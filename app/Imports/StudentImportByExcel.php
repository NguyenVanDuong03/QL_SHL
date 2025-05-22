<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use App\Models\Cohort;
use App\Models\StudyClass;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentImportByExcel implements ToCollection, WithStartRow, SkipsEmptyRows
{
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $collection)
    {
        DB::beginTransaction();

        try {
            $emails = [];
            $studentData = [];

            $cohorts = Cohort::pluck('id', 'name')->toArray();
            $studyClasses = StudyClass::pluck('id', 'name')->toArray();

            foreach ($collection as $index => $row) {
                for ($i = 0; $i <= 6; $i++) {
                    if (!isset($row[$i]) || trim($row[$i]) === '') {
                        throw new \Exception("Thiếu dữ liệu tại dòng " . ($index + 2));
                    }
                }

                $student_code = trim($row[0]);
                $name = trim($row[1]);
                $gender = trim($row[2]);
                $date_of_birth = Carbon::parse($row[3])->format('Y-m-d');
                $email = strtolower(trim($row[4]));
                $cohortName = trim($row[5]);
                $className = trim($row[6]);

                if (!isset($cohorts[$cohortName]) || !isset($studyClasses[$className])) {
                    throw new \Exception("Không tìm thấy khoá học hoặc lớp tại dòng " . ($index + 2));
                }

                $emails[] = $email;

                // Tìm user theo email (kể cả bị soft-delete)
                $user = User::withTrashed()->where('email', $email)->first();

                if (!$user) {
                    // Tạo user
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make('12345678'),
                        'date_of_birth' => $date_of_birth,
                        'gender' => $gender,
                        'role' => '3',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    if ($user->trashed()) {
                        $user->restore();
                    }

                    $user->update([
                        'name' => $name,
                        'date_of_birth' => $date_of_birth,
                        'gender' => $gender,
                        'role' => '3',
                        'updated_at' => now(),
                    ]);
                }

                // Tìm student theo user_id (kể cả soft-delete)
                $student = Student::withTrashed()->where('user_id', $user->id)->first();

                if ($student && $student->trashed()) {
                    $student->restore();
                }

                $studentData[] = [
                    'user_id' => $user->id,
                    'student_code' => $student_code,
                    'cohort_id' => $cohorts[$cohortName],
                    'study_class_id' => $studyClasses[$className],
                    'position' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Xử lý xoá mềm những user không có trong danh sách email
            $usersToDelete = User::where('role', '3')
                ->whereNotIn('email', $emails)
                ->get();

            foreach ($usersToDelete as $user) {
                $student = Student::where('user_id', $user->id)->first();

                if ($student) {
                    if (in_array($student->position, [1, 2, 3])) {
                        $student->update(['position' => 0]);
                    }
                    $student->delete();
                }

                $user->delete(); // soft delete user
            }

            // Cập nhật hoặc tạo mới student
            foreach ($studentData as $data) {
                Student::updateOrCreate(
                    ['user_id' => $data['user_id']],
                    $data
                );
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
            throw new \Exception('Import failed');
        }
    }
}
