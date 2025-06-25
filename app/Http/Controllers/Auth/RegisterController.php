<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    // protected $redirectTo = '/home';
    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->role == Constant::ROLE_LIST['TEACHER'])
            return route('teacher.index');
        else if ($user->role == Constant::ROLE_LIST['STUDENT_AFFAIRS_DEPARTMENT'])
            return route('student-affairs-department.index');
//        else if ($user->role == Constant::ROLE_LIST['CLASS_STAFF'])
//            return route('class-staff.index');
        else if ($user->role == Constant::ROLE_LIST['FACULTY_OFFICE'])
            return route('faculty-office.index');

        return route('student.index');
    }

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        $departmentEmails = [
            'vpkcntt@tlu.edu.vn',
            'khoak@tlu.edu.vn',
            'vpkhoacongtrinh@tlu.edu.vn',
            'vpkhoamt@tlu.edu.vn',
            'khoam@tlu.edu.vn',
            'sie@tlu.edu.vn',
        ];

        $ctsvEmail = 'p7@tlu.edu.vn';

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) use ($departmentEmails, $ctsvEmail) {
                    $email = strtolower($value);

                    $isStudent = preg_match('/^[0-9]{10}@e\.tlu\.edu\.vn$/', $email);
                    $isFacultyOffice = in_array($email, array_map('strtolower', $departmentEmails));
                    $isCTSV = $email === strtolower($ctsvEmail);
                    $isTeacher = str_ends_with($email, '@tlu.edu.vn') && !$isFacultyOffice && !$isCTSV;

                    if (!($isStudent || $isFacultyOffice || $isCTSV || $isTeacher)) {
                        $fail('Email không hợp lệ. Vui lòng dùng email Trường Đại học Thủy Lợi cung cấp.');
                    }
                }
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $email = $data['email'];
        $role = Constant::ROLE_LIST['STUDENT'];

        $departmentEmails = [
            'vpkcntt@tlu.edu.vn',
            'KhoaK@tlu.edu.vn',
            'vpkhoacongtrinh@tlu.edu.vn',
            'vpkhoamt@tlu.edu.vn',
            'KhoaM@tlu.edu.vn',
            'sie@tlu.edu.vn',
        ];

        $ctsv = 'p7@tlu.edu.vn';

        if (in_array(strtolower($email), array_map('strtolower', $departmentEmails))) {
            $role = Constant::ROLE_LIST['FACULTY_OFFICE'];
        } elseif (preg_match('/^[0-9]{10}@e\.tlu\.edu\.vn$/', $email)) {
            $role = Constant::ROLE_LIST['STUDENT'];
        } elseif ($email === $ctsv) {
            $role = Constant::ROLE_LIST['STUDENT_AFFAIRS_DEPARTMENT'];
        } elseif (str_ends_with($email, '@tlu.edu.vn')) {
            $role = Constant::ROLE_LIST['TEACHER'];
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $email,
            'password' => Hash::make($data['password']),
            'role' => $role,
        ]);

//        if ($user->role === Constant::ROLE_LIST['STUDENT']) {
//            $user->student()->create();
//        } elseif ($user->role === Constant::ROLE_LIST['TEACHER']) {
//            $user->lecturer()->create();
//        } elseif ($user->role === Constant::ROLE_LIST['FACULTY_OFFICE']) {
//            $user->facultyOffice()->create();
//        }
        \Log::info('User created', ['user' => $user]);
        return $user;
    }
}
