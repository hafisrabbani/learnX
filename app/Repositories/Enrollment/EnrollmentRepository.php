<?php

namespace App\Repositories\Enrollment;

use LaravelEasyRepository\Repository;

interface EnrollmentRepository extends Repository
{

    public function checkIfExist($id_matkul, $id_user);
    public function getAllMhsGroupByMatkul();
    public function getByMahasiswa($id_mahasiswa);
    public function getLeaderBoard($idMatkul);
    public function updatePoint($nilai, $id_mahasiswa, $id_matkul);
}
