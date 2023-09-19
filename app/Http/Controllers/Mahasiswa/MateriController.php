<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\UtilsController;
use App\Models\Materi;
use App\Repositories\Materi\MateriRepository;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    protected $materiRepository;
    public function __construct(MateriRepository $materiRepository)
    {
        $this->materiRepository = $materiRepository;
    }
    public function getMateri($id_matkul)
    {
        $materis = $this->materiRepository->getByMatkul($id_matkul);
        foreach ($materis as &$materi) {
            $materi['status'] = $this->materiRepository->whereFeedback($materi['id']) != null ? true : false;
        }
        $utilsController = new UtilsController();
        $randomIcon = $utilsController->randomIcon();
        return view("mahasiswa.mata-kuliah.materiPage", [
            'materis' => $materis,
            'randomIcon' => $randomIcon
        ]);
    }

    public function storeFeedback(Request $request)
    {
        $data = $request->validate([
            'point' => 'required',
        ], [
            'point.required' => 'feedback harus diisi',
        ]);

        $data['materi_id'] = $request->materi_id;
        if ($this->materiRepository->storeFeedback($data)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil memberi feedback'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan feedback'
            ], 400);
        }
    }

    public function updateFeedback(Request $request)
    {
        $data = $request->validate([
            'point' => 'required',
        ], [
            'point.required' => 'feedback harus diisi',
        ]);

        $data['materi_id'] = $request->materi_id;
        if ($this->materiRepository->updateFeedback($data)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah feedback'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan feedback'
            ], 400);
        }
    }
}
