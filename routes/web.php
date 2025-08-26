<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WebController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\admin\JobPostController;
use App\Http\Controllers\admin\DocumentRepositoryController;
use App\Http\Controllers\admin\ContactUsController;
use App\Http\Controllers\admin\ProjectController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\NewsLetterController;
use App\Http\Controllers\admin\ContactController;
use App\Http\Controllers\admin\ClientContactController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\PackageController;
use App\Http\Controllers\admin\ProjectCategoryController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\TestimonialController;
use App\Http\Controllers\admin\TeamController;
use App\Http\Controllers\admin\AgentController;
use App\Http\Controllers\admin\AboutUsController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\HomeSliderController;
use App\Http\Controllers\admin\OurSponsorController;
use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\admin\JobPostCategoryController;
use App\Http\Controllers\admin\MemberDirectoryController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\PageSettingController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\FAQController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\admin\ElectricianController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\admin\BidController;
use App\Http\Controllers\admin\SubmittalController;
use App\Http\Controllers\admin\CoverTemplateController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/route-clear', function() {
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    $cache = 'Route cache cleared <br /> View cache cleared <br /> Cache cleared <br /> Config cleared <br /> Config cache cleared';
    return $cache;
});
 
Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');

Route::get('sign-up', [WebController::class, 'SignUp'])->name('sign-up');
Route::post('user/store', [WebController::class, 'storeUser'])->name('user.register.store');

Route::get('email-verification/{token}', [WebController::class, 'verifyEmail'])->name('email-verification');

//admin reset password
Route::get('admin/forgot_password', [AdminController::class, 'forgotPassword'])->name('admin.forgot_password');
Route::get('admin/send-password-reset-link', [AdminController::class, 'passwordResetLink'])->name('admin.send-password-reset-link');
Route::get('admin/reset-password/{token}', [AdminController::class, 'resetPassword'])->name('admin.reset-password');
Route::post('admin/change_password', [AdminController::class, 'changePassword'])->name('admin.change_password');


// User forgot password
Route::get('forgot-password', [WebController::class, 'forgotPassword'])->name('forgot-password');
Route::post('forgot-password', [WebController::class, 'passwordResetLink'])->name('password.reset-link');

// User reset password (from email)
Route::get('reset-password/{verify_token}', [WebController::class, 'resetPassword'])->name('reset-password');
Route::post('reset-password', [WebController::class, 'changePassword'])->name('password.change');



Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
Route::post('admin/logout', [AdminController::class, 'logOut'])->name('admin.logout');

// Video Platform Management Routes
Route::get('/admin/video-settings', [AdminController::class, 'videoSettings'])->name('admin.video-settings');
Route::post('/admin/video-settings/update', [AdminController::class, 'updateVideoSettings'])->name('admin.video-settings.update');
Route::get('/admin/video-management', [AdminController::class, 'videoManagement'])->name('admin.video-management');
Route::get('/admin/order-management', [AdminController::class, 'orderManagement'])->name('admin.order-management');
Route::get('/admin/payout-management', [AdminController::class, 'payoutManagement'])->name('admin.payout-management');
Route::post('/admin/payout/{id}/process', [AdminController::class, 'processPayout'])->name('admin.payout.process');
Route::get('/admin/creator-analytics', [AdminController::class, 'creatorAnalytics'])->name('admin.creator-analytics');
Route::get('/admin/wallet-overview', [AdminController::class, 'walletOverview'])->name('admin.wallet-overview');


Route::post('user/authenticate', [UserController::class, 'authenticate'])->name('user.authenticate');

Route::get('/creator/profile/edit', [UserController::class, 'CreatorEditProfile'])->name('creator.profile.edit');

/* Route::get('/contractor/profile/edit', [UserController::class, 'ContractorEditProfile'])->name('contractor.profile.edit'); */
Route::get('/user/profile/edit', [UserController::class, 'UserEditProfile'])->name('user.profile.edit');

Route::post('/user/profile/update', [UserController::class, 'userUpdateProfile'])->name('user.profile.update');

