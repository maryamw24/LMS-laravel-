<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTeacherSubject extends Model
{

        protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';
    protected $table = 'Student_Teacher_Subject';

    public $timestamps = false;

    protected $fillable = [
        'teacherSubjectId',
        'studentId',
    ];

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class, 'TeacherSubjectId');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentId');
    }
}
