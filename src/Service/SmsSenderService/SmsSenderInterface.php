<?php


namespace App\Service\SmsSenderService;


interface SmsSenderInterface
{
    public function sendSms(string $message, string $number): void ;
}