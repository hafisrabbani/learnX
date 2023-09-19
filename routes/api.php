<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\mahasiswa\ApiController;
use App\Http\Controllers\Mahasiswa\MataKuliahController;
use App\Http\Controllers\Mahasiswa\TugasController;
use App\Repositories\Materi\MateriRepository;
use App\Services\Tugas\TugasService;
use App\Http\Controllers\Mahasiswa\PersonalisasiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('login')->controller(AuthController::class)->group(function () {
    Route::post('/', 'loginApi')->name('loginApi');
    Route::post('/logout', 'logout')->name('logoutApi');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::controller(MataKuliahController::class)
        ->prefix('mata-kuliah')
        ->name('mata-kuliah')->group(function () {
            Route::post('/', 'getMatakuliahApi');
            Route::get('/quiz/{id_materi}', 'getQuiz')->name('quiz');
            Route::post('/quiz-store', 'postQuiz')->name('quiz-store');
        });

    Route::controller(ApiController::class)
        ->prefix('mata-kuliah/tugas/')
        ->name('tugas.')->group(function () {
            Route::get('/{id}', 'getTugas')->name("index");
            // Route::get('/detail-tugas/{id_tugas}', 'getDetailTugas')->name("getDetailTugas");
            // Route::post('/create', 'createSubmission')->name("createSubmission");
            // Route::get('/edit/{id_tugas}', 'editSubmission')->name("editSubmission");
            // Route::post('/update/{id_submission}', 'updateSubmission')->name("updateSubmission");
        });

    Route::controller(ApiController::class)
        ->prefix('mata-kuliah/materi')
        ->name('materi.')->group(function () {
            Route::get('/{id}', 'getMateri')->name("index");
            Route::get('leaderboard/{id}', 'getLeaderboard')->name("leaderboard");
            Route::get('/badge/{id_matkul}/{id_mhs}', 'getBadge')->name("badge");
            Route::get('leaderboard-badges/{id}', 'getLeaderboardBadges')->name("leaderboard-badges");
        });


    Route::controller(PersonalisasiController::class)
        ->prefix('personalisasi')
        ->name('personalisasi')->group(function () {
            Route::get('/', 'ApiGetPersonalisasi')->name('personalisasi');
        });
});
