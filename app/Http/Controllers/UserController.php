<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    )
    {
    }

    public function profile()
    {
        return view('common.profile');
    }

    public function updateProfile()
    {
        $params = request()->all();
        $user = auth()->user();

        if (!Hash::check($params['password'], $user->password)) {
          return redirect()->back()->with('error', 'Mật khẩu hiện tại không đúng');
        }

        if ($params['new_password_confirmation']) {
            $params['password'] = $params['new_password_confirmation'];
        }
//        dd($id, $params);
        $this->userService->update($user->id, $params);

        return redirect()->route('profile')->with('success', 'Cập nhật thông tin thành công');
    }
}
