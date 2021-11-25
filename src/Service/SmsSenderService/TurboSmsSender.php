<?php

namespace App\Service\SmsSenderService;

use GuzzleHttp\Client;

class TurboSmsSender
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function sendSms(string $message, string $number): void
    {
        $client = new Client();

        $client->request(
            'POST',
            'https://smsc.ua/sys/send.php?login='.'&psw='.'&phones='.'&mes=',
            [
                'body' => \json_encode([
                    "recipients" => [
                        "380678998668",
                        "380503288668",
                        "380638998668"
                    ],
                    "viber" => [
                        "sender" => "Lipinskie Raki Test Viber",
                        "text" => "Vitalia приветствует Вас"
                    ],
                    "sms" => [
                        "sender" => "Lipinskie Raki Test SMS",
                        "text" => "Vitalia приветствует Вас"
                    ]
                ], JSON_THROW_ON_ERROR)
            ]);
    }
}