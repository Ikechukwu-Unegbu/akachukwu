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
    <h1 style="font-size: 20px; font-weight: 600; text-align: center; color: #0018A8; margin-bottom: 16px;">
        Welcome to Vastel
    </h1>
    
    <p style="font-size: 14px; line-height: 1.6; margin-bottom: 16px;">
        Hi {{ $user->name }}!!
    </p>

    <p style="font-size: 14px; line-height: 1.6; margin-bottom: 16px;">
        Your Vastel Account is ready. Thank you for using Vastel! We’re thrilled to have you on board. With our app, you can easily buy airtime, subscribe to internet services, cable TV, and educational services, send money — all at your fingertips!
    </p>

    <p style="font-size: 14px; font-weight: 600; color: #0018A8; text-align: center;">
        Your OTP is: <span style="font-size: 16px; font-weight: bold;">{{ $otp }}</span>
    </p>

    <p style="font-size: 14px; line-height: 1.6; margin-bottom: 16px;">
        To get started, download the app and explore the various services we offer. If you have any questions, our support team is just a tap away.
    </p>

    <p style="font-size: 14px; font-weight: 600; margin-bottom: 16px;">
        Once again, thank you for using our application!
    </p>

    <p style="font-size: 14px;">Best Regards,<br>The Vastel Team</p>
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
