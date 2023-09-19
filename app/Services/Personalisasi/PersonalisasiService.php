<?php

namespace App\Services\Personalisasi;

use LaravelEasyRepository\BaseService;

interface PersonalisasiService extends BaseService
{
    public function getAnalytic($id_analyzer, $id_user);
    public function createQuizFromModule($id_module);
    public function personalisasi($id_user);
}
