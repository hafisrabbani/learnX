<?php

namespace App\Services\FileUpload;

use LaravelEasyRepository\BaseService;

interface FileUploadService extends BaseService
{
    public function uploadLocal($file, $path);
    public function uploadToAPI($file, $destination = 'tugas');
    public function ckeditorUpload($file, $request);
    public function download($filename, $type = 'tugas');
    public function extractText($filename);
}
