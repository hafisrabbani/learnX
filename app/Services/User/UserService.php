<?php

namespace App\Services\User;

use LaravelEasyRepository\BaseService;

interface UserService extends BaseService
{

    // Write something awesome :)

    public function login($data, $type = 'web');
    public function logout();
}
