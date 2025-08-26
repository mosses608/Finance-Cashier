<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AzamPayService
{
    private $baseUrl;
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->baseUrl = config('azampay.base_url');
        $this->clientId = config('azampay.client_id');
        $this->clientSecret = config('azampay.client_secret');
    }

    // 1. Get Access Token
    public function getAccessToken()
    {
        $response = Http::post(env('AZAMPAY_TOKEN_URL'), [
            'appName'      => env('AZAMPAY_APP_NAME'),
            'clientId'     => env('AZAMPAY_CLIENT_ID'),
            'clientSecret' => env('AZAMPAY_CLIENT_SECRET'),
        ]);

        if ($response->successful() && isset($response->json()['data']['accessToken'])) {
            return $response->json()['data']['accessToken'];
        }

        throw new \Exception('Unable to fetch access token: ' . $response->body());
    }


    // 2. Send USSD Push
    public function ussdPush($msisdn, $amount, $reference, $description)
    {
        $token = $this->getAccessToken();

        $payload = [
            "accountNumber" => $msisdn,
            "amount"        => (int)$amount,
            "externalId"    => (string)$reference,
            "narration"     => $description,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])->post(config('services.azampay.ussd_url'), $payload);

        return $response->json();
    }
}
