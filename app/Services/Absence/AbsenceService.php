<?php

namespace App\Services\Absence;

use LaravelEasyRepository\BaseService;

interface AbsenceService extends BaseService
{

    public function createDetail($data);
}
