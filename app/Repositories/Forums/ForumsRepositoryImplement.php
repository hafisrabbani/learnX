<?php

namespace App\Repositories\Forums;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Forum as Forums;

class ForumsRepositoryImplement extends Eloquent implements ForumsRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Forums $model)
    {
        $this->model = $model;
    }

    public function getAllForums($role)
    {
        if ($role == 'dosen') {
            // return $this->model->join('materis', 'materis.id', '=', 'forums.materi_id')
            //     ->join('mata_kuliahs', 'mata_kuliahs.id', '=', 'materis.id_matkul')
            //     ->where('mata_kuliahs.dosen_id', auth()->user()->id)
            //     ->select('forums.*, mata_kuliahs.nama_mk as nama_matkul')
            //     ->get();
            return $this->model->whereHas('materi', function ($query) {
                $query->whereHas('matkul', function ($query) {
                    $query->where('dosen_id', auth()->user()->id);
                });
            })->get();
        }

        // return $this->model->join('materis', 'materis.id', '=', 'forums.materi_id')
        //     ->join('enrollments', 'enrollments.id_matkul', '=', 'materis.id_matkul')
        //     ->where('enrollments.id_user', auth()->user()->id)
        //     ->get();

        // checking in enrollments table
        return $this->model->whereHas('materi', function ($query) {
            $query->whereHas('matkul', function ($query) {
                $query->whereHas('enrollments', function ($query) {
                    $query->where('id_user', auth()->user()->id);
                });
            });
        })->get();
    }
}
