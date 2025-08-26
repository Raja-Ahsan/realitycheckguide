<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\AdminSetting;
use App\Models\VideoPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Show video purchase page
     */
    public function showPurchase(Video $video)
    {
        $user = Auth::user();
        
        // Check if user already owns this video
        if ($video->canUserAccess($user->id)) {
            return redirect()->route('videos.show', $video)
                ->with('info', 'You already own this video.');
        }

        // Check if video is free intro
        if ($video->is_intro) {
            return redirect()->route('videos.show', $video)
                ->with('info', 'This video is free for everyone.');
        }

        // Get commission rate
        $commissionRate = AdminSetting::getCommissionRate();
        
        // Calculate earnings
        $earnings = Order::calculateEarnings($video->price, $commissionRate);

        return view('videos.purchase', compact('video', 'commissionRate', 'earnings'));
    }

    /**
     * Process video purchase
     */
    public function processPurchase(Request $request, Video $video)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        $user = Auth::user();
        
        // Check if user already owns this video
        if ($video->canUserAccess($user->id)) {
            return response()->json([
                'error' => 'You already own this video.'
            ], 400);
        }

        // Check if video is free intro
        if ($video->is_intro) {
            return response()->json([
                'error' => 'This video is free for everyone.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Get commission rate
            $commissionRate = AdminSetting::getCommissionRate();
            
            // Calculate earnings
            $earnings = Order::calculateEarnings($video->price, $commissionRate);

            // Create Stripe PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($video->price * 100), // Convert to cents
                'currency' => config('services.stripe.currency', 'usd'),
                'payment_method' => $request->payment_method_id,
                'confirm' => false, // Don't confirm immediately
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                'metadata' => [
                    'video_id' => $video->id,
                    'user_id' => $user->id,
                    'creator_id' => $video->creator_id,
                ],
            ]);

            // Create order record
            $order = Order::create([
                'user_id' => $user->id,
                'video_id' => $video->id,
                'creator_id' => $video->creator_id,
                'order_number' => Order::generateOrderNumber(),
                'amount' => $video->price,
                'commission_rate' => $commissionRate,
                'commission_amount' => $earnings['commission_amount'],
                'creator_earning' => $earnings['creator_earning'],
                'stripe_payment_intent_id' => $paymentIntent->id,
                'status' => 'completed',
                'paid_at' => now(),
                'stripe_data' => $paymentIntent->toArray(),
            ]);

            // Create video purchase record
            $videoPurchase = VideoPurchase::create([
                'user_id' => $user->id,
                'video_id' => $video->id,
                'amount_paid' => $video->price,
                'status' => 'completed',
                'purchased_at' => now(),
            ]);

            // Increment video purchase count
            $video->incrementPurchases();

            // Credit creator's wallet
            $creatorWallet = Wallet::where('creator_id', $video->creator_id)->first();
            if ($creatorWallet) {
                $creatorWallet->increment('balance', $earnings['creator_earning']);
                
                // Log wallet transaction
                $creatorWallet->transactions()->create([
                    'wallet_id' => $creatorWallet->id,
                    'creator_id' => $video->creator_id,
                    'type' => 'credit',
                    'amount' => $earnings['creator_earning'],
                    'description' => "Earning from video: {$video->title}",
                    'reference_id' => $order->id,
                    'balance_before' => $creatorWallet->balance,
                    'balance_after' => $creatorWallet->balance + $earnings['creator_earning'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'payment_intent' => $paymentIntent,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'error' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm payment success
     */
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Get payment intent from Stripe
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
            
            if ($paymentIntent->status === 'succeeded') {
                // Find the order
                $order = Order::where('stripe_payment_intent_id', $request->payment_intent_id)->first();
                
                if (!$order) {
                    throw new \Exception('Order not found for payment intent.');
                }

                // Mark order as completed
                $order->markAsPaid();

                // Update video purchase status
                VideoPurchase::where('user_id', $order->user_id)
                    ->where('video_id', $order->video_id)
                    ->update(['status' => 'completed']);

                // Add earnings to creator's wallet
                $creator = $order->creator;
                $wallet = $creator->wallet;
                
                if ($wallet) {
                    $wallet->addCredit(
                        $order->creator_earning,
                        "Video sale: {$order->video->title}",
                        [
                            'order_id' => $order->id,
                            'video_id' => $order->video_id,
                            'commission_rate' => $order->commission_rate,
                        ]
                    );
                }

                // Update creator's videos sold count
                $creator->pricingRules()->update([
                    'videos_sold' => DB::raw('videos_sold + 1')
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmed successfully!',
                    'redirect_url' => route('videos.show', $order->video),
                ]);
            } else {
                throw new \Exception('Payment not succeeded.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'error' => 'Payment confirmation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = AdminSetting::getValue('stripe_webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
            default:
                // Unexpected event type
                break;
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        // This will be handled by the confirmPayment method
        // Webhook is just a backup
    }

    /**
     * Handle failed payment
     */
    private function handlePaymentFailed($paymentIntent)
    {
        // Update order status to failed
        Order::where('stripe_payment_intent_id', $paymentIntent->id)
            ->update(['status' => 'failed']);
    }
}
