<?php

namespace App\Http\Controllers;

use App\Services\QuizAttemptService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use App\Models\{ Quiz, QuizAttempt, Option, QuizAnswer, Question};
use Illuminate\Validation\ValidationException;

class QuizAttemptController extends Controller
{
    public function submitQuiz(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'start_time' => 'required',
                'attempt_id' => 'required|integer',
                'question_id' => 'required|integer',
                'selected_option_id' => 'required|integer',
                'time_consumed' => 'required|integer',
                'answers' => 'required'
            ]);
            $userId = session('user_id');
            $student = StudentService::getStudentByUserId($userId);
            $answers = $validated['answers'];
            $result = QuizAttemptService::saveAnswer(
                $validated['time_consumed'],
                $validated['start_time'],
                $validated['attempt_id'],
                $validated['question_id'],
                $validated['selected_option_id'],
                'Submit'
                
            );
        }
        catch (\Exception $e) {
            \Log::error('Submission failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return redirect()->route('students.quiz_result', ['id' => $request->quiz_id]);
    }   

    public function saveAnswer(Request $request)
    {
         try {
            $validated = $request->validate([
                'start_time' => 'required|date',
                'attempt_id' => 'required|integer',
                'question_id' => 'required|integer',
                'selected_option_id' => 'required|integer',
                'time_consumed' => 'required|integer',
            ]);

            $result = QuizAttemptService::saveAnswer(
                $validated['time_consumed'],
                $validated['start_time'],
                $validated['attempt_id'],
                $validated['question_id'],
                $validated['selected_option_id'],
                'Add'
            );

            $quizId = QuizAttempt::find($validated['attempt_id'])->Quiz_Id;
            return response()->json(['success' => true, 'result' => $result]);

        } catch (\Exception $e) {
            \Log::error('Save answer failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showResult($id)
    {
        $userId = session('user_id');
        $student = StudentService::getStudentByUserId($userId);
        $attempt = QuizAttempt::where('Quiz_Id', $id)
                    ->where('Student_Id', $student->Id)
                    ->latest()
                    ->firstOrFail();

        $timeTaken = \Carbon\Carbon::parse($attempt->Started_At)->diffInMinutes($attempt->Ended_At);
        $attempt->Total_Marks = $attempt->quiz->questions->sum('Marks');
        $attempt->Time_Taken = $timeTaken;

        return view('Pages.showResult', compact('attempt'));
    }
}
