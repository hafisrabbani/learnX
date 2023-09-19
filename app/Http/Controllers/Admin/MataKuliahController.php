<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Matkul\MatkulRepository;
use App\Services\Matkul\MatkulService;
use App\Repositories\User\UserRepository;

class MataKuliahController extends Controller
{
    protected $matkulService, $matkulRepository, $userRepository;
    public function __construct(MatkulService $matkulService, MatkulRepository $matkulRepository, UserRepository $userRepository)
    {
        $this->matkulService = $matkulService;
        $this->matkulRepository = $matkulRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $matkuls = $this->matkulRepository->all();
        $dosens = $this->userRepository->getBy(['role' => 'dosen']);
        return view('admin.mata-kuliah.index', compact('matkuls', 'dosens'));
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'kode_mk' => 'required|string|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string',
            'sks' => 'required|integer',
            'dosen_id' => 'required|exists:users,id'
        ], [
            'kode_mk.required' => 'Kode mata kuliah harus diisi',
            'kode_mk.unique' => 'Kode mata kuliah sudah terdaftar',
            'nama_mk.required' => 'Nama mata kuliah harus diisi',
            'sks.required' => 'SKS harus diisi',
            'sks.integer' => 'SKS harus berupa angka',
            'dosen_id.required' => 'Dosen harus diisi',
            'dosen_id.exists' => 'Dosen tidak valid'
        ]);

        if ($status = $this->matkulRepository->create($data)) {
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
        $matkul = $this->matkulRepository->find($id);
        return response()->json([
            'status' => true,
            'data' => $matkul
        ]);
    }


    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'kode_mk' => 'required|string|unique:mata_kuliahs,kode_mk,' . $id,
            'nama_mk' => 'required|string',
            'sks' => 'required|integer',
            'dosen_id' => 'required|exists:users,id'
        ], [
            'kode_mk.required' => 'Kode mata kuliah harus diisi',
            'kode_mk.unique' => 'Kode mata kuliah sudah terdaftar',
            'nama_mk.required' => 'Nama mata kuliah harus diisi',
            'sks.required' => 'SKS harus diisi',
            'sks.integer' => 'SKS harus berupa angka',
            'dosen_id.required' => 'Dosen harus diisi',
            'dosen_id.exists' => 'Dosen tidak valid'
        ]);

        if ($status = $this->matkulRepository->update($id, $data)) {
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
        if ($status = $this->matkulRepository->delete($id)) {
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
