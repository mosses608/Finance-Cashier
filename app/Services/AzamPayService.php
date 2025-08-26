<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class AzamPayService
{
    private $appName;
    private $clientId;
    private $clientSecret;
    private $tokenUrl;
    private $ussdUrl;

    public function __construct()
    {
        $this->appName     = config('services.azampay.app_name');
        $this->clientId    = config('services.azampay.client_id');
        $this->clientSecret= config('services.azampay.client_secret');
        $this->tokenUrl    = config('services.azampay.token_url');
        $this->ussdUrl     = config('services.azampay.ussd_url');
    }

    /**
     * Get Access Token from AzamPay
     */
    public function getAccessToken()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->tokenUrl, [
            'appName'      => $this->appName,
            'clientId'     => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['data']['accessToken'])) {
                return $data['data']['accessToken'];
            }
        }

        throw new Exception('Unable to fetch access token: ' . $response->body());
    }

    /**
     * Send USSD Push Payment Request
     */
    public function ussdPush($msisdn, $amount, $reference, $description)
    {
        $token = $this->getAccessToken();

        $payload = [
            "accountNumber" => (string)$msisdn,
            "amount"        => (int)$amount,
            "externalId"    => (string)$reference,
            "narration"     => $description,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])->post($this->ussdUrl, $payload);

        if ($response->successful()) {
            return $response->json();
        }

        throw new Exception('USSD Push failed: ' . $response->body());
    }
}
