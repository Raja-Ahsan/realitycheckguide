<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1a355e 0%, #ffc430 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: bold;">New Contact Form Submission</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            <p style="margin: 0 0 20px 0; color: #333333; font-size: 16px; line-height: 1.6;">
                                You have received a new contact form submission from your website.
                            </p>
                            
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 12px; background-color: #f8f9fa; border: 1px solid #e0e0e0; font-weight: bold; color: #1a355e; width: 150px;">
                                        Name:
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #e0e0e0; color: #333333;">
                                        {{ $details['name'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px; background-color: #f8f9fa; border: 1px solid #e0e0e0; font-weight: bold; color: #1a355e;">
                                        Email:
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #e0e0e0; color: #333333;">
                                        <a href="mailto:{{ $details['email'] }}" style="color: #1a355e; text-decoration: none;">{{ $details['email'] }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px; background-color: #f8f9fa; border: 1px solid #e0e0e0; font-weight: bold; color: #1a355e;">
                                        Phone:
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #e0e0e0; color: #333333;">
                                        <a href="tel:{{ $details['phone'] }}" style="color: #1a355e; text-decoration: none;">{{ $details['phone'] }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px; background-color: #f8f9fa; border: 1px solid #e0e0e0; font-weight: bold; color: #1a355e; vertical-align: top;">
                                        Message:
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #e0e0e0; color: #333333; line-height: 1.6;">
                                        {{ $details['message'] }}
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 20px 0 0 0; color: #666666; font-size: 14px; line-height: 1.6;">
                                <strong>Note:</strong> This is an automated email from your website contact form. Please respond directly to the user's email address if you need to reply.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 8px 8px; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0; color: #666666; font-size: 12px;">
                                © {{ date('Y') }} Reality Check Guide. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

