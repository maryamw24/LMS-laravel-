@extends('layout.app')

@section('title', 'My Subjects ')

@section('content')

<div class="max-w-6xl text-black mx-auto bg-white p-8 rounded shadow">
    <div class="md:col-span-1 flex justify-end">
        @php
            $id = session('student_id'); 
            $role = session('user_role'); 
        @endphp
            <a href="{{ route('users.view_my_subjects',[ $id, 'student' => true, 'getUnAssignedSubjects' =>true]) }}">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Enroll Subject
                </button>
            </a>
        </div>
<h2 class="text-xl font-bold mb-4">Subjects List</h2>
        <table class="w-full border border-gray-300 text-left">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Subject</th>
                    <th class="px-4 py-2">Credit Hours</th>
                    <th class="px-4 py-2">Teacher</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($viewStudentSubjects as $subject)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{$subject->teacherSubject->subject->Name}}</td>
                        <td class="px-4 py-2">{{ $subject->teacherSubject->subject->CreditHours }}</td>
                        <td 
                        class="px-4 py-2">{{$subject->teacherSubject->teacher->user->Name }}</td>
                        <td 
                        class="px-4 py-2"><form method="POST" action="{{route('students.unassign_subject',['studentId'=>$id, 'teacherSubjectId'=>$subject->TeacherSubjectId, 'portalName'=> 'student'])}}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Remove</button>
                                    </form>
                                </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-2 text-center text-gray-500">No subjects found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@if(isset($viewingunassignedSubjects) && isset($unassignedSubjects))
    <input type="checkbox" id="add-subjects-modal" class="modal-toggle" checked />

    <div class="modal">
        <div class="modal-box relative bg-white text-black max-w-2xl">
            <label for="add-subjects-modal" class="btn btn-sm btn-circle absolute right-2 top-2 text-white bg-red-500 hover:bg-red-600">✕</label>

            <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700">Subjects</h3>

            <div class="overflow-y-auto max-h-70">
                <table class="table w-full border border-gray-300 text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-black">Subject</th>
                            <th class="px-4 py-2 text-black">Credit Hours</th>
                            <th class="px-4 py-2 text-black">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unassignedSubjects as $subject)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $subject->Name }}</td>
                                <td class="px-4 py-2">{{ $subject->CreditHours }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('users.view_my_subjects',[ $id, 'student' => true, 'getUnAssignedSubjects' =>true, 'subjectId'=>$subject->Id ])}}">
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">See Teachers</button>
</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-2">No available subjects to assign.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <br/>   
            @if(isset($teachers)) 
            <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700">Teachers for {{$teachers[0]->subject->Name}}</h3>
            <div class="overflow-y-auto max-h-30">
                <table class="table w-full border border-gray-300 text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-black">Teacher</th>
                            <th class="px-4 py-2 text-black">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $teacher->teacher->user->Name }}</td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="{{ route('students.assign_subject') }}">
                                        @csrf
                                        <input type="hidden" name="teacherSubjectId" value = "{{$teacher->Id}}">
                                        <input type="hidden" name="studentId" value = "{{$id}}">
                                        <input type="hidden" name="portalName" value = "student">
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Add</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-2">No available Teachers to assign.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
@endif

    @endsection