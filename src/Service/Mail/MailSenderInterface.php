<?php


namespace App\Service\Mail;


use App\Entity\User;
use Twig\Environment;

interface MailSenderInterface
{
    /**
     * MailSenderInterface constructor.
     * @param \Swift_Mailer $mailer - for sending emails
     * @param Environment $environment - for using twig templates
     */
    public function __construct(\Swift_Mailer $mailer, Environment $environment);


    /**
     * @param User $user
     * Send message to user
     */
    public function sendMessage(User $user);
}