Route::post('user/logout', [UserController::class, 'logOut'])->name('user.logout');


//Frontend
/* Route::get('/', [HomeController::class, 'index'])->name('dashboard');  */
Route::get('/', [WebController::class, 'index'])->name('index'); 
//Route::get('/', [WebController::class, 'login'])->name('login'); 
Route::get('get_states', [WebController::class, 'getStates'])->name('get_states'); 
Route::get('about-us', [WebController::class, 'AboutUs'])->name('about-us');
Route::get('benefits', [WebController::class, 'Benefits'])->name('benefits');
Route::get('member-directory', [WebController::class, 'MemberDirectory'])->name('member-directory');
Route::get('registration', [WebController::class, 'Registration'])->name('registration');
Route::get('events', [WebController::class, 'Events'])->name('events');
Route::get('careers', [WebController::class, 'Careers'])->name('careers');
/* Route::get('how-it-works', [WebController::class, 'HowItWorks'])->name('how-it-works'); */
Route::get('leaderboard', [WebController::class, 'LeaderBoard'])->name('leaderboard');
Route::get('gallery', [WebController::class, 'Gallery'])->name('gallery');
Route::get('contact-us', [WebController::class, 'ContactUs'])->name('contact-us');
Route::get('faqs', [WebController::class, 'Faqs'])->name('faqs');
Route::get('our-services', [WebController::class, 'Services'])->name('our-services');
Route::get('privacy-policy', [WebController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('term-and-conditions', [WebController::class, 'termAndConditions'])->name('term-and-conditions');
Route::get('reviews', [WebController::class, 'Reviews'])->name('reviews');


Route::get('project-hub', [WebController::class, 'ProjectHub'])->name('project-hub');

Route::middleware(['auth', 'role:EPC Developer'])->group(function () {
    /* Route::get('our-contractors', [WebController::class, 'OurContractors'])->name('our-contractors');
    Route::get('contractor-detail/{id}', [WebController::class, 'AgentDetail'])->name('contractor-detail'); */
    Route::get('/project/details/{id}', [ProjectController::class, 'show'])->name('project.details');
    
});
Route::middleware(['auth', 'role:Viewer'])->group(function () {   
    Route::get('/jobpost/job-post-bids/{id}', [JobPostController::class, 'jobPostBids'])->name('jobpost.job-post-bids');
    Route::post('/jobpost/accept-bid/{id}', [JobPostController::class, 'acceptBid'])->name('jobpost.accept-bid');
    Route::post('/jobpost/reject-bid/{id}', [JobPostController::class, 'rejectBid'])->name('jobpost.reject-bid');
    Route::post('/jobpost/complete-job/{id}', [JobPostController::class, 'completeJob'])->name('jobpost.complete-job');
    
});

Route::get('listing', [WebController::class, 'Listing'])->name('listing');
//stripe payment
Route::get('stripe/create', [StripeController::class, 'create'])->name('stripe.create');
Route::get('stripe/checkout/{id}', [StripeController::class, 'checkout'])->name('stripe.checkout');
Route::post('stripe/store', [StripeController::class, 'store'])->name('stripe.post');


Route::get('admin/projects/pdf/{slug}', [ProjectController::class, 'downloadPDF'])->name('projects.pdf');
Route::get('admin/projects/projectspdf/{id}', [ProjectController::class, 'downloadProjectPDF'])->name('projects.gamespdf');
Route::get('admin/projects/projectshow/{id}', [ProjectController::class, 'projectShow'])->name('project.details');

//documents repository
Route::get('admin/document_repositories/pdf/{slug}', [DocumentRepositoryController::class, 'downloadPDF'])->name('documents.pdf');
Route::get('admin/document_repositories/documentpdf/{id}', [DocumentRepositoryController::class, 'downloadDocumentPDF'])->name('documents.documentspdf');
Route::get('admin/document_repositories/documentshow/{id}', [DocumentRepositoryController::class, 'documentShow'])->name('documents.documentshow');
Route::delete('documents/{id}/delete-file', [DocumentRepositoryController::class, 'deleteFile'])->name('delete-file');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('product/search', [ProductController::class, 'search'])->name('product.search');
// Route::get('remove-property-image', [PropertyController::class, 'removePropertiesImage'])->name('remove-property-image');


//NewsLetter
Route::resource('newsletter', NewsLetterController::class);

//Contact
Route::resource('contact', ContactController::class);

//Client Contact
Route::resource('client_contact', ClientContactController::class); 


Route::group(['middleware' => ['auth']], function () {
    
    //Route::get('project-hub', [WebController::class, 'ProjectHub'])->name('project-hub');
    //Roles
    Route::resource('role', RoleController::class);
    
    //ContactUs
    Route::resource('contactus', ContactUsController::class);
    
    //Stripe
    Route::get('stripe', [WebController::class, 'Stripe'])->name('stripe');

    //users
    Route::resource('admin/users', UserController::class)->names('user');

    //permissions
    Route::resource('permission', PermissionController::class);

    //Packages
    Route::resource('package', PackageController::class);

    //Category
    Route::resource('project_category', ProjectCategoryController::class);


    //Category
    Route::resource('services', CategoryController::class);
    

    //testimonial
    Route::resource('testimonial', TestimonialController::class);

    //team
    Route::resource('meet_the_team', TeamController::class);

    //Agents
    Route::resource('agents', AgentController::class);


    //About
    Route::resource('about', AboutUsController::class);

    //Setting
    Route::resource('page', PageController::class);

    //payment
    Route::resource('payment', PaymentController::class);
    
    
    //FAQS
    Route::resource('faq', FAQController::class);
    
    //Banner
    Route::resource('banner', BannerController::class);
    
    //Home Slider
    Route::resource('homeslider', HomeSliderController::class);
    
    //Our Location
    Route::resource('our_sponsor', OurSponsorController::class);

    //Projects
    Route::resource('projects', ProjectController::class);
    
    //jobposts
    Route::resource('jobpost', JobPostController::class);

    //jobcategory
    Route::resource('jobcategory', JobPostCategoryController::class);
    //Events
    Route::resource('event', EventController::class);

    // Creator Dashboard & Bidding (Creator-only) - OLD JOB SYSTEM (COMMENTED OUT)
    // Route::middleware(['auth', 'role:Creator'])->prefix('creator')->name('creator.')->group(function () {
    //     Route::get('/dashboard', [ElectricianController::class, 'dashboard'])->name('dashboard');
    //     Route::get('/jobs', [ElectricianController::class, 'jobs'])->name('jobs');
    //     Route::get('/jobs/{id}', [ElectricianController::class, 'jobDetail'])->name('job-detail');
    //     Route::post('/jobs/{id}/bid', [ElectricianController::class, 'submitBid'])->name('submit-bid');
    //     Route::get('/my-bids', [ElectricianController::class, 'myBids'])->name('my-bids');
    //     Route::put('/bids/{id}', [ElectricianController::class, 'updateBid'])->name('update-bid');
    //     Route::delete('/bids/{id}', [ElectricianController::class, 'withdrawBid'])->name('withdraw-bid');
    // });
    
    //Projects
    Route::resource('documents', DocumentRepositoryController::class);
    
    //Projects
    Route::resource('member_directory', MemberDirectoryController::class);
    // Submittals
    Route::resource('submittals', SubmittalController::class)->except(['show']);
    Route::post('submittals/{id}/upload-spec', [SubmittalController::class, 'uploadSpec'])->name('submittals.upload-spec');
    Route::post('submittals/{id}/breakout', [SubmittalController::class, 'breakoutSections'])->name('submittals.breakout');
    Route::get('submittals/{id}/export-pdf', [SubmittalController::class, 'exportPdf'])->name('submittals.export-pdf');
    Route::post('submittals/{id}/send-email', [SubmittalController::class, 'sendEmail'])->name('submittals.send-email');
    Route::post('submittals/{id}/status', [SubmittalController::class, 'updateStatus'])->name('submittals.status');
    Route::post('submittals/{id}/reminder', [SubmittalController::class, 'setReminder'])->name('submittals.reminder');
    Route::post('submittals/{id}/received', [SubmittalController::class, 'markReceived'])->name('submittals.received');
    Route::post('submittals/{id}/send-vendor', [SubmittalController::class, 'sendToVendor'])->name('submittals.send-vendor');
    Route::post('submittals/{id}/vendor-returned', [SubmittalController::class, 'vendorReturned'])->name('submittals.vendor-returned');
    Route::post('submittals/{id}/extract-comments', [SubmittalController::class, 'extractComments'])->name('submittals.extract-comments');

    // Cover Templates
    Route::resource('cover-templates', CoverTemplateController::class)->only(['index','create','store','destroy']);
    
    //pages settings
    Route::resource('page', PageController::class);
    Route::resource('page_setting', PageSettingController::class);
});

// Video Platform Routes
Route::group(['middleware' => ['auth']], function () {
    // Public video routes (for authenticated users)
    Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');
    
    // Video purchase
    Route::post('/videos/{video}/purchase', [VideoController::class, 'purchase'])->name('videos.purchase');
    
    // Video download (requires purchase or free intro)
    Route::get('/videos/{video}/download', [VideoController::class, 'download'])->name('videos.download');
    
    // Payment routes
    Route::get('/videos/{video}/buy', [PaymentController::class, 'showPurchase'])->name('videos.buy');
    Route::post('/videos/{video}/process-payment', [PaymentController::class, 'processPurchase'])->name('videos.process-payment');
    Route::post('/payment/confirm', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
});

// Stripe webhook (no auth required)
Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook'])->name('stripe.webhook');

// Creator-only video routes
Route::group(['middleware' => ['auth', 'role:Creator'], 'prefix' => 'creator'], function () {
    Route::get('/videos/create', [VideoController::class, 'create'])->name('creator.videos.create');
    Route::post('/videos', [VideoController::class, 'store'])->name('creator.videos.store');
    Route::get('/videos/{video}/edit', [VideoController::class, 'edit'])->name('creator.videos.edit');
    Route::put('/videos/{video}', [VideoController::class, 'update'])->name('creator.videos.update');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('creator.videos.destroy');
    
    // Creator dashboard and analytics
    Route::get('/dashboard', [AdminController::class, 'creatorDashboard'])->name('creator.dashboard');
    Route::get('/my-videos', [VideoController::class, 'myVideos'])->name('creator.videos.index');
    Route::get('/pricing-rules', [AdminController::class, 'creatorPricingRules'])->name('creator.pricing-rules');
    Route::put('/pricing-rules', [AdminController::class, 'updateCreatorPricingRules'])->name('creator.pricing-rules.update');
    Route::get('/analytics', [AdminController::class, 'creatorAnalytics'])->name('creator.analytics');
    Route::get('/earnings', [AdminController::class, 'creatorEarnings'])->name('creator.earnings');
    Route::get('/check-pricing-unlock', [AdminController::class, 'checkCreatorPricingUnlock'])->name('creator.check-pricing-unlock');
    
    // Creator wallet and payouts
    Route::get('/wallet', [WalletController::class, 'dashboard'])->name('creator.wallet.dashboard');
    Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('creator.wallet.transactions');
    Route::get('/wallet/payouts', [WalletController::class, 'payouts'])->name('creator.wallet.payouts');
    Route::post('/wallet/payouts', [WalletController::class, 'requestPayout'])->name('creator.wallet.request-payout');
    Route::post('/wallet/payouts/{payout}/cancel', [WalletController::class, 'cancelPayout'])->name('creator.wallet.cancel-payout');
});

// Public Creator Routes (No authentication required)
Route::prefix('creators')->name('creators.')->group(function () {
    Route::get('/', [CreatorController::class, 'index'])->name('index');
    Route::get('/{creator}', [CreatorController::class, 'show'])->name('show');
    Route::get('/{creator}/videos', [CreatorController::class, 'videos'])->name('videos');
    Route::get('/{creator}/intro-video', [CreatorController::class, 'introVideo'])->name('intro-video');
});