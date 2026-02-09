@extends('layouts.website.master')
@section('title', 'Email Verified Successfully')
@section('content')

<style>
    .verification-success {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #e8f0f8 100%);
    }
    
    .success-container {
        max-width: 600px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 50px 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
        margin: 0 auto;
    }
    
    .success-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #ffc430 0%, #df9816 50%, #ffc430 100%);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    .success-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #16a085 0%, #27ae60 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        animation: scaleIn 0.5s ease-out;
    }
    
    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .success-icon::before {
        content: '✓';
        color: #ffffff;
        font-size: 50px;
        font-weight: bold;
        line-height: 1;
    }
    
    .success-icon::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        border: 3px solid #16a085;
        border-radius: 50%;
        opacity: 0.3;
        animation: ripple 2s infinite;
    }
    
    @keyframes ripple {
        0% {
            transform: scale(0.8);
            opacity: 0.3;
        }
        100% {
            transform: scale(1.2);
            opacity: 0;
        }
    }
    
    .success-title {
        font-size: 32px;
        font-weight: 700;
        color: #1a355e;
        margin-bottom: 15px;
        line-height: 1.3;
    }
    
    .success-message {
        font-size: 18px;
        color: #555555;
        line-height: 1.7;
        margin-bottom: 30px;
    }
    
    .success-details {
        background: #f8f9fa;
        border-left: 4px solid #ffc430;
        padding: 20px;
        border-radius: 8px;
        margin: 30px 0;
        text-align: left;
    }
    
    .success-details p {
        margin: 8px 0;
        color: #555555;
        font-size: 15px;
    }
    
    .success-details strong {
        color: #1a355e;
        font-weight: 600;
    }
    
    .action-buttons {
        margin-top: 40px;
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #ffc430 0%, #df9816 100%);
        color: #ffffff;
        padding: 14px 35px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        border: none;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(255, 196, 48, 0.3);
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 196, 48, 0.4);
        color: #ffffff;
        text-decoration: none;
    }
    
    .btn-secondary-custom {
        background: #ffffff;
        color: #1a355e;
        padding: 14px 35px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        border: 2px solid #1a355e;
        display: inline-block;
    }
    
    .btn-secondary-custom:hover {
        background: #1a355e;
        color: #ffffff;
        text-decoration: none;
        transform: translateY(-2px);
    }
    
    .features-preview {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }
    
    .features-preview h4 {
        color: #1a355e;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    
    .feature-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        text-align: center;
    }
    
    .feature-item i {
        font-size: 24px;
        color: #ffc430;
        margin-bottom: 8px;
    }
    
    .feature-item p {
        margin: 0;
        font-size: 13px;
        color: #555555;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .success-container {
            padding: 40px 25px;
            margin: 20px;
        }
        
        .success-title {
            font-size: 26px;
        }
        
        .success-message {
            font-size: 16px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-primary-custom,
        .btn-secondary-custom {
            width: 100%;
            text-align: center;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="verification-success">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="success-container">
                    <div class="success-icon"></div>
                    
                    <h1 class="success-title">Email Verified Successfully! 🎉</h1>
                    
                    <p class="success-message">
                        Congratulations! Your email address has been verified and your account is now active.
                    </p>
                    
                    <div class="success-details">
                        <p><strong>✓</strong> Your email address has been confirmed</p>
                        <p><strong>✓</strong> Your account is now activated</p>
                        <p><strong>✓</strong> You can now access all features</p>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="{{ route('login') }}" class="btn-primary-custom">
                            <i class="fa fa-sign-in"></i> Go to Login
                        </a>
                        <a href="{{ route('index') }}" class="btn-secondary-custom">
                            <i class="fa fa-home"></i> Back to Home
                        </a>
                    </div>
                    
                    <div class="features-preview">
                        <h4>What's Next?</h4>
                        <p style="color: #555; margin-bottom: 20px;">
                            Now that your account is verified, you can start exploring real career stories and making informed decisions about your future!
                        </p>
                        <div class="features-grid">
                            <div class="feature-item">
                                <i class="fa fa-video-camera"></i>
                                <p>Watch Career Videos</p>
                            </div>
                            <div class="feature-item">
                                <i class="fa fa-users"></i>
                                <p>Connect with Professionals</p>
                            </div>
                            <div class="feature-item">
                                <i class="fa fa-lightbulb-o"></i>
                                <p>Get Real Insights</p>
                            </div>
                            <div class="feature-item">
                                <i class="fa fa-heart"></i>
                                <p>Save Favorites</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

