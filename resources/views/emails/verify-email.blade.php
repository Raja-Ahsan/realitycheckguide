<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verify Your Email - Reality Check Guide</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, sans-serif !important;}
    </style>
    <![endif]-->
    <style>
        /* Reset styles */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }
        
        /* Main styles */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .email-wrapper {
            width: 100%;
            background-color: #f5f7fa;
            padding: 40px 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        /* Header with gradient */
        .email-header {
            background: linear-gradient(135deg, #1a355e 0%, #2d4a7c 50%, #1a355e 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }
        
        .logo-container {
            position: relative;
            z-index: 1;
            margin-bottom: 20px;
        }
        
        .logo-container img {
            max-width: 180px;
            height: auto;
            display: block;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .email-header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            line-height: 1.3;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .email-header .subtitle {
            color: #e8f0f8;
            font-size: 16px;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }
        
        /* Body content */
        .email-body {
            padding: 45px 40px;
            color: #333333;
        }
        
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #1a355e;
            margin: 0 0 20px 0;
            line-height: 1.4;
        }
        
        .content-text {
            font-size: 16px;
            line-height: 1.7;
            color: #555555;
            margin: 0 0 20px 0;
        }
        
        .content-text strong {
            color: #1a355e;
            font-weight: 600;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #fff5e6 0%, #ffe8cc 100%);
            border-left: 4px solid #ffc430;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }
        
        .highlight-box p {
            margin: 0;
            font-size: 15px;
            line-height: 1.6;
            color: #5a4a2a;
        }
        
        .features-list {
            margin: 25px 0;
            padding: 0;
            list-style: none;
        }
        
        .features-list li {
            padding: 12px 0 12px 35px;
            font-size: 15px;
            line-height: 1.6;
            color: #555555;
            position: relative;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .features-list li:last-child {
            border-bottom: none;
        }
        
        .features-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #ffc430;
            font-weight: bold;
            font-size: 18px;
            width: 25px;
            height: 25px;
            background-color: #fff5e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* CTA Button - Email Compatible */
        .cta-container {
            text-align: center;
            margin: 35px 0;
        }
        
        .cta-button {
            display: inline-block;
            background-color: #ffc430;
            background: #ffc430; /* Fallback for older clients */
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 45px;
            border-radius: 8px;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: 2px solid #ffc430;
            mso-hide: all; /* Hide from Outlook */
        }
        
        /* Table-based button for better email client support */
        .button-table {
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .button-cell {
            background-color: #ffc430;
            border-radius: 8px;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
        }
        
        .button-link {
            display: block;
            padding: 16px 45px;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            line-height: 1.5;
        }
        
        .security-note {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
            text-align: center;
        }
        
        .security-note p {
            font-size: 13px;
            color: #888888;
            line-height: 1.6;
            margin: 8px 0;
        }
        
        .security-note .warning {
            color: #e74c3c;
            font-weight: 600;
        }
        
        /* Footer */
        .email-footer {
            background: linear-gradient(135deg, #1a355e 0%, #2d4a7c 100%);
            color: #ffffff;
            padding: 35px 40px;
            text-align: center;
        }
        
        .footer-content {
            font-size: 14px;
            line-height: 1.8;
            color: #e8f0f8;
        }
        
        .footer-content strong {
            color: #ffffff;
            font-size: 16px;
            display: block;
            margin-bottom: 10px;
        }
        
        .social-links {
            margin: 20px 0 15px 0;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
        }
        
        .footer-copyright {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 12px;
            color: #b8c8d8;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 20px 0;
            }
            
            .email-container {
                border-radius: 0;
                margin: 0 10px;
            }
            
            .email-header {
                padding: 30px 20px;
            }
            
            .email-header h1 {
                font-size: 24px;
            }
            
            .email-body {
                padding: 30px 25px;
            }
            
            .cta-button {
                padding: 14px 35px !important;
                font-size: 15px !important;
                display: inline-block !important;
            }
            
            .button-link {
                padding: 14px 35px !important;
                font-size: 15px !important;
            }
            
            .email-footer {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <div class="logo-container">
                    <img src="{{ asset('assets/website/img/logo.png') }}" alt="Reality Check Guide Logo">
                </div>
                <h1>Welcome to Reality Check Guide!</h1>
                <p class="subtitle">Your journey to real career insights starts here</p>
            </div>
            
            <!-- Body -->
            <div class="email-body">
                <p class="greeting">Hello there! 👋</p>
                
                <p class="content-text">
                    {{ $details['title'] ?? "We're excited to have you join our community!" }}
                </p>
                
                <p class="content-text">
                    {{ $details['body'] ?? "Reality Check Guide is a video-based platform created to help people explore careers through honest, real-life experiences. Whether you're a student, a career changer, or simply curious, our mission is to show you what jobs are really like — before you commit time, money, or energy into the wrong path." }}
                </p>
                
                <div class="highlight-box">
                    <p><strong>🎯 What makes us different?</strong><br>
                    Instead of polished resumes or job descriptions, you'll find authentic stories from people who live the job every day. We believe guidance should come from experience, not just theory.</p>
                </div>
                
                <p class="content-text">
                    <strong>To complete your registration and start exploring real career stories, please verify your email address by clicking the button below:</strong>
                </p>
                
                <div class="cta-container">
                    <!--[if mso]>
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="button-table">
                        <tr>
                            <td class="button-cell" style="background-color: #ffc430; border-radius: 8px;">
                                <a href="{{ route('email-verification', $details['verify_token']) }}" class="button-link" style="color: #ffffff; text-decoration: none; padding: 16px 45px; display: inline-block; font-size: 17px; font-weight: 700; text-transform: uppercase;">
                                    Verify My Email Address
                                </a>
                            </td>
                        </tr>
                    </table>
                    <![endif]-->
                    <!--[if !mso]><!-->
                    <a href="{{ route('email-verification', $details['verify_token']) }}" class="cta-button" style="display: inline-block; background-color: #ffc430; color: #ffffff !important; text-decoration: none; padding: 16px 45px; border-radius: 8px; font-size: 17px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; border: 2px solid #ffc430;">
                        Verify My Email Address
                    </a>
                    <!--<![endif]-->
                </div>
                
                <ul class="features-list">
                    <li>Explore authentic career stories from real professionals</li>
                    <li>Make informed career decisions based on real experiences</li>
                    <li>Connect with professionals who share their journey</li>
                    <li>Access valuable insights before committing to a career path</li>
                </ul>
                
                <div class="security-note">
                    <p><strong class="warning">⚠️ Security Notice:</strong></p>
                    <p>If you did not create an account with Reality Check Guide, please ignore this email. No further action is required, and your email address will not be used.</p>
                    <p style="margin-top: 15px; font-size: 12px; color: #aaaaaa;">
                        This verification link will expire in 24 hours for security reasons.
                    </p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="email-footer">
                <div class="footer-content">
                    <strong>Reality Check Guide</strong>
                    <p>Real People. Real Careers. Real Insight.</p>
                    <p style="margin-top: 15px;">
                        Helping you make smarter career decisions through authentic experiences.
                    </p>
                </div>
                
                <div class="footer-copyright">
                    <p>&copy; {{ date('Y') }} Reality Check Guide. All rights reserved.</p>
                    <p style="margin-top: 8px;">
                        This email was sent to verify your account registration.<br>
                        If you have any questions, please contact our support team.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
