<?php


namespace App\Service\MailService;


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

    public function sendMessage(User $user): void
    {
        $message = (new \Swift_Message(self::TRANSPORT))
            ->setFrom(self::SELF_EMAIL)
            ->setTo($user->getEmail())
            ->setBody(
                $this->environment->render(
                    'mail/registration_mail.html.twig',[
                        'user' => $user
                    ]
                ),
                self::CONTENT_TYPE
            );

        $this->mailer->send($message);
    }

    public function sendAdminMessage(User $admin): void
    {
        $message = (new \Swift_Message(self::TRANSPORT))
            ->setFrom(self::SELF_EMAIL)
            ->setTo($admin->getEmail())
            ->setBody(
                $this->environment->render(
                    'mail/registration_admin_mail.html.twig',[
                        'user' => $admin
                    ]
                ),
                self::CONTENT_TYPE
            );
        $this->mailer->send($message);
    }

    public function sendResetUserPassword(User $user): void
    {
        $message = (new \Swift_Message(self::TRANSPORT))
            ->setFrom(self::SELF_EMAIL)
            ->setTo($user->getEmail())
            ->setBody(
                $this->environment->render(
                    'mail/reset_password.html.twig',[
                        'user' => $user
                    ]
                ),
                self::CONTENT_TYPE
            );
        $this->mailer->send($message);
    }
}