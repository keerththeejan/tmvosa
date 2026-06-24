<?php
require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host = $_ENV['SMTP_HOST'] ?? 'vkitnet.info';
$port = (int) ($_ENV['SMTP_PORT'] ?? 465);
$user = $_ENV['SMTP_USERNAME'] ?? '';
$pass = $_ENV['SMTP_PASSWORD'] ?? '';

echo "Testing SMTP connection to {$host}:{$port}\n";
echo "User: {$user}\n";
echo "Pass length: " . strlen($pass) . "\n";

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = $host;
    $mail->SMTPAuth = true;
    $mail->Username = $user;
    $mail->Password = $pass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $port;
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];
    $mail->Timeout = 20;
    $mail->setFrom($user, 'OSA Test');
    $mail->addAddress($user);
    $mail->Subject = 'Direct SMTP Test';
    $mail->Body = 'Test';
    $mail->send();
    echo "SUCCESS\n";
} catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    echo "PHPMailer: " . $mail->ErrorInfo . "\n";
}
