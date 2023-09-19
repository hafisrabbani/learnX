<?php

use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Dosen\DosenController;
use App\Http\Controllers\Utils\UtilsController;
use App\Http\Controllers\Mahasiswa\VirtualLabController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\mahasiswa\BadgeController;
use App\Http\Controllers\Mahasiswa\MataKuliahController as MahasiswaMataKuliahController;
use App\Http\Controllers\Mahasiswa\MateriController;
use App\Http\Controllers\Mahasiswa\TugasController;
use App\Http\Controllers\Mahasiswa\PersonalisasiController;

Route::redirect('/', '/auth', 301);

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login.index');
    Route::post('/', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
});


Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
    });

    // Admin Area
    Route::middleware('check-role:admin')->prefix('admin')->group(function () {
        Route::controller(UserController::class)
            ->prefix('user-manage')
            ->name('user-manage.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/create', 'create')->name('create');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'delete')->name('delete');
            });

        Route::controller(MataKuliahController::class)
            ->prefix('mata-kuliah')
            ->name('mata-kuliah.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/create', 'create')->name('create');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'delete')->name('delete');
            });


        Route::controller(EnrollmentController::class)
            ->prefix('enrollment')
            ->name('enrollment.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/create', 'create')->name('create');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'delete')->name('delete');
            });
    });

    // Dosen Area
    Route::middleware('check-role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::controller(DosenController::class)
            ->prefix('mata-kuliah')
            ->name('mata-kuliah')->group(function () {
                Route::get('/', 'getMatakuliah');
            });

        Route::controller(DosenController::class)
            ->prefix('mata-kuliah/tugas')
            ->name('tugas.')->group(function () {
                Route::get('/{id}', 'getTugas')->name("index");
                Route::post('/create', 'createTugas')->name("createTugas");
                Route::delete('/delete/{id}', 'deleteTugas')->name("deleteTugas");
                Route::get('/edit/{id}', 'editTugas')->name("editTugas");
                Route::post('/update/{id}', 'updateTugas')->name("updateTugas");
                Route::post('/check-plagiarism', 'checkingPlagiarism')->name("checkingPlagiarism");
            });

        Route::controller(DosenController::class)
            ->prefix('mata-kuliah/materi')
            ->name('materi.')->group(function () {
                Route::get('/{id}', 'getMateri')->name("index");
                Route::post('/create', 'createMateri')->name("createMateri");
                Route::delete('/delete/{id}', 'deleteMateri')->name("deleteMateri");
                Route::get('/edit/{id}', 'editMateri')->name("editMateri");
                Route::post('/update/{id}', 'updateMateri')->name("updateMateri");
            });

        Route::controller(DosenController::class)
            ->prefix('mata-kuliah/detail-tugas/{id_tugas}')
            ->name('detail-tugas.')->group(function () {
                Route::get('/', 'getDetailTugas')->name("index");
                Route::post('/create//{id_mahasiswa}', 'createNilai')->name("createNilai");
                Route::get('/edit/{id_mahasiswa}', 'editNilai')->name("editNilai");
                Route::post('/update/{id_mahasiswa}', 'updateNilai')->name("updateNilai");
            });
    });

    // Mahasiswa Area
    Route::middleware('check-role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::controller(VirtualLabController::class)
            ->prefix('virtual-lab')
            ->name('virtual-lab.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('circuits-simulate', 'circuitsSimulate')->name('circuits-simulate');
                Route::get('capacitor-simulate', 'capacitorSimulate')->name('capacitor-simulate');
                Route::get('operating-system-simulate', 'operatingSystemSimulate')->name('operating-system-simulate');
                Route::get('geometric-optics-simulate', 'geometricOpticsSimulate')->name('geometric-optics-simulate');
                Route::get('api-tester', 'apiTester')->name('api-tester');
                Route::get('php-compiler', 'phpCompiler')->name('php-compiler');
                Route::get('kanban-board', 'kanbanBoard')->name('kanban-board');
                Route::get('python-compiler', 'pythonCompiler')->name('python-compiler');
            });

        Route::controller(MahasiswaMataKuliahController::class)
            ->prefix('mata-kuliah')
            ->name('mata-kuliah.')->group(function () {
                Route::get('/', 'getMatakuliah');
                Route::get('/leaderboard/{id_matkul}', 'getLeaderboard')->name('leaderboard');
            });

        Route::controller(MateriController::class)
            ->prefix('mata-kuliah/materi')
            ->name('materi.')->group(function () {
                Route::get('/{id_matkul}', 'getMateri')->name("index");
                Route::post('/storeFeedback', 'storeFeedback')->name("storeFeedback");
            });

        Route::controller(TugasController::class)
            ->prefix('mata-kuliah/tugas/')
            ->name('tugas.')->group(function () {
                Route::get('/{id_matkul}', 'getTugas')->name("index");
                Route::get('/detail-tugas/{id_tugas}', 'getDetailTugas')->name("getDetailTugas");
                Route::post('/create', 'createSubmission')->name("createSubmission");
                Route::get('/edit/{id_tugas}', 'editSubmission')->name("editSubmission");
                Route::post('/update/{id_submission}', 'updateSubmission')->name("updateSubmission");
            });

        Route::controller(PersonalisasiController::class)
            ->prefix('journey')
            ->name('personalisasi.')->group(function () {
                Route::get('/quiz/{id_materi}', 'getQuiz')->name('quiz');
                Route::post('quiz/store', 'storeQuiz')->name('storeQuiz');
            });

        Route::controller(BadgeController::class)->prefix('badge')->name('badge.')->group(function () {
            Route::get('/getUserBadge/{matkul_id}/{mahasiswa_id}', 'getBadge')->name('getBadge');
        });
    });

    // Forum Area
    Route::middleware('check-role:mahasiswa,dosen')
        ->prefix('forums')
        ->name('forums.')
        ->controller(ForumController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('store');
            Route::get('data', 'data')->name('data');
            Route::get('detail/{id}', 'show')->name('show');
            Route::post('/reply', 'reply')->name('reply');
            Route::delete('/delete/{id}', 'delete')->name('delete');
            Route::post('verify', 'verify')->name('verify');
            Route::post('/file-uploads', 'fileUploads')->name('file-uploads');
        });
});

Route::prefix('/utils')->group(function () {
    Route::controller(UtilsController::class)
        ->name('utils.')
        ->group(function () {
            Route::get('random-icon', 'randomIcon')->name('random-icon');
            Route::get('download/tugas/{filename}', 'downloadTugas')->name('download-tugas');
            Route::post('php-compiler', 'phpCompiler')->name('php-compiler');
            Route::post('python-compiler', 'pythonCompiler')->name('python-compiler');
            Route::get('redirect', 'redirectExternal')->name('redirect');
        });
    Route::prefix('vlab')->name('vlab.')->group(function () {
        Route::view('terminal', 'utils.terminal')->name('terminal');
        Route::view('network', 'utils.network')->name('network');
        Route::view('kanban', 'utils.kanban')->name('kanban');
        Route::view('api-tester', 'utils.api-tester')->name('api-tester');
        Route::view('compiler', 'utils.compiler')->name('compiler');
        Route::view('python-compiler', 'utils.python-compiler')->name('python-compiler');
    });

    Route::get('check-soal/{id_materi}', function ($id_materi) {
        $materi = \App\Models\Materi::find($id_materi)->load('quiz');
        return response()->json($materi);
    });
});
