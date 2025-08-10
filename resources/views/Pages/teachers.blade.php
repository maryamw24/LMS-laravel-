

@extends('layout.app')

@section('title', 'Teachers')

@section('content')
    <div class="max-w-7xl text-black mx-auto bg-white p-8 rounded shadow">
        <div class="md:col-span-1 flex justify-end">
            <a href="{{ route('teachers.create') }}">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Add Teacher
                </button>
            </a>
        </div>
        <br/>
        <h2 class="text-xl font-bold mb-4">Teachers List</h2>
        <table class="w-full border border-gray-300 text-left">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Department</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($teachers as $teacher)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{$teacher->user->Name}}</td>
                        <td class="px-4 py-2">{{$teacher->user->Email}}</td>
                        <td class="px-4 py-2">{{$teacher->department?->Value}}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <a href="{{ route('teachers.edit', $teacher->Id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('teachers.destroy', $teacher->Id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-2 text-center text-gray-500">No Teachers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>


    @if(isset($editing) && isset($editTeacher))
    <input type="checkbox" id="edit-modal" class="modal-toggle" checked />
    <div class="modal">
        <div class="modal-box relative bg-white text-black">
            <a href="{{ route('teachers.index') }}"
   class="btn btn-sm btn-circle absolute right-2 top-2 text-white bg-red-500 hover:bg-red-600">
   ✕
</a>
            <h3 class="text-lg font-bold mb-4">Edit Teacher</h3>
            <form method="POST" action="{{ route('teachers.update', $teacher->Id) }}">
                @csrf
                @method('PUT')

                <input type="text" name="name" value="{{ old('Name', $editTeacher->user->Name) }}" placeholder="Name" class="border rounded px-3 py-2 w-full my-3" required>
                <select name="gender" class="border rounded px-3 py-2 w-full my-3" required>
                <option value="">Select Gender</option>
                @foreach ($genders as $gender)
                    <option value="{{ $gender->Value }}"
                        {{ old('gender', $editTeacher->user->gender->Value ?? '') == $gender->Value ? 'selected' : '' }}>
                        {{ $gender->Value }}
                    </option>
                @endforeach
            </select>
                <select name="department" class="border rounded px-3 py-2 w-full my-3" required>
                <option value="">Select Department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->Value }}"
                        {{ old('department', $editTeacher->department->Value ?? '') == $department->Value ? 'selected' : '' }}>
                        {{ $department->Value }}
                    </option>
                @endforeach
            </select>
                <button type="submit" class="btn btn-primary mt-2">Update Teacher</button>
            </form>
        </div>
    </div>
@endif

@endsection
