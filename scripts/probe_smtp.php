<?php
require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$configs = [
    ['host' => 'vkitnet.info', 'port' => 465, 'enc' => 'smtps'],
    ['host' => 'mail.vkitnet.info', 'port' => 465, 'enc' => 'smtps'],
    ['host' => 'mail.vkitnet.info', 'port' => 587, 'enc' => 'tls'],
    ['host' => 'vkitnet.info', 'port' => 587, 'enc' => 'tls'],
    ['host' => 'smtp.vkitnet.info', 'port' => 465, 'enc' => 'smtps'],
];

foreach ($configs as $c) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $c['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'] ?? '';
        $mail->Password = $_ENV['SMTP_PASSWORD'] ?? '';
        $mail->Port = $c['port'];
        $mail->SMTPSecure = $c['enc'] === 'smtps' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
        $mail->Timeout = 12;
        if ($mail->smtpConnect()) {
            echo "{$c['host']}:{$c['port']} {$c['enc']} CONNECT OK\n";
            $mail->smtpClose();
        } else {
            echo "{$c['host']}:{$c['port']} {$c['enc']} FAIL: {$mail->ErrorInfo}\n";
        }
    } catch (Exception $e) {
        echo "{$c['host']}:{$c['port']} {$c['enc']} EX: {$e->getMessage()}\n";
    }
}
