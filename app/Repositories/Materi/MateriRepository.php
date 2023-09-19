<?php

namespace App\Repositories\Materi;

use LaravelEasyRepository\Repository;

interface MateriRepository extends Repository
{

    public function getByMatkul($id);
    public function where(array $data);
    public function storeFeedback(array $data);
    public function whereFeedback($materi_id);
    public function updateFeedback(array $data);
}
