<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
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

    public function updateProfile(Request $request)
    {
        $params = $request->all();
        $user = auth()->user();
        $password = $params['password'] ?? null;
        $newPasswordConfirmation = $params['new_password_confirmation'] ?? null;

        if (empty($password) && empty($newPasswordConfirmation)) {
            unset($params['password']);
        } else {
            if (empty($password) || empty($newPasswordConfirmation)) {
                return redirect()->back()->with('error', 'Vui lòng nhập đầy đủ mật khẩu.');
            }

            if (!Hash::check($password, $user->password)) {
                return redirect()->back()->with('error', 'Mật khẩu hiện tại không đúng.');
            }

            $params['password'] = Hash::make($newPasswordConfirmation);
        }

        $this->userService->update($user->id, $params);

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công');
    }
}
