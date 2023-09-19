<?php

namespace App\Repositories\Submission;

use LaravelEasyRepository\Repository;

interface SubmissionRepository extends Repository
{

    public function getByTugas($id_tugas);
    public function updateNilai($id_tugas, $id_mahasiswa, $data);
    public function getByMahasiswa($id_tugas, $id_mahasiswa);
    public function getByAttachment($attachment);
}
