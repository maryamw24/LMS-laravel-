<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAttempt extends Model
{
    use SoftDeletes;

    protected $table = 'quiz_attempts';

    protected $primaryKey = 'Id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'Quiz_Id',
        'Student_Id',
        'Time_Consumed',
        'Is_Submitted',
        'Started_At',
        'Ended_At',
        'Obtained_Marks',
        'Action_Type_Id',
        'Created_By',
        'Updated_By',
        'Deleted_By',
    ];

    protected $dates = [
        'Created_At',
        'Updated_At',
        'Deleted_At',
    ];

    const CREATED_AT = 'Created_At';
    const UPDATED_AT = 'Updated_At';
    const DELETED_AT = 'Deleted_At';

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'Quiz_Id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'Student_Id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'Created_By');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'Updated_By');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'Deleted_By');
    }
    
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'Quiz_Attempt_Id');
    }
}
