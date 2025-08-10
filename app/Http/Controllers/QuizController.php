<?php

namespace App\Http\Controllers;
use App\Services\QuestionService;
use App\Services\TeacherService;
use App\Models\TeacherSubject;
use App\Services\QuizService;
use App\Services\QuizAttemptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class QuizController extends Controller
{
    public function index()
    {
        $attemptedQuizzes = QuizService::getAttemptedQuizes();
        $newQuizzes = QuizService::getNewQuizes();
        return view('Pages.quizes', compact(['newQuizzes', 'attemptedQuizzes']));
    }

    public function create()
    {
        $userId = session('user_id');
        $teacher= TeacherService::getTeacherByUserId($userId);
        $subjects = TeacherSubject::where('TeacherId', $teacher->Id)->get();
        return view('Pages.createQuiz', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'subject_id' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        try {
            $success = QuizService::createQuiz($validated);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if (!$success) {
            return back()->with('error', 'Failed to create quiz.')->withInput();
        }

        return redirect()->route('quiz.index')->with('success', 'Quiz created successfully.');
    }

    public function edit($id)
    {
        $userId = session('user_id');
        $teacher= TeacherService::getTeacherByUserId($userId);
        $subjects = TeacherSubject::where('TeacherId', $teacher->Id)->get();
        $quiz = QuizService::getQuizById($id);
        return view('Pages.updateQuiz', compact(['quiz','subjects']));
    }

    public function addQuestion($id){
        $userId = session('user_id');
        $teacher= TeacherService::getTeacherByUserId($userId);
        $subjects = TeacherSubject::where('TeacherId', $teacher->Id)->get();
        $quiz = QuizService::getQuizById($id);
        return view('Pages.addQuestionsToQuiz', compact(['quiz','subjects']));
    }

    public function addQuestionToQuiz(Request $request){
        $validated = $request->validate([
            'quiz_id' => 'required',
            'question_text' => 'required',
            'marks' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'correct_option' => 'required'
        ]);
        try {
            $success = QuestionService::createQuestion($validated);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if (!$success) {
            return back()->with('error', 'Failed to add question.')->withInput();
        }

        return redirect()->route('quiz.index')->with('success', 'Question added successfully.');
    }

    public function previewQuiz($id)
    {
        
        $quiz = QuizService::getQuizById($id);
        return view('Pages.previewQuiz', compact('quiz'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'subject_id' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        try {
            $success = QuizService::updateQuiz($validated, $id);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if (!$success) {
            return back()->with('error', 'Failed to update quiz.')->withInput();
        }

        return redirect()->route('quiz.edit',$id )->with('success', 'Quiz updated successfully.');
    }
    
    public function updateQuestion(Request $request, $id){
        $validated = $request->validate([
            'quiz_id'=> 'required',
            'question_text' => 'required',
            'marks' => 'required',
            'options' => 'required|array',
            'correct_option' => 'required'
        ]);
        try {
            $success = QuestionService::updateQuestion($validated, $id);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if (!$success) {
            return back()->with('error', 'Failed to update question.')->withInput();
        }

        return redirect()->route('quiz.edit', $validated['quiz_id'])->with('success', 'Question updated successfully.');

    }
    
    public function removeQuestion($quiz_Id, $questionId)
    {
        $success = QuestionService::removeQuestion($questionId);
        if (!$success) {
            return back()->with('error', 'Failed to delete question.');
        }

        return redirect()->route('quiz.edit', $quiz_id)->with('success', 'Question deleted successfully.');
    }

    public function destroy($id)
    {
        $success = QuizService::deleteQuiz($id);
        if (!$success) {
            return back()->with('error', 'Failed to delete quiz.');
        }

        return redirect()->route('quiz.index')->with('success', 'Quiz deleted successfully.');
    }

    public function result($id)
    {
        $quiz = QuizService::getQuizById($id);
        $quiz->Total_Marks = $quiz->questions->sum('Marks');
        return view('Pages.quizResult', compact('quiz'));
    }

    public function viewAttempt($attemptId)
    {
        $attempt = QuizAttemptService::getQuizAttempt($attemptId);
        return view('Pages.viewAttemptedQuiz', compact('attempt'));
    }


}
