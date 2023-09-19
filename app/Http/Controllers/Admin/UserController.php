<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\User\UserService;
use App\Repositories\User\UserRepository;

class UserController extends Controller
{
    protected $userService, $userRepository;
    public function __construct(UserService $userService, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->getAll();
        return view('admin.manage-users.index', compact('users'));
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,mahasiswa,dosen',
            'nrp' => 'required_if:role,mahasiswa|nullable|string|unique:users,nrp'
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role harus diisi',
            'role.in' => 'Role tidak valid',
            'nrp.required_if' => 'NRP harus diisi',
            'nrp.unique' => 'NRP sudah terdaftar'

        ]);
        if ($status = $this->userRepository->create($data)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan user'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan user'
            ], 400);
        }
    }

    public function delete($id)
    {
        if ($status = $this->userRepository->delete($id)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus user'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus user'
            ], 400);
        }
    }

    public function edit($id)
    {
        $user = $this->userRepository->getById($id);
        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengambil data user',
            'data' => $user
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,mahasiswa,dosen',
            'nrp' => 'required_if:role,mahasiswa|nullable|string|unique:users,nrp,' . $id
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role harus diisi',
            'role.in' => 'Role tidak valid',
            'nrp.required_if' => 'NRP harus diisi',
            'nrp.unique' => 'NRP sudah terdaftar'
        ]);
        if ($status = $this->userRepository->update($id, $data)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah data user'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah data user'
            ], 400);
        }
    }
}
