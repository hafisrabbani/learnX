<?php

namespace App\Services\User;

use LaravelEasyRepository\Service;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;


class UserServiceImplement extends Service implements UserService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(UserRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function login($data, $type = 'web')
  {
    $credentials = [
      'email' => $data['email'],
      'password' => $data['password'],
    ];
    if ($type == 'api') {
      $token = Auth::guard('api')->attempt($credentials);
      return $token;
    }

    if (Auth::attempt($credentials)) {
      return true;
    }

    return false;
  }

  public function logout()
  {
    Auth::logout();
  }
}
