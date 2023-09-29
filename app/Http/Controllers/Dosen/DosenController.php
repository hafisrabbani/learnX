<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Repositories\Tugas\TugasRepository;
use App\Services\Matkul\MatkulService;
use App\Services\Tugas\TugasService;
use Illuminate\Http\Request;
use App\Http\Controllers\Utils\UtilsController;
use App\Models\Submission;
use App\Models\TTS;
use App\Models\Tugas;
use App\Repositories\Enrollment\EnrollmentRepository;
use App\Repositories\Materi\MateriRepository;
use App\Repositories\Submission\SubmissionRepository;
use App\Services\FileUpload\FileUploadService;
use App\Services\Materi\MateriService;
use App\Services\Submission\SubmissionService;
use App\Services\Plagiarism\PlagiarismService;
use App\Services\Personalisasi\PersonalisasiService;
use PhpParser\Node\Stmt\ElseIf_;

class DosenController extends Controller
{
    protected $matkulService,
        $tugasService,
        $tugasRepository,
        $fileUploadService,
        $materiRepository,
        $materiService,
        $submissionService,
        $submissionRepository,
        $plagiarismService,
        $personalisasiService,
        $enrollmentRepository;

    public function __construct(
        MatkulService $service,
        TugasService $tugasService,
        TugasRepository $tugasRepository,
        FileUploadService $fileUploadService,
        MateriRepository $materiRepository,
        MateriService $materiService,
        SubmissionService $submissionService,
        SubmissionRepository $submissionRepository,
        PlagiarismService $plagiarismService,
        EnrollmentRepository $enrollmentRepository,
        PersonalisasiService $personalisasiService
    ) {
        $this->materiRepository = $materiRepository;
        $this->materiService = $materiService;
        $this->matkulService = $service;
        $this->tugasService = $tugasService;
        $this->tugasRepository = $tugasRepository;
        $this->fileUploadService = $fileUploadService;
        $this->submissionService = $submissionService;
        $this->submissionRepository = $submissionRepository;
        $this->plagiarismService = $plagiarismService;
        $this->enrollmentRepository = $enrollmentRepository;
        $this->personalisasiService = $personalisasiService;
    }


    // --------------- Mata Kuliah Area --------------------
    public function getMataKuliah()
    {
        $mataKuliahs = $this->matkulService->getByDosen();
        return view("dosen.matkulPage", compact("mataKuliahs"));
    }

    // --------------- Tugas Area --------------------
    public function getTugas($id)
    {
        $tugass = $this->tugasService->getByMatkul($id);
        $utilsController = new UtilsController();
        $randomIcon = $utilsController->randomIcon();
        return view("dosen.tugasPage", [
            'id' => $id,
            'tugass' => $tugass,
            'randomIcon' => $randomIcon
        ]);
    }

