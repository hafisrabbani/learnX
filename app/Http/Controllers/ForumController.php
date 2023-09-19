<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileUpload\FileUploadService;
use App\Repositories\Matkul\MatkulRepository;
use App\Repositories\Materi\MateriRepository;
use App\Repositories\Forums\ForumsRepository;
use App\Repositories\ForumThreads\ForumThreadsRepository;

class ForumController extends Controller
{
    protected $fileUploadService, $matkulRepository, $materiRepository, $forumsRepository, $forumThreadsRepository;
    public function __construct(FileUploadService $fileUploadService, MatkulRepository $matkulRepository, MateriRepository $materiRepository, ForumsRepository $forumsRepository, ForumThreadsRepository $forumThreadsRepository)
    {
        $this->fileUploadService = $fileUploadService;
        $this->matkulRepository = $matkulRepository;
        $this->materiRepository = $materiRepository;
        $this->forumsRepository = $forumsRepository;
        $this->forumThreadsRepository = $forumThreadsRepository;
    }

    public function index()
    {
        // dd($this->forumsRepository->all());
        // $data = $this->forumsRepository->all();
        // // foreach ($data as $key => $value) {
        // //     $data[$key]->materi = $value->materi;
        // //     $data[$key]->matkul = $value->materi->matkul;
        // // }

        // // dd($data);
        // $chunk = $data->chunk(10);
        // f
        $data = $this->forumsRepository->getAllForums(auth()->user()->role);

        foreach ($data as $key => $value) {
            $data[$key]->materi = $value->materi;
            $data[$key]->matkul = $value->materi->matkul;
        }

        return view('forums.index', compact('data'));
    }

    public function create()
    {
        $matkul = $this->matkulRepository->getFromRole(auth()->user()->role);
        return view('forums.create', compact('matkul'));
    }

    public function data()
    {
        $id = request()->matkul_id;
        $materi = $this->materiRepository->where(['id_matkul' => $id]);
        return response()->json([
            'status' => 'success',
            'data' => $materi
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'matkul_id' => 'required|exists:mata_kuliahs,id',
            'materi_id' => 'required|exists:materis,id',
            'title' => 'required',
            'wysiwyg-editor' => 'required|min:10',
        ], [
            'matkul_id.required' => 'Mata Kuliah harus diisi',
            'matkul_id.exists' => 'Mata Kuliah tidak ditemukan',
            'materi_id.required' => 'Materi harus diisi',
            'materi_id.exists' => 'Materi tidak ditemukan',
            'title.required' => 'Judul harus diisi',
            'wysiwyg-editor.required' => 'Isi harus diisi',
            'wysiwyg-editor.min' => 'Isi minimal 10 karakter',
        ]);

        $create = $this->forumsRepository->create([
            'materi_id' => $request->materi_id,
            'user_id' => auth()->user()->id,
            'judul' => $request->title,
            'konten' => $request->input('wysiwyg-editor'),
        ]);

        if ($create) {
            return response()->json([
                'status' => 'success',
                'message' => 'Forum berhasil dibuat'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Forum gagal dibuat'
        ], 400);
    }


    public function delete()
    {
    }

    public function reply(Request $request)
    {
        $request->validate([
            'konten' => 'required|min:10',
        ], [
            'konten.required' => 'Isi harus diisi',
            'konten.min' => 'Isi minimal 10 karakter',
        ]);

        $create = $this->forumThreadsRepository->create([
            'forum_id' => $request->forum_id,
            'user_id' => auth()->user()->id,
            'konten' => $request->input('konten'),
            'is_verified' => (auth()->user()->role == 'dosen') ? 1 : 0,
        ]);

        if ($create) {
            return response()->json([
                'status' => 'success',
                'message' => 'Forum berhasil dibuat'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Forum gagal dibuat'
        ], 400);
    }

    public function show($id)
    {
        $data = $this->forumsRepository->findOrFail($id);
        // dd($data);
        return view('forums.details', compact('data', 'id'));
    }

    public function fileUploads(Request $request)
    {
        if ($request->hasFile('upload')) {
            $this->fileUploadService->ckeditorUpload($request->file('upload'), $request);
        }
    }

    public function verify(Request $request)
    {
        $role = auth()->user()->role;
        if ($role != 'dosen') {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses'
            ], 400);
        }
        $id = $request->id;
        $data = $this->forumThreadsRepository->findOrFail($id);
        $data->is_verified = 1;
        $data->save();
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Jawaban berhasil diverifikasi'
            ], 200);
        }
    }
}
