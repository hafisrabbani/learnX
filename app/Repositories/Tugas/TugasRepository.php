<?php

namespace App\Repositories\Tugas;

use LaravelEasyRepository\Repository;

interface TugasRepository extends Repository{

    // Write something awesome :)
    public function getByMatkul($id);
}
