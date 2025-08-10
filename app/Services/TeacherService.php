<?php

namespace App\Services;

use App\Models\{Teacher, User, Lookup, Role};
use Illuminate\Validation\ValidationException;

class TeacherService
{
    public static function createTeacher(array $data): bool
    {
        $department = Lookup::where('type', 'Department')
            ->where('value', $data['department'])
            ->first();

        if (!$department) {
            throw ValidationException::withMessages(['department' => 'Invalid department selected.']);
        }

        $gender = Lookup::where('type', 'Gender')
            ->where('value', $data['gender'])
            ->first();

        $role = Role::where('name', 'Teacher')->first();

        $user = User::create([
            'Email' => $data['email'],
            'Password' => $data['password'],
            'RoleId' => $role->Id,
            'Name' => $data['name'],
            'GenderId' => $gender->Id,
        ]);

        $teacher = Teacher::create([
            'UserId' => $user->id,
            'DepartmentId' => $department->Id
        ]);

        return $user && $teacher;
    }

    public static function updateTeacher(int $id, array $data): bool
    {
        $department = Lookup::where('type', 'Department')
            ->where('value', $data['department'])
            ->first();

        if (!$department) {
            throw ValidationException::withMessages(['department' => 'Invalid department selected.']);
        }
        $genderId = Lookup::where('value', $data['gender'])->first();
        $teacher = Teacher::where('id', $id)->first();
        $userUpdated = $teacher->user->update([
            'Name' => $data['name'],
            'Gender' => $genderId
        ]);

        $teacherUpdated = $teacher->update([
            'DepartmentId' => $department->Id
        ]);

        return $userUpdated && $teacherUpdated;
    }

    public static function deleteTeacher(int $id): bool
    {
        $teacher = Teacher::findOrFail($id);
        $userDeleted = $teacher->user->delete();
        $teacherDeleted = $teacher->delete();

        return $userDeleted && $teacherDeleted;
    }

    public static function getTeacherByUserId(int $id){
        $teacher = Teacher::where('userId', $id)->first();
        return $teacher;
    }
}
