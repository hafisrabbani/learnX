<?php

namespace App\Repositories\Absence;

use LaravelEasyRepository\Repository;

interface AbsenceRepository extends Repository
{

    public function createDetail($data);
}
