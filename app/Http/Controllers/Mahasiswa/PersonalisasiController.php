<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Materi;
use App\Models\AnsewerQuiz;
use App\Models\QuizResult;
use Illuminate\Support\Facades\DB;
use App\Services\Personalisasi\PersonalisasiService;
use App\Repositories\Enrollment\EnrollmentRepository;

class PersonalisasiController extends Controller
{
    protected $personalisasiService, $enrollmentRepository;
    public function __construct(PersonalisasiService $personalisasiService, EnrollmentRepository $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
        $this->personalisasiService = $personalisasiService;
    }
    public function getQuiz($id_materi)
    {
        $materi = Materi::find($id_materi);
        if ($materi == null) {
            return redirect()->back();
        }
        $quiz = Quiz::where('materi_id', $id_materi)->get();
        foreach ($quiz as &$q) {
            $q->option_a = $q->option_a;
            $q->option_b = $q->option_b;
            $q->option_c = $q->option_c;
            $q->option_d = $q->option_d;
        }
        $quiz = $quiz->shuffle();
        $checkIsAnswered = AnsewerQuiz::where('user_id', auth()->user()->id)->where('quiz_id', $quiz[0]->id)->first();
        $nilai = 0;
        if ($checkIsAnswered != null) {
            $getAllQuizWithAnswer = AnsewerQuiz::where('user_id', auth()->user()->id)->with('quiz')->whereHas('quiz', function ($q) use ($id_materi) {
                $q->where('materi_id', $id_materi);
            })->get();

            foreach ($getAllQuizWithAnswer as $key => $value) {
                $nilai += $value->score;
            }
        }

        $analytic = QuizResult::where('user_id', auth()->user()->id)->where('materi_id', $id_materi)->first();
        if ($analytic != null) {
            $analytic = $analytic->analysis;
        } else {
            $analytic = null;
        }

        return view('mahasiswa.personalisasi.quiz', [
            'materi' => $materi,
            'quiz' => $quiz,
            'getAllQuizWithAnswer' => $getAllQuizWithAnswer ?? null,
            'nilai' => $nilai,
            'analytic' => $analytic
        ]);
    }

    public function storeQuiz(Request $request)
    {
        $data = $request->validate([
            'quiz' => 'required|array',
        ]);
        try {
            $score = 0;
            $wrongQuestions = "";
            $materi_id = 0;
            foreach ($data['quiz'] as $key => $value) {
                $quiz = Quiz::find($key);
                $materi_id = $quiz->materi_id;
                if ($value == $quiz->true_answer) {
                    $score += $this->getPointPerSoal($quiz->materi->quiz->count());
                } else {
                    $temp = 'option_' . strtolower($quiz->true_answer);
                    $wrongQuestions .= $quiz->question . " -> " . $quiz->$temp . "\n";
                }

                DB::transaction(function () use ($key, $value) {
                    $checkIsAnswered = AnsewerQuiz::where('quiz_id', $key)->where('user_id', auth()->user()->id)->first();
                    if ($checkIsAnswered != null) {
                        throw new \Exception('Anda sudah mengisi quiz ini');
                    }
                    $quiz = Quiz::find($key);
                    $answer = new AnsewerQuiz();
                    $answer->quiz_id = $quiz->id;
                    $answer->user_id = auth()->user()->id;
                    $answer->answer = $value;
                    $answer->score = ($value == $quiz->true_answer) ? $this->getPointPerSoal($quiz->materi->quiz->count()) : 0;
                    $answer->is_correct = ($value == $quiz->true_answer) ? true : false;
                    $answer->save();
                });
            }
            if ($score < 100) {
                $quizAnalyzer = QuizResult::create([
                    'materi_id' => $materi_id,
                    'user_id' => auth()->user()->id,
                    'score' => $score,
                    'wrong_answer' => $wrongQuestions,
                    'analysis' => ''
                ]);
                $id_analyzer = $request->materi_id;
                $analytic = $this->personalisasiService->getAnalytic($id_analyzer, auth()->user()->id);
                $updateQuizAnalyzer = QuizResult::find($quizAnalyzer->id)->update([
                    'analysis' => $analytic['result']
                ]);
            }
            $id_matkul = Materi::find($request->materi_id)->id_matkul;
            $point = $this->enrollmentRepository->updatePoint($score, auth()->user()->id, $id_matkul);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengisi quiz'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function ApiGetPersonalisasi(Request $request)
    {
        try {
            $personalisasi = $this->personalisasiService->personalisasi(auth()->user()->id)['result'];
            return response()->json([
                'status' => true,
                'data' => str_replace("\n", "", $personalisasi)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getPointPerSoal($total_soal)
    {
        $point = 100 / $total_soal;
        return $point;
    }
}
