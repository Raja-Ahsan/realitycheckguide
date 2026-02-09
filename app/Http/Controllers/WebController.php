<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Package;
use App\Models\Banner;
use App\Models\HomeSlider;
use App\Models\Testimonial;
use App\Models\AboutUs; 
use App\Models\City;
use App\Models\MemberDirectory;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Exception\CardException;
use App\Models\Payment;
use App\Models\JobPost;
use App\Models\Project;
use App\Models\PaymentDetail; 
use Illuminate\Support\Facades\Mail;
use App\Models\Team;
use App\Models\ContactUs;
use App\Models\Event;
use Spatie\Permission\Models\Role;

class WebController extends Controller
{
    public function login()
    {
        $page_title = 'Submittal Builder'; 
       /*  $testimonials = Testimonial::where('status', '=', 1)->get();
        $categories = Category::where('status', 1)->get();
        $abouts = AboutUs::where('status', 1)->get();
        $states = City::where('status', 1)->get();
        $cities = State::where('status', 1)->get();
        $jobposts = JobPost::where('status', 1)->get();
        $homesliders = HomeSlider::where('status',  1)->get(); */
        // return view('auth.login', compact('abouts', 'categories', 'jobposts', 'page_title', 'homesliders', 'testimonials', 'cities', 'states',));
        return view('auth.login', compact('page_title'));
    }


