<?php

namespace App\Repositories\Badge;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Badge;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class BadgeRepositoryImplement extends Eloquent implements BadgeRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Badge $model)
    {
        $this->model = $model;
    }

    public function checkSubmission(int $idTugas)
    {
        $totalSubmission = Submission::where('id_tugas', $idTugas)->get()->count();
        $badge = null;

        if ($totalSubmission == 1) {
            $badge = 'gold';
        } elseif ($totalSubmission == 2) {
            $badge = 'silver';
        } elseif ($totalSubmission == 3) {
            $badge = 'bronze';
        }

        if (!is_null($badge)) {
            return $badge;
        } else {
            return null;
        }
    }

    public function getByMatkul(int $idMatkul, int $idMahasiswa)
    {
        return $this->model->selectRaw('COUNT(*) as count, type, name')->where('matkul_id', $idMatkul)->where('mahasiswa_id', $idMahasiswa)->groupBy('type', 'name')->orderBy('count', 'desc')->get();
    }
}
