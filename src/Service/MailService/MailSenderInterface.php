<?php


namespace App\Service\MailService;

use App\Entity\OrderInfo;
use App\Entity\User;

interface MailSenderInterface
{
    /**
     * @param User $user
     * Send message to user
     */
    public function sendMessage(User $user): void ;

    /**
     * @param User $admin
     * Send message to admin
     */
    public function sendAdminMessage(User $admin): void ;

    /**
     * Send message for resetting password
     * @param User $user
     */
    public function sendResetUserPassword(User $user): void ;

    /**
     * @param User $user
     */
    public function sendAboutResettingPassword(User $user): void ;

    /**
     * @param User $user
     * @param string $temporaryPass
     */
    public function sendAboutUnknownRegistration(User $user, string $temporaryPass): void ;

    /**
     * @param User $user
     * @param OrderInfo $info
     */
    public function sendAboutMakingOrder(User $user, OrderInfo $info): void;

    /**
     * @param User $user
     * @param OrderInfo $info
     */
    public function sendAboutChangingStatus(User $user, OrderInfo $info): void;
}