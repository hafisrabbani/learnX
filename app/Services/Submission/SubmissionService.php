<?php

namespace App\Services\Submission;

use LaravelEasyRepository\BaseService;

interface SubmissionService extends BaseService
{

    public function getByTugas($id_tugas);
}
