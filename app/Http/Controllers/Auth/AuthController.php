<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Repositories\User\UserRepository;
use App\Services\User\UserService;

class AuthController extends Controller
{
    protected $userRepository, $userService;
    public function __construct(UserRepository $userRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        if (!$data = $request->validated()) {
            return redirect()->back()->with('error', $request->errors());
        }
        if (!$this->userService->login($data)) {
            session()->flash('error', 'Email or password is wrong');
            return redirect()->back();
        }
        return redirect()->to(self::$REDIRECTAFTERLOGIN);
    }

    public function loginApi(LoginRequest $request)
    {
        $data = $request->validated();
        if ($attempt = $this->userService->login($data, 'api')) {
            return response()->json([
                'status' => 'success',
                'message' => 'Login success',
                'data' => [
                    'token' => $attempt
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email or password is wrong',
        ], 401);
    }

    public function index()
    {
        return view('auth.login');
    }

    public function logout()
    {
        $this->userService->logout();
        return redirect()->route('login.index');
    }
}
