<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuizController;
use App\Http\Middleware\EnsureUserLoggedIn;
use App\Http\Controllers\QuizAttemptController;

    Route::get('/', function () {
    return view('auth/login');});
    Route::post('/users/verify', [UserController::class, 'verify'])->name('users.verify');

Route::middleware([EnsureUserLoggedIn::class])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('students.dashboard');

    Route::get('/student/quizes', [StudentController::class, 'quizes'])->name('students.quizzes');
    Route::get('/student/attempt-quiz/{id}', [StudentController::class, 'attemptQuiz'])->name('students.attempt_quiz');
    Route::get('/student/resume-quiz/{id}', [StudentController::class, 'resumeQuiz'])->name('students.resume_quiz');
    Route::post('/student/submit', [QuizAttemptController::class, 'submitQuiz'])->name('students.submit_quiz');
    Route::get('/student/quiz-result/{id}', [QuizAttemptController::class, 'showResult'])->name('students.quiz_result');
    Route::post('/student/save-answer', [QuizAttemptController::class, 'saveAnswer'])->name('students.save_answer');

    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}', [StudentController::class, 'edit'])->name('students.edit');
    Route::get('/students/edit-credentials/{id}', [StudentController::class, 'editCredentails'])->name('students.edit_creds');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::get('/students/view-student-subjects/{id}', [StudentController::class, 'viewStudentSubjects'])->name('students.view_student_subjects');
    Route::get('/students/view-unassigned-subjects/{id}', [StudentController::class, 'viewUnAssignedSubjects'])->name('students.view_unassigned_subjects');
    Route::post('/students/assign-subject', [StudentController::class, 'assignSubject'])->name('students.assign_subject');
    Route::delete('/students/unassign-subject/{studentId}/{teacherSubjectId}/{portalName}', [StudentController::class, 'unAssignSubject'])->name('students.unassign_subject');

    Route::put('/users/{id}', [UserController::class, 'updateCredentials'])->name('users.update_credentials');

    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{id}', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    Route::get('/quizes', [QuizController::class, 'index'])->name('quiz.index');
    Route::get('/quizes/result/{id}', [QuizController::class, 'result'])->name('quiz.result');
    Route::get('/quizes/attempt/{id}', [QuizController::class, 'viewAttempt'])->name('quiz.attempt');
    Route::get('/quizes/create/', [QuizController::class, 'create'])->name('quiz.create');
    Route::post('/quizes', [QuizController::class, 'store'])->name('quiz.store');
    Route::get('/quizes/{id}', [QuizController::class, 'edit'])->name('quiz.edit');
    Route::put('/quizes/{id}', [QuizController::class, 'update'])->name('quiz.update');
    Route::get('/quizes/{id}/add-question/', [QuizController::class, 'addQuestion'])->name('quiz.add_question');
    Route::delete('/quizes/{id}/remove-question/{questionId}', [QuizController::class, 'removeQuestion'])->name('quiz.remove_question');
    Route::post('/quizes/add-question/', [QuizController::class, 'addQuestionToQuiz'])->name('quiz.add_question_to_quiz');
    Route::put('/quizes/{id}/update-question', [QuizController::class, 'updateQuestion'])->name('quiz.update_question');
    Route::get('/quizes/preview-quiz/{id}', [QuizController::class, 'previewQuiz'])->name('quiz.preview_quiz');
    Route::delete('/quizes/{id}', [QuizController::class, 'destroy'])->name('quiz.destroy');

    Route::get('/subjects/{teacherId}', [TeacherController::class, 'getSubjects'])->name('teachers.getSubjects');
    Route::get('/subject-students/{teacherId}/{subjectId}', [TeacherController::class, 'getStudentsByTeacherSubject'])->name('teachers.getSubjectStudents');
    Route::get('/my-subjects/{studentId}', [StudentController::class, 'viewStudentSubjects'])->name('users.view_my_subjects');
});

Route::get('/logout', [UserController::class, 'logout'])->name('users.logout');
   
    