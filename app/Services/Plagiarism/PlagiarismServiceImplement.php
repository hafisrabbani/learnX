<?php

namespace App\Services\Plagiarism;

use LaravelEasyRepository\Service;
use GuzzleHttp\Client;

class PlagiarismServiceImplement extends Service implements PlagiarismService
{

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */

    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL');
    }

    public function singleCheckPlagiarism($file, array $files)
    {
        $client = new Client();
        try {
            $request = $client->request('POST', env('INTERNAL_API_URL') . '/compare', [
                'json' => [
                    'file_name' => $file,
                    'compare_files' => $files,
                    'threshold' => 0,
                    'to_precentages' => true
                ]
            ]);

            $response = json_decode($request->getBody()->getContents(), true);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function multipleCheckPlagiarism(array $files)
    {
        $client = new Client();

        try {
            $request = $client->request('POST', 'http://127.0.0.1:5000/highest_similarity', [
                'json' => [
                    'compare_files' => $files,
                ]
            ]);

            $response = json_decode($request->getBody()->getContents(), true);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
