<?php

namespace App\Repositories\ForumThreads;

use LaravelEasyRepository\Repository;

interface ForumThreadsRepository extends Repository
{

    public function reply($data);
}
