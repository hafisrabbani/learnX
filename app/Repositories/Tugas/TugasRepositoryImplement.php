<?php

namespace App\Repositories\Tugas;

use App\Models\MataKuliah;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Tugas;

class TugasRepositoryImplement extends Eloquent implements TugasRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Tugas $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
    public function create($data)
    {
        // code buat simpen file ke directory (belum)
        return $this->model->create([
            'judul_tugas' => $data['judul_tugas'],
            'id_matkul' => $data['id_matkul'],
            'deskripsi' => $data['deskripsi'],
            'deadline' => $data['deadline'],
            'attachment' => $data['attachment']
        ]);
    }

    public function getByMatkul($id)
    {
        return MataKuliah::find($id)->tugas()->get();
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
