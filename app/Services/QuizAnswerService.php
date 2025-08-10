<?php

namespace App\Services;

use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QuizAnswerService
{
    public static function getAnswersByAttempt($id){
     $answers = QuizAnswer::where('Quiz_Attempt_Id', $id)
        ->pluck('Selected_Option_Id', 'Question_Id')
        ->toArray();
        return $answers;
    }
}