<?php

namespace App\Services\Plagiarism;

use LaravelEasyRepository\BaseService;

interface PlagiarismService extends BaseService
{
    public function singleCheckPlagiarism($file, array $files);
    public function multipleCheckPlagiarism(array $files);
}
