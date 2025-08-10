<?php

namespace App\Http\Controllers;

use App\Models\{Student, Quiz, User, StudentTeacherSubject, TeacherSubject, Subject, Lookup};
use App\Services\StudentService;
use App\Services\QuizService;
use App\Services\QuizAttemptService;
use App\Services\QuizAnswerService;
use App\Services\StudentTeacherSubjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::all();
        $addSubjectStudent = null;
        $unassignedSubjects = [];

        if ($request->has('add_subject')) {
            $addSubjectStudent = Student::find($request->add_subject);
            if ($addSubjectStudent) {
                $assignedIds = $addSubjectStudent->subjects()->pluck('subjects.id')->toArray();
                $unassignedSubjects = Subject::with('teacher')
                    ->whereNotIn('id', $assignedIds)
                    ->get();
            }
        }

        return view('Pages.students', compact(
            'students',
            'addSubjectStudent',
            'unassignedSubjects'
        ));
    }

    public function dashboard(){
        return view('Pages.studentDashboard');
    }

    public function quizes(){
        $attempted = QuizService::getAttemptedQuizesForStudents();
        $upcoming = QuizService::getUpcomingQuizesForStudents();
        return view('Pages.studentQuizzes' ,compact(['upcoming', 'attempted']));
    }

    public function attemptQuiz($id)
    {
        $userId = session('user_id');
        $student = StudentService::getStudentByUserId($userId);
        $start_time = now();
        $quiz = Quiz::with(['questions.options', 'teacherSubject.subject'])->findOrFail($id);
        $attempt = QuizAttemptService::startQuiz($student->Id, $quiz->Id, $start_time);
        return view('Pages.attemptQuiz', compact('quiz', 'start_time', 'attempt'));
    }

    public function resumeQuiz($id)
    {
        $userId = session('user_id');
        $student = StudentService::getStudentByUserId($userId);
        $attempt = QuizAttemptService::getQuizAttemptByStudentAndQuiz($id, $student->Id);
        $quiz = Quiz::with(['questions.options', 'teacherSubject.subject'])->findOrFail($id);
        $answers = QuizAnswerService::getAnswersByAttempt($attempt->Id);    
        $timeConsumed = ($attempt->Time_Consumed)?? 0;   
        $remainingSeconds = $quiz->Duration - $timeConsumed;
        $start_time = now()->toIso8601String();
        $resumeIndex = count($answers);
        return view('Pages.attemptQuiz', compact('quiz', 'attempt', 'answers', 'remainingSeconds', 'start_time', 'resumeIndex'));
    }

    public function create()
    {
        $genders = Lookup::where('Type', 'Gender')->get();
        return view('Pages.addStudent', compact('genders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer|min:1',
            'gender' => 'required|string',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $success = StudentService::createStudent($validated);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if (!$success) {
            return back()->with('error', 'Failed to create student.')->withInput();
        }

        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $students = Student::all();
        $genders = Lookup::where('Type', 'Gender')->get();

        return view('Pages.students', [
            'students' => $students,
            'student' => $student,
            'editing' => true,
            'genders' => $genders,
        ]);
    }

    public function editCredentails($id)
    {
        $student = Student::findOrFail($id);
        $students = Student::all();
        $user = User::findOrFail($student->UserId);
        return view('Pages.students', [
            'students' => $students,
            'user' => $user,
            'editingCreds' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'age' => 'required|integer|min:1',
            'gender' => 'required|string',
        ]);

        try {
            $success = StudentService::updateStudent($id, $validated);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if (!$success) {
            return back()->with('error', 'Failed to update student.')->withInput();
        }

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $success = StudentService::deleteStudent($id);

        if (!$success) {
            return back()->with('error', 'Failed to delete student.');
        }

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function viewStudentSubjects(Request $request, $studentId)
    {
    $viewingunassignedSubjects = false;
    $unassignedSubjects = null;
    $viewStudentSubjects = StudentTeacherSubject::where('studentId', $studentId)->get();

    if($request->has('student')){
        $teachers = null;
        if($request->has('getUnAssignedSubjects')){
            $unassignedSubjects = StudentTeacherSubjectService::getUnassignedSubjects($studentId);
            if ($request->has('subjectId')) {
                $teachers = TeacherSubject::where('SubjectId', $request->subjectId)->get();
            }
            $viewingunassignedSubjects = true;
        }
        return view('Pages.studentSubjects', [
                'teachers' =>$teachers,
                'viewStudentSubjects' => $viewStudentSubjects,
                'unassignedSubjects' => $unassignedSubjects,
                'viewingunassignedSubjects' => $viewingunassignedSubjects,
            ]);
    }

    return view('Pages.students', [
        'students' => Student::all(),
        'viewStudentSubjects' => $viewStudentSubjects,
        'viewingStudentSubjects' => true,
        'assigningStudent' => Student::find($studentId),
    ]);
}

    public function viewUnAssignedSubjects(Request $request, $studentId)
    {
        $students = Student::all();
        $unassignedSubjects = StudentTeacherSubjectService::getUnassignedSubjects($studentId);
        $teachers = null;

        if ($request->has('subjectId')) {
            $teachers = TeacherSubject::where('SubjectId', $request->subjectId)->get();
        }
        
        return view('Pages.students', [
            'students' => $students,
            'unassignedSubjects' => $unassignedSubjects,
            'viewingunassignedSubjects' => true,
            'assigningStudent' => Student::find($studentId),
            'teachers' => $teachers,
            'selectedSubjectId' => $request->subjectId ?? null,
        ]);
    }

    public function assignSubject(Request $request)
    {
        $validated = $request->validate([
            'studentId' => 'required|int',
            'teacherSubjectId' => 'required|int',
        ]);

        $success = StudentTeacherSubjectService::assignSubject($validated['studentId'], $validated['teacherSubjectId']);

        if (!$success) {
            return back()->with('error', 'Failed to assign subject.');
        }
        if($request->portalName)
        {
            $student = true;
            return redirect()->route('users.view_my_subjects', [
                'studentId' => $validated['studentId'],
                'student' => true
            ]);
        }

        return redirect()->route('students.view_unassigned_subjects', $validated['studentId'])
            ->with('success', 'Subject assigned successfully.');
    }

    public function unAssignSubject($studentId, $teacherSubjectId, $portalName)
    {
        $success = StudentTeacherSubjectService::unAssignSubject($studentId, $teacherSubjectId);

        if (!$success) {
            return back()->with('error', 'Failed to unassign subject.');
        }
        if($portalName == "student")
                {
                    $student = true;
                    return redirect()->route('users.view_my_subjects', [
                        'studentId' => $studentId,
                        'student' => true
                    ]);
                }

        return redirect()->route('students.view_student_subjects', $studentId)
            ->with('success', 'Subject unassigned successfully.');
    }
}
