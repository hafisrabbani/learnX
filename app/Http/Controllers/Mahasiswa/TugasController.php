<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\UtilsController;
use App\Models\Tugas;
use App\Models\User;
use App\Repositories\Badge\BadgeRepository;
use App\Repositories\Enrollment\EnrollmentRepository;
use App\Repositories\Submission\SubmissionRepository;
use App\Repositories\Tugas\TugasRepository;
use App\Services\FileUpload\FileUploadService;
use App\Services\Tugas\TugasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    protected $tugasService,
        $tugasRepository,
        $submissionRepository,
        $fileUploadService,
        $enrollmentRepository,
        $badgeRepository;
    public function __construct(
        TugasService $tugasService,
        TugasRepository $tugasRepository,
        SubmissionRepository $submissionRepository,
        FileUploadService $fileUploadService,
        EnrollmentRepository $enrollmentRepository,
        BadgeRepository $badgeRepository
    ) {
        $this->tugasService = $tugasService;
        $this->tugasRepository = $tugasRepository;
        $this->submissionRepository = $submissionRepository;
        $this->fileUploadService = $fileUploadService;
        $this->enrollmentRepository = $enrollmentRepository;
        $this->badgeRepository = $badgeRepository;
    }

    public function getTugas($id_matkul)
    {
        $tugass = $this->tugasService->getByMatkul($id_matkul);
        $utilsController = new UtilsController();
        $randomIcon = $utilsController->randomIcon();
        return view("mahasiswa.mata-kuliah.tugasPage", [
            'tugass' => $tugass,
            'randomIcon' => $randomIcon
        ]);
    }

    public function getDetailTugas($id_tugas)
    {
        $tugas = $this->tugasRepository->find($id_tugas);
        $submission = $this->submissionRepository->getByMahasiswa($id_tugas, Auth::user()->id);
        if ($submission != null) {
            $status =  $submission->created_at > $tugas->deadline ? "terlambat" : "sudah mengumpulkan";
        } else {
            $status = "";
        }
        return view("mahasiswa.mata-kuliah.detailTugasPage", ['tugas' => $tugas, 'status' => $status, 'submission' => $submission]);
    }

    public function createSubmission(Request $request)
    {
        $data = $request->validate([
            'komentar' => 'string|nullable',
            'attachment' => 'required|mimes:pdf,docx,doc,xlsx,xls,ppt,pptx,txt,zip,rar,png,jpg,jpeg|max:2048',
            'id_tugas' => 'required',
        ], [
            'attachment.required' => 'attachment / file harus diisi',
            'id_tugas.required' => 'id tugas tidak ada',

        ]);
        $uploadFile = $this->fileUploadService->uploadToAPI(request()->file('attachment'));

        $data['attachment'] = $uploadFile['filename'];
        $data['id_mahasiswa'] = Auth::user()->id;
        $id_matkul = Tugas::where('id', $data['id_tugas'])->first()->id_matkul;
        $title = 'kerja bagus!!';

        if ($this->submissionRepository->create($data)) {
            $this->enrollmentRepository->updatePoint(100, $data['id_mahasiswa'], $id_matkul);
            $badge = $this->badgeRepository->checkSubmission($data['id_tugas']);
            if (!is_null($badge)) {
                $title = "mendapat medali $badge";
                $this->badgeRepository->create([
                    'mahasiswa_id' => Auth::user()->id,
                    'matkul_id' => $id_matkul,
                    'type' => $badge,
                    'name' => 'submission'
                ]);
            }


            return response()->json([
                'status' => true,
                'message' => ['title' => $title, 'subtitle' => 'bertambah 100 point']
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan mata materi'
            ], 400);
        }
    }

    public function editSubmission($id_tugas)
    {
        $submission = $this->submissionRepository->getByMahasiswa($id_tugas, Auth::user()->id);
        return response()->json([
            'status' => true,
            'data' => $submission
        ]);
    }

    public function updateSubmission($id_submission)
    {
        $data = request()->validate([
            'komentar' => 'nullable|string',
            'attachment' => 'nullable|mimes:pdf,docx,doc,xlsx,xls,ppt,pptx,txt,zip,rar,png,jpg,jpeg|max:2048',
        ]);
        if (request()->hasFile('attachment')) {
            $data['attachment'] = $this->fileUploadService->uploadToAPI(request()->file('attachment'))['filename'];
        }
        if ($this->submissionRepository->update($id_submission, $data)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah tugas'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah tugas'
            ], 400);
        }
    }
}
