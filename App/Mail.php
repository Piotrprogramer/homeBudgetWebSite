<?php

namespace App;

use App\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Mail
 *
 * PHP version 7.0
 */
class Mail
{

    /**
     * Send a message
     *
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */
    public static function send($to, $subject, $text, $html='')
    {
                                   $mail = new PHPMailer(true);
                                   $mail->isSMTP();
                                   $mail->Host = "smtp.gmail.com";
                                   $mail->SMTPAuth = true;

                                   $mail->Username = Config::MAIL_NAME;
                                   $mail->Password = Config::PHPmailer_API_KEY;
                                   
                                   $mail->SMTPSecure = 'tls';
                                   $mail->Port = 587;
                                   
                                   $mail->setFrom('piotr.wasilewski.programista@gmail.com');
                                   $mail->addAddress($to);
                                   $mail->Subject = $subject;
                                   $mail->Body = $text;
                                   $mail->send();
    }
}
