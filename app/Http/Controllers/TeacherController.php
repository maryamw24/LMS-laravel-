<?php

namespace App\Http\Controllers;

use App\Models\{Teacher, Lookup, TeacherSubject};
use Illuminate\Http\Request;
use App\Services\TeacherService;
use App\Services\TeacherSubjectService;
use App\Services\StudentTeacherSubjectService;

use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return view('Pages.teachers', compact('teachers'));
    }

    public function create()
    {
        $genders = Lookup::where('type', 'Gender')->get();
        $departments = Lookup::where('Type', 'Department')->get();
        return view('Pages.addTeacher', compact(['departments', 'genders']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'department' => 'required|string',
            'gender' => 'required|string',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $success = TeacherService::createTeacher($validated);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if (!$success) {
            return back()->with('error', 'Failed to add teacher.')->withInput();
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher added successfully.');
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teachers = Teacher::all();
        $departments = Lookup::where('Type', 'Department')->get();
        $genders = Lookup::where('Type', 'Gender')->get();
        return view('Pages.teachers', [
            'teachers' => $teachers,
            'editTeacher' => $teacher,
            'editing' => true,
            'departments' => $departments,
            'genders' => $genders,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'department' => 'required',
            'gender' => 'required',
        ]);

        try {
            $success = TeacherService::updateTeacher($id, $validated);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if (!$success) {
            return back()->with('error', 'Failed to update teacher.')->withInput();
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy($id)
    {
        $success = TeacherService::deleteTeacher($id);

        if (!$success) {
            return back()->with('error', 'Failed to delete teacher.');
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }

    public function getSubjects($id)
    {
        $teacher = TeacherService::getTeacherByUserId($id);
        $subjects = TeacherSubjectService::getSubjectByTeacher($teacher->Id);
        $subjects = TeacherSubject::withCount('studentTeacherSubjects')
        ->where('teacherId', $teacher->Id)
        ->get();

        return view('Pages.teacherSubjects', compact('subjects'));
    }

    public function getStudentsByTeacherSubject($teacherId, $subjectId){
        $teacherSubjectId = TeacherSubjectService::getId($teacherId, $subjectId);
        $students = StudentTeacherSubjectService::getStudentsByTeacherSubject($teacherSubjectId);
        return view('Pages.subjectStudents', compact('students'));
    }
}
