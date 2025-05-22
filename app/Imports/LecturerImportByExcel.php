<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Lecturer;
use App\Models\Faculty;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Validation\ValidationException;

class LecturerImportByExcel implements ToCollection, WithStartRow, SkipsEmptyRows
{
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $collection)
    {
        DB::beginTransaction();

        try {
            $newEmails = [];
            $lecturerData = [];

            $faculties = Faculty::pluck('id', 'name')->toArray();

            foreach ($collection as $index => $row) {
                for ($i = 0; $i <= 7; $i++) {
                    if (!isset($row[$i]) || trim($row[$i]) === '') {
                        throw new \Exception('Thiếu dữ liệu tại dòng ' . ($index + 2));
                    }
                }

                $name = trim($row[0]);
                $gender = trim($row[1]);
                $date_of_birth = Carbon::parse(is_string($row[2]) ? trim($row[2]) : $row[2])->format('Y-m-d');
                $email = strtolower(trim($row[3]));
                $title = trim($row[4]);
                $facultyName = trim($row[5]);
                $position = trim($row[6]);
                $phone = trim($row[7]);

                if (!isset($faculties[$facultyName])) {
                    throw new \Exception('Không tìm thấy khoa tại dòng ' . ($index + 2));
                }

                $newEmails[] = $email;

                $user = User::withTrashed()->where('email', $email)->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make('12345678'),
                        'date_of_birth' => $date_of_birth,
                        'gender' => $gender,
                        'role' => '0',
                        'phone' => $phone,
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
                        'role' => '0',
                        'phone' => $phone,
                        'updated_at' => now(),
                    ]);
                }

                $lecturerData[] = [
                    'user_id' => $user->id,
                    'title' => $title,
                    'faculty_id' => $faculties[$facultyName],
                    'position' => $position,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $usersToDelete = User::where('role', '0')->whereNotIn('email', $newEmails)->pluck('id');

            Lecturer::whereIn('user_id', $usersToDelete)->delete();
            User::whereIn('id', $usersToDelete)->delete();

            foreach ($lecturerData as $item) {
                Lecturer::updateOrCreate(['user_id' => $item['user_id']], $item);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log::error($e);
            throw new \Exception('Import failed');
        }
    }
}
