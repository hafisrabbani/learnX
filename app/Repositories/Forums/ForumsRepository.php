<?php

namespace App\Repositories\Forums;

use LaravelEasyRepository\Repository;

interface ForumsRepository extends Repository
{

    public function getAllForums($role);
}
