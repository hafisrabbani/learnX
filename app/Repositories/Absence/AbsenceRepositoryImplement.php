<?php

namespace App\Repositories\Absence;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Absence;
use App\Models\Absense;
use App\Models\AbsenseDetail;

class AbsenceRepositoryImplement extends Eloquent implements AbsenceRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Absense $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create();
    }

    public function createDetail($data)
    {
        $lastCreatedDetail = null;
        foreach ($data['users'] as $studentId => $isAbsence) {
            $lastCreatedDetail = AbsenseDetail::create([
                'absence_id' => $data['absence_id'],
                'user_id' => $studentId,
                'is_absence' => $isAbsence ? 1 : 0,
            ]);
        }
        return $lastCreatedDetail;
    }
}
