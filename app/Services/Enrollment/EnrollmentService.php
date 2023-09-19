<?php

namespace App\Services\Enrollment;

use LaravelEasyRepository\BaseService;

interface EnrollmentService extends BaseService
{

    public function getMatkulByMahasiswa($id_mahasiswa);
}
