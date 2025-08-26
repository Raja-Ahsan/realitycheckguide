<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Payout;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Show creator's wallet dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            return redirect()->back()->with('error', 'Wallet not found.');
        }

        // Get recent transactions
        $recentTransactions = $wallet->transactions()
            ->with('wallet')
            ->latest()
            ->take(10)
            ->get();

        // Get recent payouts
        $recentPayouts = $user->payouts()
            ->latest()
            ->take(5)
            ->get();

        // Get monthly earnings
        $monthlyEarnings = $wallet->transactions()
            ->where('type', 'credit')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        return view('creator.wallet.dashboard', compact(
            'wallet',
            'recentTransactions',
            'recentPayouts',
            'monthlyEarnings'
        ));
    }

    /**
     * Show wallet transactions
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            return redirect()->back()->with('error', 'Wallet not found.');
        }

        $transactions = $wallet->transactions()
            ->with('wallet')
            ->when($request->type, function($query, $type) {
                return $query->where('type', $type);
            })
            ->latest()
            ->paginate(20);

        return view('creator.wallet.transactions', compact('wallet', 'transactions'));
    }

    /**
     * Show payout history
     */
    public function payouts()
    {
        $user = Auth::user();
        $payouts = $user->payouts()
            ->with('wallet')
            ->latest()
            ->paginate(15);

        return view('creator.wallet.payouts', compact('payouts'));
    }

    /**
     * Request a payout
     */
    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10.00|max:10000.00',
            'payout_method' => 'required|in:stripe,bank_transfer,manual',
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            return redirect()->back()->with('error', 'Wallet not found.');
        }

        $amount = (float) $request->amount;

        // Check if wallet has sufficient balance
        if (!$wallet->canRequestPayout($amount)) {
            return redirect()->back()->with('error', 'Insufficient balance for payout request.');
        }

        // Check minimum payout amount
        if ($amount < 10.00) {
            return redirect()->back()->with('error', 'Minimum payout amount is $10.00.');
        }

        try {
            DB::beginTransaction();

            // Create payout request
            $payout = Payout::create([
                'creator_id' => $user->id,
                'wallet_id' => $wallet->id,
                'payout_number' => Payout::generatePayoutNumber(),
                'amount' => $amount,
                'payout_method' => $request->payout_method,
                'status' => 'pending',
            ]);

            // Reserve amount in wallet
            $wallet->reserveForPayout($amount);

            // Create transaction record
            $wallet->transactions()->create([
                'creator_id' => $user->id,
                'type' => 'payout_request',
                'amount' => $amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance,
                'description' => "Payout request #{$payout->payout_number}",
                'reference_id' => $payout->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Payout request submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to submit payout request. Please try again.');
        }
    }

    /**
     * Cancel a pending payout request
     */
    public function cancelPayout(Payout $payout)
    {
        $user = Auth::user();
        
        // Check if payout belongs to user
        if ($payout->creator_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Check if payout can be cancelled
        if (!$payout->isPending()) {
            return redirect()->back()->with('error', 'Only pending payouts can be cancelled.');
        }

        try {
            DB::beginTransaction();

            // Release reserved amount
            $wallet = $user->wallet;
            $wallet->releaseReservedAmount($payout->amount);

            // Update payout status
            $payout->update(['status' => 'cancelled']);

            // Create transaction record
            $wallet->transactions()->create([
                'creator_id' => $user->id,
                'type' => 'payout_cancelled',
                'amount' => $payout->amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance,
                'description' => "Payout request #{$payout->payout_number} cancelled",
                'reference_id' => $payout->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Payout request cancelled successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel payout request. Please try again.');
        }
    }
}
