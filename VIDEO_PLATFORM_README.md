# Video Platform Implementation for Reality Check Guide

## Overview

This implementation adds a comprehensive video platform to the existing Laravel application, allowing creators to upload, price, and sell videos while providing viewers with a pay-per-class model.

## Key Features

### 🎥 **Free First Video Intro**
- Each creator can upload one free 1-minute introduction video
- Intro videos are accessible to all users without purchase
- Only one intro video allowed per creator

### 💰 **Per-Class Payment Model**
- Users pay for individual videos instead of subscriptions
- No commitment - users can watch one or two videos and quit
- Transparent pricing with no hidden fees

### 📥 **Downloadable Videos**
- Users can download videos they have purchased
- Free intro videos are also downloadable
- Secure access control prevents unauthorized downloads

### 🎯 **Dynamic Pricing for Creators**
- New creators start with limited pricing range ($0.99 - $19.99)
- Unlock custom pricing after selling 15 videos
- Configurable price caps to prevent abuse

## Database Structure

### Tables Created

1. **`videos`** - Core video information
   - `id`, `title`, `description`, `video_path`, `thumbnail_path`
   - `duration`, `is_intro`, `price`, `downloads_enabled`
   - `creator_id`, `category_id`, `tags`, `status`
   - `views_count`, `purchases_count`

2. **`video_purchases`** - Track video purchases
   - `user_id`, `video_id`, `amount_paid`, `payment_method`
   - `transaction_id`, `status`, `purchased_at`, `expires_at`

3. **`video_downloads`** - Track download history
   - `user_id`, `video_id`, `ip_address`, `user_agent`, `downloaded_at`

4. **`creator_pricing_rules`** - Manage creator pricing
   - `creator_id`, `videos_sold_threshold`, `max_price_cap`
   - `min_price_floor`, `custom_pricing_enabled`, `pricing_tiers`

## Models

### Core Models

- **`Video`** - Main video model with access control methods
- **`VideoPurchase`** - Purchase tracking and validation
- **`VideoDownload`** - Download history and analytics
- **`CreatorPricingRule`** - Dynamic pricing management

### User Model Extensions

The existing `User` model has been extended with:
- `videos()` - Videos created by the user
- `purchasedVideos()` - Videos purchased by the user
- `downloadedVideos()` - Videos downloaded by the user
- `pricingRules()` - Creator pricing rules
- `isCreator()` - Check if user has Creator role
- `getTotalVideosSoldAttribute()` - Total videos sold
- `canSetCustomPricing()` - Check pricing permissions

## Controllers

### VideoController
- **CRUD operations** for videos
- **Access control** and validation
- **Purchase handling** and download management
- **Pricing validation** based on creator rules

### CreatorController
- **Creator dashboard** with analytics
- **Pricing rules management**
- **Earnings tracking** and reporting
- **Performance analytics** by video and category

## Services

### VideoAccessService
Centralized business logic for:
- **Video access control** (free intro vs. purchased)
- **Download permissions** and validation
- **Pricing validation** and recommendations
- **Cache management** for performance
- **Statistics** and analytics

## Routes

### Public Video Routes (Authenticated Users)
```
GET  /videos                    - Browse all videos
GET  /videos/{video}           - View specific video
POST /videos/{video}/purchase  - Purchase a video
GET  /videos/{video}/download  - Download a video
```

### Creator-Only Routes
```
GET    /creator/dashboard           - Creator dashboard
GET    /creator/videos              - Create new video
POST   /creator/videos              - Store new video
GET    /creator/videos/{id}/edit    - Edit video
PUT    /creator/videos/{id}         - Update video
DELETE /creator/videos/{id}         - Delete video
GET    /creator/pricing-rules       - Manage pricing
PUT    /creator/pricing-rules       - Update pricing
GET    /creator/analytics           - View analytics
GET    /creator/earnings            - View earnings
```

## Business Rules Implementation

### Free Intro Video Rules
```php
// Only one intro video per creator
if ($request->boolean('is_intro')) {
    if ($user->hasIntroVideo()) {
        return redirect()->back()
            ->withErrors(['is_intro' => 'You already have an intro video.']);
    }
    
    // Intro videos must be free
    if ($request->price > 0) {
        return redirect()->back()
            ->withErrors(['price' => 'Intro videos must be free.']);
    }
}
```

### Pricing Validation Rules
```php
// New creators: $0.99 - $19.99
// After 15 sales: Custom pricing within limits
if (!$pricingRules->custom_pricing_enabled) {
    $totalSold = $creator->getTotalVideosSoldAttribute();
    if ($totalSold < $pricingRules->videos_sold_threshold) {
        return ['valid' => false, 'message' => 'Need more sales to unlock custom pricing'];
    }
}
```

### Access Control Rules
```php
public function canUserAccess($userId = null)
{
    // Free intro videos are accessible to everyone
    if ($this->is_intro) {
        return true;
    }
    
    // Check if user has purchased this video
    return $this->purchases()
        ->where('user_id', $userId)
        ->where('status', 'completed')
        ->exists();
}
```

## Installation & Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Default Data
```bash
php artisan db:seed --class=CreatorPricingRulesSeeder
```

### 3. Create Storage Links
```bash
php artisan storage:link
```

### 4. Configure File Uploads
Ensure your `.env` file has proper file upload settings:
```env
FILESYSTEM_DISK=public
UPLOAD_MAX_FILESIZE=100M
POST_MAX_SIZE=100M
```

## Usage Examples

