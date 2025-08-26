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

            return redirect()->route('login');
        } else {
            return redirect()->back()->with('error', 'Your token is expired');
        }
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
        //$abouts = AboutUs::where('status', 1)->get();
        return view('website.index', compact('page_title'));
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



        // Add Stripe token validation when payment is required
        if ($request->amount > 0) {
            Log::info('Payment required - amount > 0');
            $this->validate($request, [
                'stripeToken' => 'required',
            ]);
        } else {
            Log::info('Free registration - amount = 0');
        }

        try {
           
            //Log::info('Starting user creation process');
           

            if ($request->amount > 0) {
                Log::info('Processing payment for amount: ' . $request->amount);
              
                // Set your Stripe secret key
                Stripe::setApiKey(config('services.stripe.secret'));
                // Create a Stripe customer
                $customer = Customer::create([
                    'email' => $request->email,
                    'source' => $request->stripeToken,
                ]);
                // Create a charge
                $response = Charge::create([
                    'customer' => $customer->id,
                    'amount' => 100 * $request->amount,
                    'currency' => 'usd',
                    'description' => $request->package_description,
                ]);
              
                if ($response->status === 'succeeded') {
                    
                    // Create the user
                    $user = User::create([
                        'name' => $request->name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'phone' => $request->phone,
                        'role' => $request->role,
                        'category_id' => isset($request->category_id) ? json_encode($request->category_id) : null,
                        'expiry_date' => isset($request->expiry_date) ? date('Y-m-d', strtotime($request->expiry_date)) : null,
                        'status' => 0, // Set as inactive until email is verified
                        'package_id' => $request->package_id,
                    ]);
                    
                    $user->assignRole($request->input('role'));
                    $userId = $user->id;
                   
                    // Generate and save verification token
                    do {
                        $verify_token = uniqid();
                    } while (User::where('verify_token', $verify_token)->first());
                    $user->verify_token = $verify_token;
                    $user->save();
                    // Send verification email
                    $details = [
                        'from' => 'verify',
                        'title' => "We have received your registration. Please verify your account.",
                        'body' => "Click the link below to verify your email address.",
                        'verify_token' => $user->verify_token,
                    ];
                    Mail::to($user->email)->send(new \App\Mail\Email($details));

                    $order_number = rand(10000, 99999);
                    $payment = Payment::create([
                        'customer_id' => $userId,
                        'order_number' => $order_number,
                        'total_payment' => $request->amount,
                        'paid' => $request->amount,
                        'dues' => '0',
                        'payment_status' => $response->status,
                        'package_id' => $request->package_id,
                    ]);

                    if ($payment) {
                        PaymentDetail::create([
                            'order_number' => $order_number,
                            'transaction_id' => $response->id,
                            'transaction_status' => $response->status,
                            'name_on_card' => $request->name_on_card,
                            'expiration_month' => $response->source->exp_month,
                            'expiration_year' => $response->source->exp_year,
                            'transaction_date' => date('Y-m-d'),
                        ]);
                    }
                    return redirect()->route('login')->with('message', 'Registration successful! Please check your email to verify your account.');
                } else {
                    return back()->withErrors(['error' => 'Payment was not successful. Please try again.'])->withInput();
                }
            } else {
                
                Log::info('Processing free registration - no payment required');
                Log::info('About to create user with data:', $request->only(['name', 'email', 'role', 'package_id']));
                // For the case where no payment is made (amount = 0 or free registration)
                Log::info('Creating user with data:', [
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'role' => $request->role,
                    'package_id' => $request->package_id,
                ]);
                
                
                $user = User::create([
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'role' => $request->role,
                    'category_id' => isset($request->category_id) ? json_encode($request->category_id) : null,
                    'status' => 0, // Set as inactive until email is verified
                    'package_id' => $request->package_id,
                ]);
                // Assign the selected role
                $user->assignRole($request->input('role'));
                $userId = $user->id;
                
                // Role-specific post-registration logic
                $this->handleRoleSpecificRegistration($user, $request->input('role'));
                
                // Generate and save verification token
                do {
                     $verify_token = uniqid();
                } while (User::where('verify_token', $verify_token)->first());
                
                $user->verify_token = $verify_token;
                $user->save();
                // Send verification email
                $details = [
                     'from' => 'verify',
                     'title' => "We have received your registration. Please verify your account.",
                     'body' => "Click the link below to verify your email address.",
                     'verify_token' => $user->verify_token,
                ];

                //Mail::to($user->email)->send(new \App\Mail\Email($details));

                // Create payment record for free package
                $order_number = rand(10000, 99999);
                Payment::create([
                     'customer_id' => $userId,
                     'order_number' => $order_number,
                     'total_payment' => 0,
                     'paid' => 0,
                     'dues' => '0',
                     'payment_status' => 'completed',
                     'package_id' => $request->package_id,
                ]);

                return redirect()->route('login')->with('message', 'Registration successful! Please check your email to verify your account.');
            }
        } catch (\Exception $e) {
            Log::error('Registration ddderror: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->amount > 0) {
                return back()->withErrors(['error' => 'An error occurred while processing your payment. Please try again.'])->withInput();
            } else {
                return back()->withErrors(['error' => 'An error occurred during registration. Please try again.'])->withInput();
            }
        }
    }


   
    /**
     * Handle role-specific post-registration logic
     */
    private function handleRoleSpecificRegistration($user, $role)
    {
        switch ($role) {
            case 'Creator':
                // Send welcome email with creator-specific information
                $details = [
                    'from' => 'welcome_creator',
                    'title' => "Welcome to Reality Check Guide as a Creator!",
                    'body' => "You can now browse available jobs and submit bids. Complete your profile to get started.",
                ];
                Mail::to($user->email)->send(new \App\Mail\Email($details));
                break;
                
            case 'Viewer':
                // Send welcome email with viewer-specific information
                $details = [
                    'from' => 'welcome_viewer',
                    'title' => "Welcome to Reality Check Guide as a Viewer!",
                    'body' => "You can now post job requests and projects. Complete your profile to get started.",
                ];
                Mail::to($user->email)->send(new \App\Mail\Email($details));
                break;
        }
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