    public function createTugas(Request $request)
    {
        $data = $request->validate([
            'judul_tugas' => 'required|string',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date',
            'files' => 'required|mimes:pdf,docx,doc,xlsx,xls,ppt,pptx,txt,zip,rar,png,jpg,jpeg|max:2048',
            'id_matkul' => 'required',
        ], [
            'judul_tugas.required' => 'judul tugas harus diisi',
            'deskripsi.required' => 'deskripsi harus diisi',
            'deadline.required' => 'deadline harus diisi',
            'files.required' => 'attachment / file harus diisi',
            'id_matkul.required' => 'id matkul tidak ada',

        ]);
        $data['files'] = $this->fileUploadService->uploadLocal($request->file('files'), 'tugas');
        if ($this->tugasService->create($data)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan tugas'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan tugas'
            ], 400);
        }
    }

    public function deleteTugas($id)
    {
        if ($status = $this->tugasService->delete($id)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus tugas'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus tugas'
            ], 400);
        }
    }

    public function editTugas($id)
    {
        $tugas = $this->tugasRepository->find($id);
        return response()->json([
            'status' => true,
            'data' => $tugas
        ]);
    }

    public function updateTugas($id)
    {
        $data = request()->validate([
            'judul_tugas' => 'required|string',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date',
            'files' => 'nullable|mimes:pdf,docx,doc,xlsx,xls,ppt,pptx,txt,zip,rar,png,jpg,jpeg|max:2048',
            'id_matkul' => 'required',
        ], [
            'judul_tugas.required' => 'judul tugas harus diisi',
            'deskripsi.required' => 'deskripsi harus diisi',
            'deadline.required' => 'deadline harus diisi',
            'files.required' => 'attachment / file harus diisi',
            'id_matkul.required' => 'id matkul tidak ada',

        ]);
        if (request()->hasFile('files')) {
            $data['files'] = $this->fileUploadService->uploadLocal(request()->file('files'), 'tugas');
        }
        if ($this->tugasRepository->update($id, $data)) {
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

    // --------------- Materi Area --------------------
    public function getMateri($id)
    {
        $materis = $this->materiRepository->getByMatkul($id);
        $utilsController = new UtilsController();
        $randomIcon = $utilsController->randomIcon();
        return view("dosen.materiPage", [
            'id' => $id,
            'materis' => $materis,
            'randomIcon' => $randomIcon
        ]);
    }

    public function createMateri(Request $request)
    {
        $data = $request->validate([
            'judul_materi' => 'required|string',
            'deskripsi' => 'required|string',
            'files' => 'required|mimes:pdf,docx,doc,xlsx,xls,ppt,pptx,txt,zip,rar,png,jpg,jpeg|max:2048',
            'id_matkul' => 'required',
            'additional_reference' => 'string'
        ], [
            'judul_materi.required' => 'judul materi harus diisi',
            'deskripsi.required' => 'deskripsi harus diisi',
            'files.required' => 'attachment / file harus diisi',
            'id_matkul.required' => 'id matkul tidak ada',

        ]);
        $transcript = $this->fileUploadService->extractText(request()->file('files'));
        $generateTTS = $this->personalisasiService->generateTTS($transcript['text']);
        $uploadFile = $this->fileUploadService->uploadToAPI(request()->file('files'), 'modul');
        $data['files'] = $uploadFile['filename'];
        if ($create = $this->materiService->create($data)) {
            $insertBatchTTS = TTS::create([
                'materi_id' => $create->id,
                'transcript' => $transcript['text'],
                'job_id' => $generateTTS['job_id'],
            ]);
            if (!$insertBatchTTS) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal Menambahkan TTS'
                ], 400);
            }
            $createQuiz = $this->personalisasiService->createQuizFromModule($create->id);
            if (!$createQuiz) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal Menambahkan Quiz'
                ], 400);
            }
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan materi'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan mata materi'
            ], 400);
        }
    }

    public function deleteMateri($id)
    {
        if ($status = $this->materiService->delete($id)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus tugas'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus tugas'
            ], 400);
        }
    }

    public function editMateri($id)
    {
        $tugas = $this->materiRepository->find($id);
        return response()->json([
            'status' => true,
            'data' => $tugas
        ]);
    }

    public function updateMateri($id)
    {
        $data = request()->validate([
            'judul_materi' => 'required|string',
            'deskripsi' => 'required|string',
            'files' => 'nullable|mimes:pdf,docx,doc,xlsx,xls,ppt,pptx,txt,zip,rar,png,jpg,jpeg|max:2048',
            'id_matkul' => 'required',
            'additional_reference' => 'string'
        ], [
            'judul_materi.required' => 'judul materi harus diisi',
            'deskripsi.required' => 'deskripsi harus diisi',
            'id_matkul.required' => 'id matkul tidak ada',

        ]);

        if (request()->hasFile('files')) {
            $uploadFile = $this->fileUploadService->uploadToAPI(request()->file('files'), 'modul');

            $data['attachment'] = $uploadFile['filename'];
        }
        if ($this->materiRepository->update($id, $data)) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah materi'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah materi'
            ], 400);
        }
    }

    // --------------- Detail Tugas Area --------------------
    public function getDetailTugas($id_tugas)
    {
        $submissions = $this->submissionService->getByTugas($id_tugas);
        $files = [];
        if ($submissions->count() > 1) {
            foreach ($submissions as $submission) {
                $files[] = $submission->attachment;
            }
            $plagiarism = $this->plagiarismService->multipleCheckPlagiarism($files);
        } elseif ($submissions->count() == 1) {
            foreach ($submissions as $submission) {
                $files[] = $submission->attachment;
                $plagiarism[$submission->attachment] = '0';
            }
        } else {
            $plagiarism = null;
        }
        foreach ($submissions as $submission) {
            $submission->plagiarism = $plagiarism[$submission->attachment] . '%';
        }

        $deadline = $this->tugasRepository->find($id_tugas)->deadline;
        return view("dosen.detailTugasPage", [
            'submissions' => $submissions,
            'deadline' => $deadline,
            'id' => $id_tugas
        ]);
    }

    public function createNilai($id_tugas, $id_mahasiswa)
    {
        $data = request()->validate([
            'nilai' => 'required|integer',
        ], [
            'nilai.required' => 'nilai harus diisi',
            'nilai.integer' => 'nilai harus angka'
        ]);

        $id_matkul = Tugas::find($id_tugas)->first()->id_matkul;

        if ($this->submissionRepository->updateNilai($id_tugas, $id_mahasiswa, $data['nilai'])) {
            $this->enrollmentRepository->updatePoint(($data['nilai']), $id_mahasiswa, $id_matkul);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil memberi nilai'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memberi nilai'
            ], 400);
        }
    }

    public function editNilai($id_tugas, $id_mahasiswa)
    {
        $tugas = $this->submissionRepository->getByMahasiswa($id_tugas, $id_mahasiswa);
        return response()->json([
            'status' => true,
            'data' => $tugas
        ]);
    }

    public function updateNilai($id_tugas, $id_mahasiswa)
    {
        $data = request()->validate([
            'nilai' => 'required|integer',
        ], [
            'nilai.required' => 'nilai harus diisi',
            'nilai.integer' => 'nilai harus angka'
        ]);

        $id_matkul = Tugas::find($id_tugas)->first()->id_matkul;
        $nilai = Submission::where('id_tugas', $id_tugas)->where('id_mahasiswa', $id_mahasiswa)->first()->nilai;
        if ($this->submissionRepository->updateNilai($id_tugas, $id_mahasiswa, $data['nilai'])) {
            $this->enrollmentRepository->updatePoint(($data['nilai'] - $nilai), $id_mahasiswa, $id_matkul);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah nilai'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah nilai'
            ], 400);
        }
    }

    public function checkingPlagiarism(Request $request)
    {
        $request->validate([
            'files_compare' => 'required',
            'id_tugas' => 'required'
        ], [
            'files.required' => 'file harus diisi',
            'id_tugas.required' => 'id tugas harus diisi'
        ]);
        $submissions = $this->submissionService->getByTugas($request->id_tugas);
        $files = [];
        foreach ($submissions as $submission) {
            $files[] = $submission->attachment;
        }
        $plagiarism = $this->plagiarismService->singleCheckPlagiarism($request->files_compare, $files);
        foreach ($plagiarism['compared_files'] as $key => $value) {
            $nama = $this->submissionRepository->getByAttachment($value['file'])->mahasiswa->name;
            $plagiarism['compared_files'][$key]['nama'] = $nama;
        }
        return response()->json([
            'status' => true,
            'data' => $plagiarism
        ]);
    }
}
