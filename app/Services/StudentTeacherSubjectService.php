<?php

namespace App\Services;

use App\Models\{ StudentTeacherSubject, TeacherSubject, Subject};
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentTeacherSubjectService
{

    public static function getUnassignedSubjects(int $studentId)
    {
        $assignedTeacherSubjectIds = StudentTeacherSubject::where('studentId', $studentId)
            ->pluck('teacherSubjectId')
            ->toArray();

        $unassignedTeacherSubjectIds = TeacherSubject::whereNotIn('id', $assignedTeacherSubjectIds)
            ->pluck('subjectId')
            ->unique()
            ->toArray();

        return Subject::whereIn('id', $unassignedTeacherSubjectIds)->get();
    }

    public static function assignSubject(int $studentId, int $teacherSubjectId): bool
    {
        return StudentTeacherSubject::create([
            'studentId' => $studentId,
            'teacherSubjectId' => $teacherSubjectId,
        ]) ? true : false;
    }

    public static function unAssignSubject(int $studentId, int $teacherSubjectId): bool
    {
        return StudentTeacherSubject::where('studentId', $studentId)
            ->where('TeacherSubjectId', $teacherSubjectId)
            ->delete() > 0;
    }

    public static function getStudentsByTeacherSubject($teacherSubjectId){
        return StudentTeacherSubject::where('TeacherSubjectId', $teacherSubjectId)->get();
    }

    public static function getTeacherSubjectsByStudent($studentId){
        return StudentTeacherSubject::where('StudentId', $studentId)->pluck('TeacherSubjectId')->toArray();
    }
}