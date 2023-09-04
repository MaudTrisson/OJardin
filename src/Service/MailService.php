<?php

namespace App\Service;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
    }

    public function sendEmail($to, $subject, $body)
    {
        try {
            //Server settings
            $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;                //Enable verbose debug output
            $this->mailer->isSMTP();                                      //Send using SMTP
            $this->mailer->Host       = 'smtp.gmail.com';                 //Set the SMTP server to send through
            $this->mailer->SMTPAuth   = true;                             //Enable SMTP authentication
            $this->mailer->Username   = 'maud.trisson@gmail.com';         //SMTP username
            $this->mailer->Password   = 'sgjyyklvwwcbqqbx';               //SMTP password
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   //Enable implicit TLS encryption
            $this->mailer->Port       = 587;                              //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $this->mailer->setFrom('support@ojardin.com', 'O\'jardin');     //sender's mail ans name
            $this->mailer->addAddress($to);                                 //Add a recipient
            $this->mailer->addReplyTo('support@ojardin.com', 'O\'jardin');

            //Content
            $this->mailer->isHTML(true); //Set email format to HTML
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;

            $this->mailer->send();

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}