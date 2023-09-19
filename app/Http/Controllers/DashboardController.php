<?php

namespace App\Http\Controllers;

use App\Models\Personalisasi;
use Illuminate\Http\Request;
use App\Services\Personalisasi\PersonalisasiService;
use App\Models\Enrollment;
use App\Models\Materi;

class DashboardController extends Controller
{

    protected $personalisasiService;
    public function __construct(PersonalisasiService $personalisasiService)
    {
        $this->personalisasiService = $personalisasiService;
    }

    public function index()
    {
        $role = auth()->user()->role;
        $data = [];
        if ($role == 'admin') {
            // return response()->json([
            //     'message' => 'success',
            //     'data' => auth()->user()
            // ]);
        } else if ($role == 'dosen') {
            // return redirect()->route('dosen.mata-kuliah.index');
        } else if ($role == 'mahasiswa') {
            $personalisasi = $this->personalisasiService->personalisasi(auth()->user()->id)['result'];
            $matkul = Enrollment::where('id_user', auth()->user()->id)->get();
            $totalMateri = 0;
            $totalTugas = 0;
            foreach ($matkul as $value) {
                $totalMateri += $value->matkul->materi->count();
                $totalTugas += $value->matkul->tugas->count();
            }

            $data = [
                'personalisasi' => $personalisasi,
                'totalMatkul' => $matkul->count(),
                'totalMateri' => $totalMateri,
                'totalTugas' => $totalTugas,
                'matkul' => $matkul
            ];
        }
        $data['user'] = auth()->user();
        return view('dashboard.index', $data);
    }
}
