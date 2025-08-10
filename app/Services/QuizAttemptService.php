<?php

namespace App\Services;

use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\Question;
use Carbon\Carbon;

class QuizAttemptService
{
    public static function startQuiz($id, $quiz_id, $start_time){
     try {
            $attempt = QuizAttempt::create([
                'Quiz_Id' => $quiz_id,
                'Student_Id' => $id,
                'Time_Consumed' => 0,
                'Started_At' => $start_time,
                'Created_At' => now(),
                'Created_By' => $id,
            ]);
            return $attempt;
        }   
        catch (Exception $e) {

            return ['success' => false, 'message' => 'Something went wrong while submitting the quiz.'];
        }
    }

    public static function saveAnswer($timeConsumed, $startTime, $attemptId, $questionId, $selectedOptionId, $type){
        try {
            $attempt = QuizAttempt::findOrFail($attemptId);
            $question = Question::with('options')->findOrFail($questionId);
            $studentId = session('student_id') ?? session('user_id');

            $isCorrect = $question->options->where('Id', $selectedOptionId)->first()?->Is_Correct ?? false;

            $existingAnswer = QuizAnswer::where('Quiz_Attempt_Id', $attemptId)
                ->where('Question_Id', $questionId)
                ->first();
            if ($existingAnswer) {
                $existingAnswer->update([
                    'Selected_Option_Id' => $selectedOptionId,
                    'IsCorrect' => $isCorrect,
                    'Updated_By' => $studentId,
                    'Updated_At' => now(),
                ]);
            } else {
                QuizAnswer::create([
                    'Quiz_Attempt_Id' => $attemptId,
                    'Question_Id' => $questionId,
                    'Selected_Option_Id' => $selectedOptionId,
                    'IsCorrect' => $isCorrect,
                    'Created_By' => $studentId,
                    'Created_At' => now(),
                ]);
            }
            $totalMarks = QuizAnswer::where('Quiz_Attempt_Id', $attemptId)
                ->where('IsCorrect', true)
                ->join('questions', 'quiz_answers.Question_Id', '=', 'questions.Id')
                ->sum('questions.Marks');

            $totalTime = $timeConsumed + $attempt->Time_Consumed;
            if($type === 'Add'){
                $attempt->update([
                    'Obtained_Marks' => $totalMarks,
                    'Updated_By' => $studentId,
                    'Updated_At' => now(),
                    'Time_Consumed' => $totalTime
                ]);
            }
            if($type === 'Submit'){
                $attempt->update([
                    'Obtained_Marks' => $totalMarks,
                    'Updated_By' => $studentId,
                    'Updated_At' => now(),
                    'Time_Consumed' => $totalTime,
                    'Is_Submitted' => 1,
                    'Ended_At' => now(),
                ]);
            }

            return ['success' => true];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to save answer.',
                'error' => $e->getMessage(),
            ];
        }
    }

    public static function getQuizAttempt($attemptId){
        $attempt = QuizAttempt::findOrFail($attemptId);
        return $attempt;
    }
    
    public static function getQuizAttemptByStudentAndQuiz($quizId, $studentId) {
        $attempt = QuizAttempt::where('Quiz_Id', $quizId)
                            ->where('Student_Id', $studentId)
                            ->latest()
                            ->first();
        return $attempt;
    }

    public static function autoSubmitExpiredQuizAttempts()
{
    $nowTime = Carbon::now()->format('H:i:s'); // current time only (e.g., "14:23:00")

    $attempts = QuizAttempt::where('Is_Submitted', 0)
        ->with('quiz') 
        ->get();

    foreach ($attempts as $attempt) {
        $quiz = $attempt->quiz;

        $quizEndTime = $quiz->End_Time; // assuming it's stored as "HH:MM:SS"

        // Compare time strings
        if ($nowTime > $quizEndTime) {
            $attempt->update([
                'Is_Submitted' => 1,
                'Ended_At' => Carbon::now() // still saving full timestamp
            ]);
        }
    }

    return $nowTime;
}



}
