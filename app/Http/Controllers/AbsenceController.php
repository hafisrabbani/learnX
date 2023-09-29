<?php

namespace App\Http\Controllers;

use App\Models\Absense;
use App\Repositories\User\UserRepository;
use App\Services\Absence\AbsenceService;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    protected $absenceService;
    protected $userRepository;
    public function __construct(AbsenceService $absenceService, UserRepository $userRepository)
    {
        $this->absenceService = $absenceService;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $absences = $this->absenceService->all();
        $students = $this->userRepository->getBy(['role' => 'mahasiswa']);
        return view('dosen.absencePage', ['absences' => $absences, 'students' => $students]);
    }

    public function store()
    {
        $newAbsence = $this->absenceService->create('');

        return $this->absenceService->createDetail(['absence_id' => $newAbsence->id, 'users' => request('students')])
            ? back()->with('success', 'absen berhasil dibuat!') : back()->with('error', 'proses gagal!');
    }

    public function delete(Request $request)
    {

        $this->absenceService->delete($request->id);
        return back()->with('success', 'absen berhasil dihapus!');
    }

    public function getAbsenseDetail(Request $request)
    {
        $students = [];
        $absenseDetails =  $this->absenceService->find($request->id)->AbsenseDetail();
        foreach ($absenseDetails as $absenseDetail) {
            $students[] = ['name' => $absenseDetail->user()->name, 'is_absence' =>  $absenseDetail->is_absence];
        }
        return $students;
    }
}