### Creating a Video (Creator)
```php
// Check if user can create videos
if (Auth::user()->isCreator()) {
    $video = Video::create([
        'title' => 'My First Video',
        'description' => 'Learn about my expertise',
        'is_intro' => true,  // Free intro video
        'price' => 0.00,     // Must be free for intro
        'creator_id' => Auth::id(),
        // ... other fields
    ]);
}
```

### Checking Video Access (Viewer)
```php
$video = Video::find($id);
$user = Auth::user();

if ($video->canUserAccess($user->id)) {
    // User can watch this video
    $video->incrementViews();
} else {
    // User needs to purchase
    return redirect()->route('videos.show', $video)
        ->with('error', 'Please purchase this video to watch.');
}
```

### Managing Creator Pricing
```php
$creator = Auth::user();
$pricingRules = $creator->pricingRules;

if ($pricingRules->custom_pricing_enabled) {
    // Creator can set custom prices within limits
    $maxPrice = $pricingRules->max_price_cap;
    $minPrice = $pricingRules->min_price_floor;
} else {
    // Use default pricing range
    $maxPrice = 19.99;
    $minPrice = 0.99;
}
```

## Security Features

### Access Control
- **Role-based middleware** for creator routes
- **Video ownership validation** for updates/deletes
- **Purchase verification** for downloads
- **IP tracking** for download analytics

### File Security
- **Secure file storage** in public disk
- **File type validation** (video formats only)
- **Size limits** (100MB max for videos)
- **Thumbnail validation** (images only)

### Payment Security
- **Transaction ID generation** for tracking
- **Purchase status validation** for access
- **Duplicate purchase prevention** with unique constraints

## Performance Optimizations

### Caching
- **Video access cache** (5 minutes TTL)
- **User purchase cache** for repeated checks
- **Category and creator data** caching

### Database Indexes
- **Composite indexes** for common queries
- **Foreign key indexes** for relationships
- **Status and type indexes** for filtering

### Eager Loading
- **Relationship loading** to prevent N+1 queries
- **Selective field loading** for large datasets
- **Pagination** for video listings

## Customization & Extension

### Adding New Video Types
```php
// In Video model
protected $casts = [
    'is_intro' => 'boolean',
    'is_premium' => 'boolean',  // New field
    'is_series' => 'boolean',   // New field
];

// Add corresponding database migration
$table->boolean('is_premium')->default(false);
$table->boolean('is_series')->default(false);
```

### Extending Pricing Rules
```php
// In CreatorPricingRule model
protected $casts = [
    'bulk_discounts' => 'array',      // New field
    'subscription_pricing' => 'array', // New field
];

// Add to migration
$table->json('bulk_discounts')->nullable();
$table->json('subscription_pricing')->nullable();
```

### Adding New Access Rules
```php
// In VideoAccessService
public function canUserAccessVideo(User $user, Video $video): bool
{
    // Existing rules...
    
    // New rule: Premium subscribers
    if ($user->hasActiveSubscription() && $video->is_premium) {
        return true;
    }
    
    // New rule: Series access
    if ($this->hasUserPurchasedSeries($user, $video->series_id)) {
        return true;
    }
    
    return false;
}
```

## Testing

### Unit Tests
```bash
php artisan test --filter=VideoTest
php artisan test --filter=VideoAccessServiceTest
```

### Feature Tests
```bash
php artisan test --filter=VideoControllerTest
php artisan test --filter=CreatorControllerTest
```

## Monitoring & Analytics

### Key Metrics to Track
- **Video upload rates** by creator
- **Purchase conversion rates** by video
- **Download patterns** and user behavior
- **Revenue trends** by creator and category
- **User engagement** with intro videos

### Performance Monitoring
- **File upload times** and success rates
- **Video streaming performance** and buffering
- **Database query performance** for video listings
- **Cache hit rates** for access control

## Troubleshooting

### Common Issues

1. **Video upload fails**
   - Check file size limits in `.env`
   - Verify storage disk configuration
   - Check file permissions on storage directory

2. **Access denied errors**
   - Verify user authentication
   - Check role assignments
   - Validate video ownership

3. **Pricing validation fails**
   - Check creator's pricing rules
   - Verify sales threshold requirements
   - Validate price range constraints

### Debug Commands
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Check video access
php artisan tinker
>>> $video = App\Models\Video::first();
>>> $user = App\Models\User::first();
>>> $video->canUserAccess($user->id);
```

## Future Enhancements

### Planned Features
- **Video series** and bundle pricing
- **Subscription tiers** for power users
- **Advanced analytics** and reporting
- **Mobile app API** endpoints
- **Video processing** with FFmpeg
- **CDN integration** for global delivery

### Scalability Considerations
- **Video transcoding** for multiple formats
- **Progressive streaming** for better UX
- **Geographic pricing** by region
- **Multi-currency** support
- **Bulk operations** for creators

## Support & Maintenance

### Regular Maintenance Tasks
- **Clean up old video files** (30+ days)
- **Optimize database indexes** monthly
- **Monitor storage usage** and cleanup
- **Update pricing rules** as business evolves
- **Backup video files** regularly

### Monitoring Alerts
- **Storage space** usage alerts
- **Upload failure** rate monitoring
- **Purchase completion** rate alerts
- **Video access** error monitoring
- **Performance degradation** alerts

---

This implementation provides a solid foundation for a video platform while maintaining clean code principles and allowing for future business model changes. The service-based architecture makes it easy to modify rules and add new features without affecting the core functionality.
