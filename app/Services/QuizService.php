<?php

namespace App\Services;

use App\Models\{ Quiz, StudentTeacherSubject, TeacherSubject, Subject, Lookup};
use Carbon\Carbon;
use App\Services\TeacherService;
use App\Services\StudentService;
use Illuminate\Validation\ValidationException;

class QuizService
{
    public static function createQuiz($data){
        $userId = session('user_id');
        $Action_Type_Id = Lookup::where('type', 'Action_Type')
        ->where('value', 'Create')
        ->first();
        $quiz = Quiz::create([
            'Created_By' => $userId,
            'TeacherSubjectId' => $data['subject_id'],
            'Duration' => ($data['duration']*60),
            'Date' => $data['date'],
            'Start_Time' => $data['start_time'],
            'End_Time' => $data['end_time'],
            'Title' => $data['title'],
            'Action_Type_Id' => $Action_Type_Id,
        ]);
        return $quiz;
    }
    
    public static function updateQuiz($data, $id){

        $userId = session('user_id');
        $ActionType = Lookup::where('type', 'Action_Type')
            ->where('value', 'Update')
            ->first();

        $quiz = Quiz::findOrFail($id);
        $quizUpdated = $quiz->update([
            'TeacherSubjectId' => $data['subject_id'],
            'Date' => $data['date'],
            'Duration' => $data['duration'],
            'Start_Time' => $data['start_time'],
            'End_Time' => $data['end_time'],
            'Title' => $data['title'],
            'Action_Type_Id' => $ActionType->Id,
            'Update_By' =>  $userId
            
        ]);
        return $quizUpdated;
    
    }

    public static function deleteQuiz($id){
        $quiz = Quiz::findOrFail($id);
        foreach ($quiz->questions as $question) {
            foreach ($question->options as $option) {
                $option->delete();
            }
            $question->delete();
        }
        $quiz->delete();
    }

    public static function getNewQuizes()
    {
        $userId = session('user_id');
        $teacher = TeacherService::getTeacherByUserId($userId);
        $teacherSubjectIds = TeacherSubject::where('TeacherId', $teacher->Id)->pluck('Id');
        $now = Carbon::now(); 

        $quizzes = Quiz::whereIn('TeacherSubjectId', $teacherSubjectIds)
            ->where(function ($query) use ($now) {
                $query->where('Date', '>', $now->toDateString()) 
                    ->orWhere(function ($q) use ($now) {
                        $q->where('Date', '=', $now->toDateString()) 
                            ->whereTime('Start_Time', '>', $now->toTimeString()); 
                    });
            })
            ->with('teacherSubject.subject')
            ->paginate(2);

        return $quizzes;
    }
     
    public static function getAttemptedQuizes(){
        $userId = session('user_id');
        $teacher = TeacherService::getTeacherByUserId($userId);
        $teacherSubjectIds = TeacherSubject::where('TeacherId', $teacher->Id)->pluck('Id');
        $now = Carbon::now();
        $quizzes = Quiz::whereIn('TeacherSubjectId', $teacherSubjectIds)
            ->where(function ($query) use ($now) {
                $query->where('Date', '<', $now->toDateString()) 
                    ->orWhere(function ($q) use ($now) {
                        $q->where('Date', '=', $now->toDateString()) 
                            ->whereTime('Start_Time', '<', $now->toTimeString()); 
                    });
            })
            ->with('teacherSubject.subject')
            ->paginate(2);

        return $quizzes;
    }

    public static function getQuizById($id){
        return  $quiz = Quiz::with(['questions.options'])->find($id);

    }


  public static function getUpcomingQuizesForStudents(){
        $userId = session('user_id');
        $student = StudentService::getStudentByUserId($userId);
        $subjects = StudentTeacherSubjectService::getTeacherSubjectsByStudent($student->Id);
        $now = Carbon::now();
        $quizzes = Quiz::whereIn('TeacherSubjectId', $subjects)
        ->where(function ($query) use ($now) {
            $query->where('Date', '>', $now->toDateString())
                ->orWhere(function ($q) use ($now) {
                    $q->where('Date', '=', $now->toDateString())
                        ->whereTime('End_Time', '>', $now->toTimeString());
                });
        })
        ->with('teacherSubject.subject')
        ->get();
            foreach ($quizzes as $quiz) {
                $quizStart = Carbon::parse($quiz->Date . ' ' . $quiz->Start_Time);
                $quizEnd = Carbon::parse($quiz->Date . ' ' . $quiz->End_Time);
                $quiz->canStart = $now >= $quizStart && $now <= $quizEnd;
                $attempt = QuizAttemptService::getQuizAttemptByStudentAndQuiz($quiz->Id, $student->Id);
                $quiz->isStarted = $attempt !== null;
            }

        $filtered = $quizzes->filter(function ($quiz) use ($student) {
            $attempt = QuizAttemptService::getQuizAttemptByStudentAndQuiz($quiz->Id, $student->Id);
            return !$attempt || !$attempt->Is_Submitted;
        });

    return $filtered->values(); 
    }

   public static function getAttemptedQuizesForStudents()
{
    $userId = session('user_id');
    $student = StudentService::getStudentByUserId($userId);
    $subjects = StudentTeacherSubjectService::getTeacherSubjectsByStudent($student->Id);
    $now = Carbon::now();

    $quizzes = Quiz::whereIn('TeacherSubjectId', $subjects)
        ->where(function ($query) use ($now) {
            $query->where('Date', '<', $now->toDateString())
                ->orWhere(function ($q) use ($now) {
                    $q->where('Date', '=', $now->toDateString())
                        ->whereTime('End_Time', '<', $now->toTimeString());
                });
        })
        ->with([
            'teacherSubject.subject',
            'attempts' => function ($query) use ($student) {
                $query->where('Student_Id', $student->Id);
            }
        ])
        ->get();

    $filteredQuizzes = $quizzes->filter(function ($quiz) {
        $attempt = $quiz->attempts->first();
        return !$attempt || $attempt->Is_Submitted;
    });

    return $filteredQuizzes->values(); 
}



}