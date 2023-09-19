<?php

namespace App\Services\FileUpload;

use LaravelEasyRepository\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FileUploadServiceImplement extends Service implements FileUploadService
{

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL');
    }

    public function uploadLocal($file, $path)
    {
        // upload to storage
        $randomName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $uploaded = $file->storeAs('public/' . $path, $randomName);

        if ($uploaded) {
            return $randomName;
        } else {
            return false;
        }
    }

    public function uploadToAPI($file, $destination = 'tugas')
    {
        $client = new Client();
        $response = $client->request('POST', 'http://localhost:5000/upload', [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ],
                [
                    'name'     => 'destination',
                    'contents' => $destination
                ]
            ]
        ]);

        $response = json_decode($response->getBody()->getContents(), true);
        return $response;
    }

    public function ckeditorUpload($file, $request)
    {
        $path = $this->uploadLocal($file, 'ckeditor');
        $url = asset('storage/ckeditor/' . $path);
        $msg = 'File uploaded successfully';

        $response = '<script>window.parent.CKEDITOR.tools.callFunction(' . $request->CKEditorFuncNum . ', "' . $url . '", "' . $msg . '")</script>';
        @header('Content-type: text/html; charset=utf-8');
        echo $response;
    }

    public function download($filename, $type = 'tugas')
    {
        $client = new Client();
        try {
            $response = $client->request('POST', 'http://127.0.0.1:5000/download', [
                'json' => [
                    'file_name' => $filename,
                    'type' => $type
                ]
            ]);

            return response($response->getBody()->getContents())
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
