

@extends('layout.app')

@section('title', 'Students')

@section('content')
    <div class="max-w-7xl text-black mx-auto bg-white p-8 rounded shadow">
        <div class="md:col-span-1 flex justify-end">
            <a href="{{ route('students.create') }}">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Add Student
                </button>
            </a>
        </div>
        <br/>
        <h2 class="text-xl font-bold mb-4">Student List</h2>
        <table class="w-full border border-gray-300 text-left">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Id</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Age</th>
                    <th class="px-4 py-2">Gender</th>
                    <th class="px-4 py-2">Subject Actions</th>
                    <th class="px-4 py-2">Credentials</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $std)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{$std->Id}}</td>
                        <td class="px-4 py-2">{{ $std->user->Name }}</td>
                        <td 
                        class="px-4 py-2">{{ $std->Age }}</td>
                        <td class="px-4 py-2">{{ $std->user->gender->Value }}</td>
                        <td><a href="{{ route('students.view_student_subjects',$std->Id) }}"
                            class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 mx-2">
                            View Subjects
                            </a>
                            <a href="{{ route('students.view_unassigned_subjects',  $std->Id) }}"
                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Add Subject
                            </a>
                        </td>
                        <td><a href="{{ route('students.edit_creds', $std->Id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 mx-2">Manage Credentials</a></td>
                            <td class="px-4 py-2 flex space-x-2">
                            <a href="{{ route('students.edit', $std->Id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('students.destroy', $std->Id) }}">
                                @csrf   
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-2 text-center text-gray-500">No students found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>



    @if(isset($editing) && isset($student))
    <input type="checkbox" id="edit-modal" class="modal-toggle" checked />

    <div class="modal">
        <div class="modal-box relative bg-white text-black">
            <a href="{{ route('students.index') }}"
   class="btn btn-sm btn-circle absolute right-2 top-2 text-white bg-red-500 hover:bg-red-600">
   ✕
</a>
            <h3 class="text-lg font-bold mb-4">Edit Student</h3>
            <form method="POST" action="{{ route('students.update', $student->Id) }}">
                @csrf
                @method('PUT')
                <input type="text" name="name" value="{{ old('Name', $student->user->Name) }}" placeholder="Name" class="border rounded px-3 py-2 w-full my-3" required>
                <input type="number" name="age" value="{{ old('Age', $student->Age) }}" placeholder="Age" class="border rounded px-3 py-2 w-full my-3" required>
                <select name="gender" class="border rounded px-3 py-2 w-full my-3" required>
                <option value="">Select Gender</option>
                @foreach ($genders as $gender)
                    <option value="{{ $gender->Value }}"
                        {{ old('gender', $student->user->gender->Value ?? '') == $gender->Value ? 'selected' : '' }}>
                        {{ $gender->Value }}
                    </option>
                @endforeach
            </select>
                <button type="submit" class="btn btn-primary mt-2">Update Student</button>
            </form>
        </div>
    </div>
@endif


@if(isset($editingCreds) && isset($user))
    <input type="checkbox" id="edit-creds-modal" class="modal-toggle" checked />
    <div class="modal">
        <div class="modal-box relative bg-white text-black">
            <a href="{{ route('students.index') }}"
   class="btn btn-sm btn-circle absolute right-2 top-2 text-white bg-red-500 hover:bg-red-600">
   ✕
</a>
            <h3 class="text-lg font-bold mb-4">Edit Credentials
            </h3>
            <form method="POST" action="{{ route('users.update_credentials', $user->Id) }}">
                @csrf
                @method('PUT')
                <input type="text" name="email" value="{{ old('Name', $user->Email) }}" placeholder="Email" class="border rounded px-3 py-2 w-full my-3" required>
                <input type="text" name="password" value="{{ old('Age', $user->Password) }}" placeholder="Password" class="border rounded px-3 py-2 w-full my-3" required>
                
                <button type="submit" class="btn btn-primary mt-2">Update</button>
            </form>
        </div>
    </div>
@endif


@if (isset($viewStudentSubjects) && isset($viewingStudentSubjects))
    <input type="checkbox" id="view-student-subjects-modal" class="modal-toggle" checked />

    <div class="modal">
        <div class="modal-box relative bg-white text-black max-w-2xl">
            <label for="view-student-subjects-modal" class="btn btn-sm btn-circle absolute right-2 top-2 text-white bg-red-500 hover:bg-red-600">✕</label>
            <h2 class="text-lg font-bold mb-4">Assigned Subjects to {{ $assigningStudent->Name }}</h2>   
            <div class="overflow-y-auto">
                <table class="table border border-gray-300 text-left text-black">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-black">Subject</th>
                            <th class="px-4 py-2 text-black">Teacher</th>
                            <th class="px-4 py-2 text-black">Credit Hours</th>
                            <th class="px-4 py-2 text-black ">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($viewStudentSubjects as $subjectTeacher)
                            <tr class="border-t">
                                
                                <td class="px-4 py-2">{{ $subjectTeacher->teacherSubject->subject->Name }}</td>
                                <td class="px-4 py-2">{{ $subjectTeacher->teacherSubject->teacher->user->Name }}</td>
                                <td class="px-4 py-2">{{ $subjectTeacher->teacherSubject->subject->CreditHours }}</td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="{{route('students.unassign_subject',['studentId'=>$assigningStudent->Id, 'teacherSubjectId'=>$subjectTeacher->TeacherSubjectId, 'portalName'=>'teacher'])}}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-gray-500 py-2">No subjects assigned.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif


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
                                    
                                    <form method="GET" action="{{ route('students.view_unassigned_subjects', $assigningStudent->Id) }}">
                                        <input type="hidden" name="subjectId" value="{{ $subject->Id }}">
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">See Teachers</button>
                                    </form>
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
                                        <input type="hidden" name="studentId" value = "{{$assigningStudent->Id}}">
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
