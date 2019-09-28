<?php


namespace App\Traits;


use App\Entity\OrderInfo;
use App\Entity\User;
use App\Service\MailService\MailSenderInterface;

trait OrderMailTrait
{
    /**
     * @var MailSenderInterface
     */
    private $mailSender;

    /**
     * OrderMailTrait constructor.
     * @param MailSenderInterface $mailSender
     */
    public function __construct(MailSenderInterface $mailSender)
    {
        $this->mailSender = $mailSender;
    }

    public function sendEmailAboutOrder(User $user, OrderInfo $info): void
    {
        $this->mailSender->sendAboutMakingOrder($user,$info);
    }

    public function sendEmailAboutOrderStatus(User $user, OrderInfo $info): void
    {
        $this->mailSender->sendAboutChangingStatus($user, $info);
    }
}