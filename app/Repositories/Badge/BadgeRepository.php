<?php

namespace App\Repositories\Badge;

use LaravelEasyRepository\Repository;

interface BadgeRepository extends Repository
{

    public function checkSubmission(int $idTugas);
    public function getByMatkul(int $idMatkul, int $mahasiswa_id);
}
