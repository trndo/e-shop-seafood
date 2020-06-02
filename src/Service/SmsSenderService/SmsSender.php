<?php


namespace App\Service\SmsSenderService;


use GuzzleHttp\Client;

class SmsSender implements SmsSenderInterface
{
    private $client;
    private $login;
    private $password;

    public function __construct()
    {
        $this->client = new Client();
        $this->login = getenv('SMS_CLIENT_LOGIN');
        $this->password =  getenv('SMS_CLIENT_PASSWORD');
    }

    public function sendSms(string $message, string $number): void
    {
        $this->client->request('GET',
            'https://smsc.ua/sys/send.php?login='.$this->login.'&psw='.$this->password.'&phones='.$number.'&mes='.$message, [
                'verify' => false
            ]);
    }
}