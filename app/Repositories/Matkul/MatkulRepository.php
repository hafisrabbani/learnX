<?php

namespace App\Repositories\Matkul;

use LaravelEasyRepository\Repository;

interface MatkulRepository extends Repository
{

    // Write something awesome :)
    function getByDosen($id);
    function getFromRole($role);
}
