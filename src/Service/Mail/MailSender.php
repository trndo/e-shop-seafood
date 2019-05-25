<?php


namespace App\Service\Mail;


use App\Entity\User;
use Twig\Environment;

class MailSender implements MailSenderInterface
{
    public const TRANSPORT = 'Lipinskie Raki';

    public const SELF_EMAIL = 'trndogv@gmail.com';

    public const CONTENT_TYPE = 'text/html';
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $environment;

    public function __construct(\Swift_Mailer $mailer, Environment $environment)
    {

        $this->mailer = $mailer;
        $this->environment = $environment;
    }

    public function sendMessage(User $user)
    {
        $message = (new \Swift_Message(self::TRANSPORT))
            ->setFrom(self::SELF_EMAIL)
            ->setTo(self::SELF_EMAIL)
            ->setBody(
                $this->environment->render(
                    'mail/registration_mail.html.twig'
                ),
                self::CONTENT_TYPE
            );

        $this->mailer->send($message);
    }

}