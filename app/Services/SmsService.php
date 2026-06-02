<?php

namespace App\Services;

class SmsService
{
    protected string $apiUrl;
    private string $fromNumber;

    public function __construct()
    {
        $this->apiUrl = 'https://console.melipayamak.com/api/send/simple/ca25fdda0f6043f1b45d4efbf71708a1';
        $this->fromNumber = '50002710028103';
        // $this->apiKey = 'your-api-key-here'; // اگر نیاز به API Key دارید
    }

    /**
     * متد اصلی ارسال پیامک
     */
    public function sendSms(string $to, string $text): array
    {
        $data = [
            'from' => $this->fromNumber,
            'to' => $to,
            'text' => $text
        ];

        $response = $this->makeRequest($data);

        return $this->handleResponse($response);
    }

    /**
     * متد ارسال کد تایید
     */
    public function sendVerificationCode(string $phone, string $code): array
    {
        $text = "کد تایید شما: $code\n\nفیلانت";

        return $this->sendSms($phone, $text);
    }

    private function makeRequest(array $data): array
    {
        $dataString = json_encode($data);

        $ch = curl_init($this->apiUrl);

        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($dataString)
        ];

        // اگر API Key دارید و نیاز هست، این خط را فعال کنید
        // $headers[] = 'Authorization: Bearer ' . $this->apiKey;

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $dataString,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => !app()->isLocal()
        ]);

        $result = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'result' => $result,
            'error' => $error,
            'http_code' => $httpCode
        ];
    }

    private function handleResponse(array $response): array
    {
        if ($response['error']) {
            return [
                'success' => false,
                'message' => 'Curl error: ' . $response['error']
            ];
        }

        $decodedResult = json_decode($response['result'], true);

        if ($response['http_code'] !== 200) {
            return [
                'success' => false,
                'message' => 'API request failed with code: ' . $response['http_code'],
                'response' => $decodedResult
            ];
        }

        return [
            'success' => true,
            'data' => $decodedResult
        ];
    }
}
