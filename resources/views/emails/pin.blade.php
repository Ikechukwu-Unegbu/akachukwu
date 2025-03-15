<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
</head>
<body style="background-color: #e5e7eb; margin: 0; padding: 20px; font-family: Arial, sans-serif;">
    <div style="max-width: 400px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden;">
        
        <!-- Header Section -->
        <div style="text-align: center; padding: 24px;">
            <img src="https://vastel-uploads.fra1.cdn.digitaloceanspaces.com/production/vastel-logo.svg" alt="Vastel Logo" width="80" style="display: block; margin: 0 auto;">
        

        </div>
        
        <!-- Main Content -->
        <div style="padding: 24px; color: #1f2937;">
            
        
        <h1 style="font-size: 20px; color: #0018A8; text-align: center; margin-bottom: 16px;">
            Hello {{ $user->name }}!
        </h1>

        <!-- Message Content -->
        <p style="font-size: 14px; line-height: 1.6; color: #333333; margin-bottom: 16px;">
            You requested to 
            <strong>{{ $type === 'pin_reset' ? 'reset your PIN' : 'set up your PIN' }}</strong> 
            for your Vastel account.
        </p>

        <p style="font-size: 14px; font-weight: bold; color: #0018A8; text-align: center; margin-bottom: 16px;">
            Your One-Time Password (OTP) is:
        </p>

        <p style="font-size: 18px; font-weight: bold; color: #ff0000; text-align: center; background-color: #f8f9fa; padding: 10px; border-radius: 4px;">
            {{ $otp }}
        </p>

        <p style="font-size: 14px; line-height: 1.6; color: #333333; margin-bottom: 16px;">
            This OTP is valid for <strong>10 minutes</strong>. Please do not share it with anyone for your account security.
        </p>

        <p style="font-size: 14px; line-height: 1.6; color: #333333; margin-bottom: 16px;">
            If you did not request this action, please ignore this message or contact our support team.
        </p>
        </div>

        
        <!-- Footer Section -->
        <div style="background-color: #0018A8; color: white; text-align: center; padding: 16px; font-size: 12px;">
            <p style="margin: 0;">&copy; 2024 Vastel.io</p>
            <div style="margin-top: 8px;">
                <!-- <a href="#" style="opacity: 0.8; margin: 0 8px; display: inline-block;">
                    <img src="https://via.placeholder.com/16" alt="Twitter" width="16">
                </a>
                <a href="#" style="opacity: 0.8; margin: 0 8px; display: inline-block;">
                    <img src="https://via.placeholder.com/16" alt="Facebook" width="16">
                </a>
                <a href="#" style="opacity: 0.8; margin: 0 8px; display: inline-block;">
                    <img src="https://via.placeholder.com/16" alt="Instagram" width="16">
                </a>
                <a href="#" style="opacity: 0.8; margin: 0 8px; display: inline-block;">
                    <img src="https://via.placeholder.com/16" alt="YouTube" width="16">
                </a> -->
            </div>
        </div>
        
    </div>
</body>
</html>
