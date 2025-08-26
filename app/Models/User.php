<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    
    public function hasCity()
    {
        return $this->belongsTo(City::class, 'city_id'); // Replace 'city_id' with the actual foreign key in the users table.
    }

    public function hasState()
    {
        return $this->belongsTo(State::class, 'state_id'); // Replace 'state_id' with the actual foreign key in the users table.
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class, 'customer_id', 'id'); // Assuming customer_id in payments refers to id in users
    }

    // Add the storedProjects relationship
    public function storedProjects()
    {
        return $this->belongsToMany(Project::class, 'stored_projects', 'user_id', 'project_id');
    }

    // Add the categories relationship (for user interests)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'user_categories', 'user_id', 'category_id');
    }

    // Bids as electrician
    public function bids()
    {
        return $this->hasMany(Bid::class, 'electrician_id');
    }

    // Job posts created by user
    public function jobPosts()
    {
        return $this->hasMany(JobPost::class, 'created_by');
    }

    // Bids received by user (for job posts they created)
    public function receivedBids()
    {
        return $this->hasMany(Bid::class, 'user_id');
    }

    // Video platform relationships
    /**
     * Videos created by this user (as a creator)
     */
    public function videos()
    {
        return $this->hasMany(Video::class, 'creator_id');
    }

    /**
     * Videos purchased by this user
     */
    public function purchasedVideos()
    {
        return $this->hasMany(VideoPurchase::class);
    }

    /**
     * Videos downloaded by this user
     */
    public function downloadedVideos()
    {
        return $this->hasMany(VideoDownload::class);
    }

    /**
     * Alias for purchasedVideos relationship
     */
    public function videoPurchases()
    {
        return $this->purchasedVideos();
    }

    /**
     * Alias for downloadedVideos relationship
     */
    public function videoDownloads()
    {
        return $this->downloadedVideos();
    }

    /**
     * Pricing rules for this creator
     */
    public function pricingRules()
    {
        return $this->hasOne(CreatorPricingRule::class, 'creator_id');
    }

    /**
     * Check if user is a creator
     */
    public function isCreator()
    {
        return $this->hasRole('Creator');
    }

    /**
     * Get total videos sold by this creator
     */
    public function getTotalVideosSoldAttribute()
    {
        if (!$this->isCreator()) {
            return 0;
        }

        return $this->videos()
            ->whereHas('purchases', function($query) {
                $query->where('status', 'completed');
            })
            ->count();
    }

    /**
     * Check if creator can set custom pricing
     */
    public function canSetCustomPricing()
    {
        if (!$this->isCreator()) {
            return false;
        }

        $pricingRules = $this->pricingRules;
        if (!$pricingRules) {
            return false;
        }

        return $pricingRules->custom_pricing_enabled;
    }

    /**
     * Get the first intro video for this creator
     */
    public function getIntroVideo()
    {
        return $this->videos()
            ->where('is_intro', true)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Get the intro video relationship (alias for easier access)
     */
    public function introVideo()
    {
        return $this->hasOne(Video::class, 'creator_id')
            ->where('is_intro', true)
            ->where('status', 'active');
    }

    /**
     * Check if creator already has an intro video
     */
    public function hasIntroVideo()
    {
        return $this->videos()
            ->where('is_intro', true)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get the user's wallet
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'creator_id');
    }

    /**
     * Get the user's orders (as buyer)
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Get the user's created videos (as creator)
     */
    public function createdVideos()
    {
        return $this->hasMany(Video::class, 'creator_id');
    }

    /**
     * Get the user's payout requests
     */
    public function payouts()
    {
        return $this->hasMany(Payout::class, 'creator_id');
    }

    /**
     * Get the user's wallet transactions
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'creator_id');
    }
}
