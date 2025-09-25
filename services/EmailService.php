<?php
// Load environment variables from .env file
if (file_exists(__DIR__ . '/../.env')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    $dotenv->required([
        'SENDGRID_HOST',
        'SENDGRID_USERNAME',
        'SENDGRID_API_KEY',
        'SENDGRID_EMAIL'
    ])->notEmpty();
} else {
    die('.env file not found in ' . __DIR__ . '/..');
}

// Check if autoloader exists, if not use alternative path
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} else {
    // Fallback: include PHPMailer files directly if composer not available 
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->setupSMTP();
    }

    private function setupSMTP()
    {
        try {
            // SendGrid SMTP settings
            $this->mail->isSMTP();
            $this->mail->Host       = $_ENV['SENDGRID_HOST'];
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $_ENV['SENDGRID_USERNAME']; // Always "apikey" for SendGrid
            $this->mail->Password   = $_ENV['SENDGRID_API_KEY']; // Replace with your SendGrid API key
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = 587;

            // Sender email - Use your verified email in SendGrid
            $this->mail->setFrom($_ENV['SENDGRID_EMAIL']);
        } catch (Exception $e) {
            // error_log("Email setup failed: " . $e->getMessage());
            echo "Email setup failed: " . $e->getMessage();
        }
    }

    public function sendOTP($recipientEmail, $recipientName, $otpCode)
    {
        try {
            // Recipients
            $this->mail->addAddress($recipientEmail, $recipientName);

            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Your Verification Code - Saltel Rwanda';

            $htmlBody = $this->getOTPEmailTemplate($recipientName, $otpCode);
            $this->mail->Body = $htmlBody;

            // Alternative plain text body
            $this->mail->AltBody = "Hello $recipientName,\n\nYour verification code is: $otpCode\n\nThis code will expire in 10 minutes.\n\nBest regards,\nSaltel Rwanda Team";

            $result = $this->mail->send();

            // Clear addresses for next use
            $this->mail->clearAddresses();

            return $result;
        } catch (Exception $e) {
            // error_log("Email sending failed: " . $this->mail->ErrorInfo);
            echo "Email sending failed: " . $this->mail->ErrorInfo;
            return false;
        }
    }

    private function getOTPEmailTemplate($name, $otpCode)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Verification Code</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { text-align: center; background: #667eea; color: white; padding: 20px; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .otp-box { background: white; border: 2px solid #667eea; border-radius: 10px; padding: 20px; text-align: center; margin: 20px 0; }
                .otp-code { font-size: 32px; font-weight: bold; color: #667eea; letter-spacing: 5px; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Saltel Rwanda</h1>
                    <h2>Email Verification</h2>
                </div>
                <div class='content'>
                    <h3>Hello $name,</h3>
                    <p>Thank you for registering with Saltel Rwanda! To complete your registration, please verify your email address using the code below:</p>
                    
                    <div class='otp-box'>
                        <p>Your verification code is:</p>
                        <div class='otp-code'>$otpCode</div>
                    </div>
                    
                    <p><strong>Important:</strong> This code will expire in 10 minutes for security reasons.</p>
                    <p>If you didn't request this verification, please ignore this email.</p>
                    
                    <div class='footer'>
                        <p>Best regards,<br>The Saltel Rwanda Team</p>
                        <p>This is an automated message, please do not reply to this email.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }
}
