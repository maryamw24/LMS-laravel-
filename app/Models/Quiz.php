<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use SoftDeletes; 
    
    protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';

    protected $table = 'quiz';
    protected $fillable = [
        'Title',
        'TeacherSubjectId',
        'Duration',
        'Date',
        'Start_Time',
        'End_Time',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($option) {
            $userId = session('user_id');
            $actionType = Lookup::where('type', 'Action_Type')
                                ->where('value', 'Delete')
                                ->first();
            $option->Deleted_By = $userId;
            $option->Action_Type_Id = $actionType?->Id;
            $option->save();
        });

    }

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class, 'TeacherSubjectId');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'Quiz_Id');
    }
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'Quiz_Id');
    }

}
