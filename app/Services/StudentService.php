<?php

namespace App\Services;

use App\Models\{Student, User, StudentTeacherSubject, TeacherSubject, Subject, Role, Lookup};
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentService
{
    public static function createStudent(array $data): bool
    {
        $gender = Lookup::where('type', 'Gender')
            ->where('value', $data['gender'])
            ->first();

        if (!$gender) {
            throw ValidationException::withMessages(['gender' => 'Invalid gender selected.']);
        }

        $role = Role::where('name', 'Student')->first();

        $user = User::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'roleId' => $role->Id,
            'name' => $data['name'],
            'genderId' => $gender->Id,
        ]);

        $student = Student::create([
            'userId' => $user->id,
            'age' => $data['age'],
        ]);

        return $user && $student;
    }

    public static function updateStudent(int $id, array $data): bool
    {
        $gender = Lookup::where('type', 'Gender')
            ->where('value', $data['gender'])
            ->first();

        if (!$gender) {
            throw ValidationException::withMessages(['gender' => 'Invalid gender selected.']);
        }

        $student = Student::findOrFail($id);
        $studentUpdated = $student->update(['age' => $data['age']]);

        $userUpdated = $student->user->update([
            'name' => $data['name'],
            'genderId' => $gender->Id,
        ]);

        return $studentUpdated && $userUpdated;
    }

    public static function deleteStudent(int $id): bool
    {
        $student = Student::findOrFail($id);
        $userDeleted = $student->user->delete();
        $studentDeleted = $student->delete();

        return $userDeleted && $studentDeleted;
    }


    public static function getStudentByUserId($id){
        $student = Student::where('UserId', $id)->first();
        return $student;
    }

}
