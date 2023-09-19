<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Repositories\Tugas\TugasRepository;
use Illuminate\Http\Request;
use App\Repositories\Enrollment\EnrollmentRepository;
use App\Repositories\Badge\BadgeRepository;
use Illuminate\Support\Collection;

class ApiController extends Controller
{
    protected $mainRepository, $enrollmentRepository, $badgeRepository;


    public function __construct(TugasRepository $mainRepository, EnrollmentRepository $enrollmentRepository, BadgeRepository $badgeRepository)
    {
        $this->mainRepository = $mainRepository;
        $this->enrollmentRepository = $enrollmentRepository;
        $this->badgeRepository = $badgeRepository;
    }

    public function getTugas($id)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Mata Kuliah berhasil didapatkan',
            'data' => $this->mainRepository->getByMatkul($id)
        ]);
    }

    public function getMateri($id)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Mata Kuliah berhasil didapatkan',
            'data' => MataKuliah::find($id)->materi()->get()
        ]);
    }

    public function getLeaderBoard($id)
    {
        try {
            $leaderBoard = $this->enrollmentRepository->getLeaderboard($id);
            $nama_matkul = MataKuliah::find($id)->nama_mk;

            return response()->json([
                'status' => 'success',
                'message' => 'Mata Kuliah berhasil didapatkan',
                'data' => [
                    'leaderBoard' => $leaderBoard,
                    'nama_matkul' => $nama_matkul,
                    'id_matkul' => $id
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata Kuliah tidak ditemukan',
                'data' => null
            ]);
        }
    }

    public function getBadge($matkul_id, $mahasiswa_id)
    {
        try {
            $badge = $this->badgeRepository->getByMatkul($matkul_id, $mahasiswa_id);
            return response()->json([
                'status' => 'success',
                'message' => 'Mata Kuliah berhasil didapatkan',
                'data' => $badge
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata Kuliah tidak ditemukan',
                'data' => null
            ]);
        }
    }

    public function getLeaderboardBadges($id)
    {
        try {
            $leaderBoard = $this->enrollmentRepository->getLeaderboard($id);
            $nama_matkul = MataKuliah::find($id)->nama_mk;
            $result = [];
            foreach ($leaderBoard as $key => $value) {
                $result[$key] = $value;
                $result[$key]['badge'] = $this->badgeRepository->getByMatkul($value['id_mk'], $value['id_mahasiswa']);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Mata Kuliah berhasil didapatkan',
                'id_matkul' => $id,
                'nama_matkul' => $nama_matkul,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }
}
