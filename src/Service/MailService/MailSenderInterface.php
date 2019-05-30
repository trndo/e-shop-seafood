<?php


namespace App\Service\MailService;

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
}