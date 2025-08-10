<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAnswer extends Model
{   
    use SoftDeletes;

    protected $table = 'quiz_answers';

    protected $fillable = [
        'Quiz_Attempt_Id',
        'Question_Id',
        'Selected_Option_Id',
        'IsCorrect',
        'Action_Type_Id',
        'Created_By',
        'Updated_By',
        'Deleted_By',
    ];


    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'Quiz_Attempt_Id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'Question_Id');
    }

    public function selectedOption()
    {
        return $this->belongsTo(Option::class, 'Selected_Option_Id');
    }
}
