<?php

namespace App\Service\SmsSenderService;

use GuzzleHttp\Client;

class SmsSender implements SmsSenderInterface
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSms(string $message, string $number): void
    {
        $client = new Client();
        $login = getenv('SMS_CLIENT_LOGIN');
        $password =  getenv('SMS_CLIENT_PASSWORD');

        $client->request(
            'GET',
            'https://smsc.ua/sys/send.php?login='.$login.'&psw='.$password.'&phones='.$number.'&mes='.$message.'&tinyurl=1'
        );
    }
}