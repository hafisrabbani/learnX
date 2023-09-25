<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;
use App\Repositories\Matkul\MatkulRepository;
use App\Repositories\Enrollment\EnrollmentRepository;

class EnrollmentController extends Controller
{
    protected $userRepository, $mataKuliahRepository, $enrollmentRepository;

    public function __construct(UserRepository $userRepository, MatkulRepository $mataKuliahRepository, EnrollmentRepository $enrollmentRepository)
    {
        $this->userRepository = $userRepository;
        $this->mataKuliahRepository = $mataKuliahRepository;
        $this->enrollmentRepository = $enrollmentRepository;
    }

    public function index()
    {
        $mahasiswas = $this->userRepository->getBy(['role' => 'mahasiswa']);
        $mataKuliahs = $this->mataKuliahRepository->all();
        $enrollments = $this->enrollmentRepository->all();
        return view('admin.enrollments.index', compact('mahasiswas', 'mataKuliahs', 'enrollments'));
    }

    public function create(Request $request)
    {
        if ($this->enrollmentRepository->checkIfExist($request->id_matkul, $request->id_user)) {
            return response()->json([
                'status' => false,
                'message' => 'Mahasiswa sudah terdaftar pada mata kuliah ini'
            ], 400);
        }
        $data = $request->validate([
            'id_matkul' => 'required|exists:mata_kuliahs,id',
            'id_user' => 'required|exists:users,id'
        ], [
            'id_matkul.required' => 'Mata kuliah harus diisi',
            'id_matkul.exists' => 'Mata kuliah tidak valid',
            'id_user.required' => 'Mahasiswa harus diisi',
            'id_user.exists' => 'Mahasiswa tidak valid'
        ]);

        $data['key_unique'] = $data['id_matkul'] . $data['id_user'];

        if ($status = $this->enrollmentRepository->create($data)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan mata kuliah'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan mata kuliah'
            ], 400);
        }
    }

    public function edit($id)
    {
        $enrollment = $this->enrollmentRepository->find($id)->first();
        return response()->json([
            'status' => true,
            'data' => $enrollment
        ], 200);
    }

    public function update(Request $request, $id)
    {
        if ($check = $this->enrollmentRepository->checkIfExist($request->id_matkul, $request->id_user)) {
            if ($check->id != $id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mahasiswa sudah terdaftar pada mata kuliah ini'
                ], 400);
            }
        }
        $data = $request->validate([
            'id_matkul' => 'required|exists:mata_kuliahs,id',
            'id_user' => 'required|exists:users,id'
        ], [
            'id_matkul.required' => 'Mata kuliah harus diisi',
            'id_matkul.exists' => 'Mata kuliah tidak valid',
            'id_user.required' => 'Mahasiswa harus diisi',
            'id_user.exists' => 'Mahasiswa tidak valid'
        ]);

        if ($status = $this->enrollmentRepository->update($id, $data)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah mata kuliah'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah mata kuliah'
            ], 400);
        }
    }

    public function delete($id)
    {
        if ($status = $this->enrollmentRepository->delete($id)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus mata kuliah'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus mata kuliah'
            ], 400);
        }
    }
}
