<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Video Platform Implementation for Reality Check Guide

This document outlines the implementation of a comprehensive video platform for the Reality Check Guide application, designed specifically for creators to monetize their content through a pay-per-video model with integrated wallet and payment systems.

### 🎯 Key Features

#### **Video Rules for Creators**
- **Free First Video Intro**: The first 1-minute video introduction uploaded by a creator is always free for all viewers
- **Per-Class Payment Model**: Users pay per video instead of being locked into subscriptions
- **Downloadable Videos**: Purchased videos can be downloaded by verified buyers
- **Dynamic Pricing**: Creators can set custom pricing after selling at least 15 videos

#### **Wallet + Payment Flow**
- **Stripe Integration**: Full payment goes to Admin's Stripe account first
- **Commission System**: Configurable commission rate (default 30%) managed by admin
- **Creator Wallet Tracking**: Each creator has a wallet balance tracking earnings
- **Payout System**: Creators can request payouts from their wallet balance

### 🏗️ Architecture

#### **Database Structure**
- `videos` - Video content with enhanced fields (learning objectives, prerequisites, difficulty level)
- `wallets` - Creator wallet balances and transaction history
- `wallet_transactions` - Detailed transaction log for all wallet activities
- `orders` - Video purchase records with commission calculations
- `payouts` - Creator payout requests and processing status
- `admin_settings` - Configurable platform settings (commission rates, pricing caps)

#### **Models & Relationships**
- **User Model**: Extended with wallet, orders, and payout relationships
- **Video Model**: Enhanced with new fields and order relationships
- **Wallet Model**: Manages balance, transactions, and payout operations
- **Order Model**: Handles payment processing and commission calculations
- **AdminSetting Model**: Centralized configuration management

#### **Controllers & Services**
- **VideoController**: Video CRUD operations and access control
- **CreatorController**: Creator dashboard and analytics
- **WalletController**: Wallet management and payout requests
- **PaymentController**: Stripe integration and payment processing
- **VideoAccessService**: Business logic for video access and pricing rules

### 🚀 Implementation Details

#### **Commission System**
```php
// Automatic commission calculation
$earnings = Order::calculateEarnings($videoPrice, $commissionRate);
// Returns: ['commission_amount' => $7.50, 'creator_earning' => $17.50]
```

#### **Wallet Operations**
```php
// Add earnings to creator wallet
$wallet->addCredit($amount, $description, $metadata);

// Request payout
$wallet->reserveForPayout($amount);

// Process payout
$wallet->deduct($amount, $description, $metadata);
```

#### **Dynamic Pricing Rules**
- New creators start with limited pricing range
- After selling 15 videos, custom pricing is unlocked
- Admin-configurable minimum and maximum price caps
- Automatic threshold checking and rule enforcement

### 🔐 Security Features

- **Role-based Access Control**: Creator, Viewer, and Admin roles
- **Secure Video Access**: Only paying users or free intro viewers can access content
- **Download Protection**: Secure signed URLs for video downloads
- **Payment Security**: Stripe integration with webhook verification
- **Wallet Security**: Transaction logging and balance validation

### 💳 Payment Integration

#### **Stripe Features**
- Secure payment processing with Stripe Elements
- Webhook handling for payment confirmation
- Support for multiple currencies
- Automatic commission calculation and distribution
- Payment intent management and error handling

#### **Order Flow**
1. User selects video and proceeds to purchase
2. Stripe payment method is created
3. Order record is created with commission calculations
4. Payment is processed and confirmed
5. Creator wallet is credited with earnings
6. Video access is granted to purchaser

### 📊 Creator Dashboard Features

- **Wallet Dashboard**: Balance overview, transaction history, payout requests
- **Video Analytics**: Views, purchases, conversion rates, revenue tracking
- **Pricing Management**: Dynamic pricing rules and threshold monitoring
- **Payout System**: Request and track payout status
- **Performance Metrics**: Monthly earnings, video performance, audience insights

### 🎛️ Admin Management

#### **Platform Settings**
- Commission rate configuration
- Video pricing caps (min/max)
- Videos sold threshold for custom pricing
- Stripe API key management
- Currency and webhook configuration

#### **Payout Management**
- Review and approve creator payout requests
- Process payouts via Stripe or manual methods
- Track payout status and history
- Manage rejected payout requests

#### **Platform Analytics**
- Total videos and creators
- Sales volume and revenue
- Commission earnings
- Creator performance metrics

### 🛠️ Installation & Setup

#### **Database Setup**
```bash
# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed --class=AdminSettingsSeeder
php artisan db:seed --class=WalletSeeder
```

#### **Configuration**
1. Set Stripe API keys in `.env`:
   ```
   STRIPE_KEY=your_publishable_key
   STRIPE_SECRET=your_secret_key
   STRIPE_WEBHOOK_SECRET=your_webhook_secret
   ```

2. Configure admin settings via dashboard or seeder

3. Set up Stripe webhook endpoint: `/stripe/webhook`

#### **Routes**
- **Creator Routes**: `/creator/*` (wallet, videos, analytics)
- **Payment Routes**: `/videos/{video}/buy`, `/payment/confirm`
- **Admin Routes**: `/admin/video-settings` (platform configuration)

### 🔄 Business Logic Flow

#### **Video Upload Process**
1. Creator uploads video with metadata
2. System validates pricing against creator rules
3. Video is published and available for purchase
4. Commission rate is displayed to creator

#### **Video Purchase Process**
1. Viewer selects video and proceeds to purchase
2. Stripe payment is processed
3. Order is created with commission calculations
4. Creator wallet is credited with earnings
5. Video access is granted to purchaser
6. Creator's sales count is incremented

#### **Payout Process**
1. Creator requests payout from wallet
2. Amount is reserved in pending balance
3. Admin reviews and approves payout
4. Payout is processed via Stripe or manual transfer
5. Wallet balance is updated and transaction logged

### 📈 Performance & Scalability

- **Caching**: Video access checks and pricing rules cached
- **Database Indexing**: Optimized queries for wallet and transaction operations
- **Lazy Loading**: Efficient relationship loading for dashboard views
- **Background Jobs**: Payment processing and webhook handling
- **Rate Limiting**: API endpoints protected against abuse

### 🧪 Testing

The system includes comprehensive testing:
- Unit tests for business logic
- Integration tests for payment flow
- Feature tests for user workflows
- Database seeding for development testing

### 🔮 Future Enhancements

- **Subscription Models**: Monthly/yearly creator subscriptions
- **Advanced Analytics**: Detailed viewer behavior tracking
- **Multi-Currency Support**: Localized pricing and payments
- **Mobile App Integration**: Native mobile video viewing
- **Content Moderation**: AI-powered content filtering
- **Affiliate System**: Referral-based commission sharing

### 📚 API Documentation

The platform provides RESTful APIs for:
- Video management and access
- Wallet operations and balance queries
- Payment processing and order management
- Creator analytics and performance data
- Admin configuration and platform management

### 🤝 Support & Maintenance

- **Regular Updates**: Security patches and feature updates
- **Monitoring**: Payment success rates and system performance
- **Backup**: Automated database and file backups
- **Documentation**: Comprehensive API and user documentation
- **Community**: Developer support and community forums

---

*This video platform implementation provides a robust, scalable solution for content creators to monetize their videos while maintaining platform security and user experience quality.*
