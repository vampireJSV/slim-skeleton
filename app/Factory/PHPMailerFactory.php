<?php namespace App\Factory;

use Dtkahl\SimpleConfig\Config;

class PHPMailerFactory
{

    public function __invoke(Config $config)
    {
        $mail = new \PHPMailer();

        $mail->SMTPDebug = $config->get("mail.smtp_debug");
        if ($config->get("mail.smtp")) {
            $mail->isSMTP();
            $mail->Host = $config->get("mail.smtp_host");
            if ($config->get("mail.smtp_auth")) {
                $mail->SMTPAuth   = true;
                $mail->Username   = $config->get("mail.smtp_user");
                $mail->Password   = $config->get("mail.smtp_password");
                $mail->SMTPSecure = $config->get("mail.smtp_secure");
                $mail->Port       = $config->get("mail.smtp_port");
            }
        }

        $mail->setFrom($config->get("mail.from.email"), $config->get("mail.from.name"));

        return $mail;
    }

}