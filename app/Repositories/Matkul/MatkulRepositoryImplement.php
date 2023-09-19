<?php

namespace App\Repositories\Matkul;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\MataKuliah as Matkul;
use App\Models\User;

class MatkulRepositoryImplement extends Eloquent implements MatkulRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Matkul $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
    public function getByDosen($id)
    {
        return User::find($id)->mataKuliahs()->get();
    }

    public function getFromRole($role)
    {
        if ($role == 'dosen') {
            return $this->model->where('dosen_id', auth()->user()->id)->get();
        } elseif ($role == 'mahasiswa') {
            return $this->model->whereHas('enrollments', function ($query) {
                $query->where('id_user', auth()->user()->id);
            })->get();
        }
        return $this->model->get();
    }
}
