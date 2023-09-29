<?php

namespace App\Services\Personalisasi;

use LaravelEasyRepository\Service;
use GuzzleHttp\Client;

class PersonalisasiServiceImplement extends Service implements PersonalisasiService
{

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */

    public function getAnalytic($id_analyzer, $id_user)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', env('INTERNAL_API_URL') . '/analyzer/quiz', [
                'query' => [
                    'id_analyzer' => $id_analyzer,
                    'id_user' => $id_user
                ]
            ]);
            $response = json_decode($response->getBody()->getContents(), true);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function createQuizFromModule($id_module)
    {
        $client = new Client();
        try {
            $response = $client->request('POST', env('INTERNAL_API_URL') . '/generate-quiz', [
                'json' => [
                    'id_module' => $id_module
                ]
            ]);
            $response = json_decode($response->getBody()->getContents(), true);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function personalisasi($id_user)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', env('INTERNAL_API_URL') . '/personalisasi', [
                'query' => [
                    'id_user' => $id_user
                ]
            ]);
            $response = json_decode($response->getBody()->getContents(), true);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function generateTTS($data)
    {
        $client = new Client();
        try {
            $response = $client->request('POST', 'https://api.prosa.ai/v2/speech/tts', [
                'json' => [
                    "config" => [
                        "model" => "tts-dimas-formal",
                        "wait" => false,
                        "audio_format" => "opus"
                    ],
                    "request" => [
                        "text" => $data
                    ]
                ],
                'headers' => [
                    'X-Api-Key' => env('API_KEY_PROSA')
                ],
                'verify' => false
            ]);
            $response = json_decode($response->getBody()->getContents(), true);
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
