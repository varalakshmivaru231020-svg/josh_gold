<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $url;
    protected $version;
    protected $token;
    protected $phoneId;

    public function __construct()
    {
        $this->url = "https://crmapp.botsyncwa.com/api/meta";
        $this->version = "v19.0";
        $this->token = "6diIJ78UXBWR3M7acnAB1xSFvfgzC5rc1Zs9eplTKA72w1lvDzX5NTCDhGREFTSAg9PviyJl6lbPizoREFTSAGnwhsY3cpBhRsfLiG8jEZTZJREFTSAdi69EywwzWzbJkNktAYqoyc";
        $this->phoneId = "829285263608496";
    }

    public function sendTemplate(string $to, string $templateName, array $bodyVariables = [], array $buttonVariables = [])
    {
        // Build body component
        $components = [];

        if (!empty($bodyVariables)) {
            $bodyComponent = [
                "type" => "body",
                "parameters" => []
            ];

            foreach ($bodyVariables as $value) {
                $bodyComponent["parameters"][] = [
                    "type" => "text",
                    "text" => $value
                ];
            }

            $components[] = $bodyComponent;
        }

        // Build button components
        if (!empty($buttonVariables)) {
            foreach ($buttonVariables as $index => $value) {
                $components[] = [
                    "type" => "button",
                    "sub_type" => "url",     // adjust if needed
                    "index" => (string)$index,
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $value
                        ]
                    ]
                ];
            }
        }

        // Request payload
        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $templateName,
                "language" => [
                    "code" => "en",
                    "policy" => "deterministic"
                ],
                "components" => $components
            ]
        ];

        $endpoint = "{$this->url}/{$this->version}/{$this->phoneId}/messages";

        // Log request
        Log::channel('daily')->info("WHATSAPP API REQUEST", [
            "endpoint" => $endpoint,
            "payload" => $payload
        ]);

        try {
            $response = Http::withToken($this->token)->post($endpoint, $payload);

            Log::channel('daily')->info("WHATSAPP API RESPONSE", [
                "status" => $response->status(),
                "body"   => $response->json()
            ]);

            return $response->json();

        } catch (\Exception $e) {
            Log::channel('daily')->error("WHATSAPP API EXCEPTION", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
            return [
                "success" => false,
                "error" => $e->getMessage()
            ];
        }
    }

}
