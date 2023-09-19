<?php

namespace App\Repositories\Submission;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Submission;

class SubmissionRepositoryImplement extends Eloquent implements SubmissionRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Submission $model)
    {
        $this->model = $model;
    }

    public function getByTugas($id_tugas)
    {
        return $this->model->where('id_tugas', $id_tugas)->get();
    }

    public function getByAttachment($attachment)
    {
        return $this->model->where('attachment', $attachment)->first();
    }

    public function updateNilai($id_tugas, $id_mahasiswa, $data)
    {
        $nilai =  $this->model->where('id_tugas', $id_tugas)->where('id_mahasiswa', $id_mahasiswa)->first()->nilai;
        if ($nilai == null) {
            # enrollment + data
        } else {
            # enrollment - nilai + data
        }
        return $this->model->where('id_tugas', $id_tugas)->where('id_mahasiswa', $id_mahasiswa)->update(['nilai' => $data]);
    }

    public function getByMahasiswa($id_tugas, $id_mahasiswa)
    {
        return $this->model->where('id_tugas', $id_tugas)->where('id_mahasiswa', $id_mahasiswa)->first();
    }

    public function create($data)
    {
        return $this->model->create(
            [
                'komentar' => $data['komentar'],
                'id_tugas' => $data['id_tugas'],
                'attachment' => $data['attachment'],
                'id_mahasiswa' => $data['id_mahasiswa']
            ]
        );
    }

    public function update($id, array $data)
    {
        return $this->model->where('id', $id)->update([
            'komentar' => $data['komentar'],
            'attachment' => $data['attachment']
        ]);
    }
}
