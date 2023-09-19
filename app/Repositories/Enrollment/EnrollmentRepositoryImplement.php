<?php

namespace App\Repositories\Enrollment;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Enrollment;
use App\Models\User;

class EnrollmentRepositoryImplement extends Eloquent implements EnrollmentRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Enrollment $model)
    {
        $this->model = $model;
    }


    public function checkIfExist($id_matkul, $id_user)
    {
        $checking = $id_matkul . $id_user;
        $enrollment = $this->model->where('key_unique', $checking)->first();
        return $enrollment;
    }

    public function getAllMhsGroupByMatkul()
    {
        $enrollment = $this->model
            ->with('matkul', 'mahasiswa')
            ->get()
            ->groupBy('id_user')
            ->map(function ($items) {
                return [
                    'id' => $items->last()->id,
                    'id_user' => $items->last()->id_user,
                    'nama_mhs' => $items->last()->mahasiswa->name,
                    'matkul' => $items->pluck('matkul'),
                    'key_unique' => $items->last()->key_unique,
                    'created_at' => $items->last()->created_at,
                    'updated_at' => $items->last()->updated_at,
                ];
            });

        return $enrollment;
    }

    public function getByMahasiswa($id_mahasiswa)
    {
        return User::with('mataKuliahByMahasiswa')->find($id_mahasiswa)->mataKuliahByMahasiswa;
    }

    public function getLeaderBoard($idMatkul)
    {
        return $this->model->with('mahasiswa')->where('id_matkul', $idMatkul)->orderBy('point', 'desc')->get()->map(
            function ($item) {
                return [
                    'id_mk' => $item->id_matkul,
                    'nama_mahasiswa' => $item->mahasiswa->name,
                    'id_mahasiswa' => $item->mahasiswa->id,
                    'level' => $this->level($item->point),
                    'point' => $item->point
                ];
            }
        );
    }

    public function updatePoint($nilai, $id_mahasiswa, $id_matkul)
    {
        try {

            $point = $this->model->where('id_matkul', $id_matkul)->where('id_user', $id_mahasiswa)->first()->point;
            $update = $this->model->where('id_matkul', $id_matkul)->where('id_user', $id_mahasiswa)->update([
                'point' => $point + $nilai,
            ]);
            return $update;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function level($number, $sum = 0, $index = 1)
    {
        if ($number < 100) {
            return 1;
        }
        $number = intval($number / 100);
        while (($sum += $index++) < $number);
        return ($sum == $number) ? ($index) : ($index - 1);
    }
}
