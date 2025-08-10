<?php

namespace App\Services;

use App\Models\TeacherSubject;
use Illuminate\Validation\ValidationException;

class TeacherSubjectService
{

    public static function getSubjectByTeacher($teacherId){
        $subjects = TeacherSubject::where('teacherId', $teacherId)->get();
        return $subjects;
    }

    

    public static function getId($teacherId, $subjectId){
        $id = TeacherSubject::where('teacherId', $teacherId)
                ->where('subjectId', $subjectId)->pluck('Id')->first();
        return $id;
    }

}