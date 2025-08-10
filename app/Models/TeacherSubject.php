<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{

    protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';
    protected $table = 'Teacher_Subject';

    public $timestamps = false;

    protected $fillable = [
        'teacherId',
        'subjectId',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'TeacherId');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'SubjectId');
    }

    public function studentTeacherSubjects()
    {
        return $this->hasMany(StudentTeacherSubject::class, 'teacherSubjectId');
    }

    public function studentCount()
    {
        return $this->studentTeacherSubjects()->count();
    }
}