    public function authenticate(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!empty($user) && $user->status == 1 && $user->hasRole($request->user_type)) {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                return redirect()->route('dashboard');
            }
            return redirect()->back()->with('error', 'Failed to login try again.!');
        } elseif (!empty($user) && $user->status == 0) {
            return redirect()->back()->with('error', 'Your account is not active verify your email we have sent you verification link.!');
        } else {
            return redirect()->back()->with('error', 'This is only for user login not found your account!');
        }
    }

    public function verifyEmail($token)
    {
        $user = User::where('verify_token', $token)->first();
        if (!empty($user)) {
            $user->verify_token = null;
            $user->email_verified_at = date('Y-m-d H:i:s');
            if (!empty($user->temprary_email)) {
                $user->email = $user->temprary_email;
                $user->temprary_email = null;
            }
            $user->status = 1; // Activate user upon verification
            $user->update();

            $page_title = 'Email Verified Successfully';
            return view('auth.email-verified', compact('page_title'));
        } else {
            $page_title = 'Verification Failed';
            return redirect()->route('login')->with('error', 'Your verification token is invalid or has expired. Please request a new verification email.');
        }
    }

    public function resendVerificationForm()
    {
        $page_title = 'Resend Verification Email';
        return view('auth.resend-verification', compact('page_title'));
    }

    public function resendVerification(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $user = User::where('email', $request->email)->where('status', 0)->first();
        if (!$user) {
            return redirect()->back()
                ->with('error', 'No inactive account found with this email. If you already verified, try logging in.')
                ->withInput($request->only('email'));
        }

        do {
            $verify_token = uniqid();
        } while (User::where('verify_token', $verify_token)->first());
        $user->verify_token = $verify_token;
        $user->save();

        try {
            $details = [
                'from' => 'verify',
                'title' => 'Verify your account - Reality Check Guide',
                'body' => 'Click the link below to verify your email address.',
                'verify_token' => $user->verify_token,
            ];
            Mail::to($user->email)->send(new \App\Mail\Email($details));
        } catch (\Exception $e) {
            Log::error('Resend verification email failed: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'We could not send the verification email. Please try again later or contact support.');
        }

        return redirect()->route('login')->with('message', 'Verification email sent! Please check your inbox (and spam folder) and click the link to activate your account.');
    }

    //Reset password
    public function forgotPassword()
    {
        $page_title = 'Forgot Password';
        return view('auth.passwords.forgot-password', compact('page_title'));
    }

    public function passwordResetLink(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('status', 1)->first();
        if (!empty($user)) {
            $page_title = 'Change Password';
            do {
                $verify_token = uniqid();
            } while (User::where('verify_token', $verify_token)->first());

            $user->verify_token = $verify_token;
            $user->update();

            $details = [
                'from' => 'password-reset',
                'title' => "Hello, {$user->name} {$user->last_name}!",
                'body' => "You are receiving this email because we recieved a password reset request for your account.",
                'verify_token' => $user->verify_token,
            ];

            Mail::to($user->email)->send(new \App\Mail\Email($details));

            return redirect()->route('login')->with('message', 'We have emailed your password reset link!');
        } else {
            return redirect()->back()->with('error', 'Your email address is not matched.');
        }
    }

    public function resetPassword($verify_token)
    {
        $page_title = 'Reset Password';
        return view('auth.passwords.change', compact('page_title', 'verify_token'));
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|same:confirm-password',
        ]);

        $user = User::where('verify_token', $request->verify_token)->where('status', 1)->first();
        $user->password = Hash::make($request->password);
        $user->verify_token = null;
        $user->update();

        if ($user) {
            return redirect()->route('login')->with('message', 'You have updated password. You can login again.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong try again');
        }
    }

    public function sendEmail(Request $request)
    {
        if (!isset($request->type)) {
            $this->validate($request, [
                'email' => 'required|email|unique:users,email',
            ]);
        }

        $user = User::where('email', Auth::user()->email)->first();

        do {
            $verify_token = uniqid();
        } while (User::where('verify_token', $verify_token)->first());

        $user->temprary_email = $request->email;
        $user->verify_token = $verify_token;
        $user->update();

        $details = [
            'from' => 'verify',
            'title' => "We have recieved update email request. First, you need to confirm your account. Just press the button below.",
            'body' => "If you have any questions, just reply to this email—we're always happy to help out.",
            'verify_token' => $user->verify_token,
        ];

        Mail::to($request->email)->send(new \App\Mail\Email($details));

        return redirect()->back()->with('message', 'We have sent verification email. Click on link and get activation');
    }
    public function index() 
    {
        $page_title = 'Reality Check Guide';
        $categories = Category::where('status', 1)->orderBy('id', 'ASC')->get();
        return view('website.index', compact('page_title', 'categories'));
    }
    
    public function allCategories()
    {
        $page_title = 'All Career Categories';
        $categories = Category::where('status', 1)->orderBy('title', 'ASC')->get();
        $banner = Banner::where('slug', 'categories')->where('status', 1)->first();
        return view('website.categories', compact('page_title', 'categories', 'banner'));
    }
    
    public function submitContactForm(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|max:20',
            'message' => 'required|max:1000',
        ]);

        // Save to database
        $contact = new ContactUs();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;
        $contact->save();

        // Send email notification to admin
        try {
            $adminEmail = config('mail.from.address', 'admin@realitycheckguide.com');
            
            $details = [
                'from' => 'contact-form',
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message,
            ];

            Mail::to($adminEmail)->send(new \App\Mail\Email($details));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Contact form email error: ' . $e->getMessage());
        }

        return redirect()->route('contact-us')->with('success', 'Thank you for contacting us! Your message has been sent successfully. We will get back to you soon.');
    }
    
    public function categoryDetail($slug)
    {
        $category = Category::where('slug', $slug)->where('status', 1)->first();
        if (!$category) {
            return redirect()->route('categories')->with('error', 'Category not found');
        }
        $page_title = $category->title;
        $banner = Banner::where('slug', 'category-detail')->where('status', 1)->first();
        $relatedCategories = Category::where('status', 1)
            ->where('id', '!=', $category->id)
            ->orderBy('title', 'ASC')
            ->limit(6)
            ->get();
        return view('website.category-detail', compact('page_title', 'category', 'banner', 'relatedCategories'));
    }
    public function AboutUs() 
    {
        $page_title = 'About Us';
        $abouts = AboutUs::where('status', 1)->get();
        return view('website.about-us', compact('page_title', 'abouts'));
    }

    public function Benefits()
    {
        $page_title = 'Benefits';
        $benefits = AboutUs::where('status', 1)->get();
        return view('website.benefits', compact('page_title', 'benefits'));
    }

    public function MemberDirectory()
    {
        $page_title = 'Member Directory';
        $members = MemberDirectory::where('status', 'Approved')->get();
        return view('website.member-directory', compact('page_title', 'members'));
    }

    public function Registration()
    {
        $page_title = 'Registration';
        $packages = Package::where('status', 1)->get();
        $roles = Role::whereIn('name', ['Viewer', 'Creator'])->get();
        $banner = Banner::where('slug', request()->route()->getName())->where('status', 1)->first();
        return view('website.registration', compact('page_title', 'packages', 'roles', 'banner'));
    }

    public function Events()
    {
        $banner = Banner::where('id', 7)->where('status', 1)->first();
        $page_title = 'Events | Submittal Builder';
        $events = Event::where('status', 1)->orderBy('date', 'asc')->get();
        return view('website.events', compact('page_title', 'banner', 'events'));
    }

    public function Careers()
    {
        $page_title = 'Careers';
        return view('website.careers', compact('page_title'));
    }

    public function ProjectHub()
    {
        $page_title = 'Project Hub';
        $projects = Project::where('status', 'Approved')->get();
        return view('website.project-hub', compact('page_title', 'projects'));
    }

    public function Gallery()
    {
        $page_title = 'Gallery';
        return view('website.gallery', compact('page_title'));
    }

    public function ContactUs()
    {
        $page_title = 'Contact Us';
        return view('website.contact-us', compact('page_title'));
    }

    public function instructionsForEasyUpload()
    {
        $page_title = 'Instructions for Easy Upload';
        $banner = Banner::where('slug', 'instructions')->where('status', 1)->first();
        return view('website.instructions-upload', compact('page_title', 'banner'));
    }

    public function getStates(Request $request)
    {
        $city_id = $request->city_id;
        $states = State::where('city_id', $city_id)->where('status', 1)->get();
        return response()->json($states);
    }

    public function getCity(Request $request)
    {
        return State::where('city_id', $request->city_id)->get();
    }

    public function Stripe()
    {
        $banner = Banner::where('status', 1)->first();
        $page_title = 'Stripe Payment';
        return view('website.stripe', compact('page_title', 'banner'));
    }


    public function ThankYou()
    {
        $banner = Banner::where('status', 1)->first();
        $page_title = 'Thank You';
        return view('website.thank-you', compact('page_title', 'banner'));
    }

    public function AgentDetail($id)
    {
        $banner = Banner::where('status', 1)->first();
        $page_title = 'Contractor Detail';
        $agent_detail = User::where('id', $id)->first();
        $contacts = Contact::where('status', 1)->where('agent_id', $id)->get();
        return view('website.contractor-detail', compact('page_title', 'banner', 'contacts', 'agent_detail'));
    }

    public function SignUp()
    {
        $package_id = 1; // $_GET['package_id'];
        $package = Package::where('id', $package_id)->first();
        $page_title = 'Sign Up';
        $banner = Banner::where('slug', request()->route()->getName())->where('status', 1)->first();
        $packages = Package::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();
        
        // Get available roles for registration
        $roles = Role::whereIn('name', ['Viewer', 'Creator'])->get();
        
        // If no roles found, create them (fallback)
        if ($roles->count() == 0) {
            Log::warning('No roles found, creating default roles');
            $viewerRole = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);
            $creatorRole = Role::firstOrCreate(['name' => 'Creator', 'guard_name' => 'web']);
            $roles = collect([$viewerRole, $creatorRole]);
        }
        
        // Debug logging
        Log::info('SignUp method - Package info:', [
            'package_id' => $package_id,
            'package_price' => $package ? $package->price : 'Package not found',
            'package_title' => $package ? $package->title : 'N/A'
        ]);
        
        return view('website.sign-up', compact('page_title', 'packages', 'roles', 'package', 'categories', 'banner'));
    }

    public function storeUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|same:password_confirmation',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:Viewer,Creator',
            'package_id' => 'required|exists:packages,id',
            'package_description' => 'required|string',
        ]);

        if ($request->amount > 0) {
            $this->validate($request, ['stripeToken' => 'required']);
        }

        $response = null;
        if ($request->amount > 0) {
            Stripe::setApiKey(config('services.stripe.secret'));
            try {
                $customer = Customer::create([
                    'email' => $request->email,
                    'source' => $request->stripeToken,
                ]);
                $response = Charge::create([
                    'customer' => $customer->id,
                    'amount' => 100 * $request->amount,
                    'currency' => 'usd',
                    'description' => $request->package_description,
                ]);
            } catch (\Exception $e) {
                Log::error('Stripe error: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()])->withInput();
            }
            if ($response->status !== 'succeeded') {
                return back()->withErrors(['error' => 'Payment was not successful. Please try again.'])->withInput();
            }
        }

        try {
            do {
                $verify_token = uniqid();
            } while (User::where('verify_token', $verify_token)->first());

            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'category_id' => isset($request->category_id) ? json_encode($request->category_id) : null,
                'expiry_date' => isset($request->expiry_date) ? date('Y-m-d', strtotime($request->expiry_date)) : null,
                'status' => 0,
                'package_id' => $request->package_id,
                'verify_token' => $verify_token,
            ]);
            $user->assignRole($request->input('role'));
            $this->handleRoleSpecificRegistration($user, $request->input('role'));

            $order_number = rand(10000, 99999);
            Payment::create([
                'customer_id' => $user->id,
                'order_number' => $order_number,
                'total_payment' => $request->amount ?? 0,
                'paid' => $request->amount ?? 0,
                'dues' => '0',
                'payment_status' => $response ? $response->status : 'completed',
                'package_id' => $request->package_id,
            ]);
            if ($response && $response->status === 'succeeded') {
                $source = is_object($response->source) ? $response->source : null;
                PaymentDetail::create([
                    'order_number' => $order_number,
                    'transaction_id' => $response->id,
                    'transaction_status' => $response->status,
                    'name_on_card' => $request->name_on_card ?? null,
                    'expiration_month' => $source->exp_month ?? null,
                    'expiration_year' => $source->exp_year ?? null,
                    'transaction_date' => date('Y-m-d'),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            if ($request->amount > 0) {
                return back()->withErrors(['error' => 'An error occurred while processing your payment. Please try again.'])->withInput();
            }
            return back()->withErrors(['error' => 'An error occurred during registration. Please try again.'])->withInput();
        }

        try {
            $details = [
                'from' => 'verify',
                'title' => 'We have received your registration. Please verify your account.',
                'body' => 'Click the link below to verify your email address.',
                'verify_token' => $user->verify_token,
            ];
            Mail::to($user->email)->send(new \App\Mail\Email($details));
        } catch (\Exception $e) {
            Log::error('Verification email failed: ' . $e->getMessage());
            return redirect()->route('login')->with('warning', 'Account created successfully, but we could not send the verification email. Please use "Forgot Password" to set your password, or contact support for a verification link.');
        }

        return redirect()->route('login')->with('message', 'Registration successful! Please check your email to verify your account.');
    }


   
    /**
     * Handle role-specific post-registration logic
     */
    private function handleRoleSpecificRegistration($user, $role)
    {
        // Note: Welcome emails are sent via the verification email
        // This method can be extended for other role-specific logic if needed
        // For now, we'll skip sending separate welcome emails to avoid conflicts
        // The verification email already contains welcome information
    }

    public function __construct()
    {
        $this->middleware(['auth', 'role:Creator'])->only(['OurContractors', 'AgentDetail']);
    }

 

    public function OurContractors()
    {
        $page_title = 'Our Contractors';

        // Fetch Top Rated Contractors
        $topRated = User::whereHas('roles', function ($q) {
            $q->where('name', 'Contractor');
        })
            ->where('status', 1)
            ->where('top_rated', 1)
            ->get();

        // Fetch All Contractors (excluding top-rated)
        $allContractors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Contractor');
        })
            ->where('status', 1)
            ->where('top_rated', 0)
            ->get();

        // Pass both variables to the view
        return view('website.our-contractors', compact('page_title', 'topRated', 'allContractors'));
    }

   
}
