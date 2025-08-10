@extends('layout.app')

@section('title', 'Students')

@section('content')
    <div class="max-w-7xl text-black mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Student List</h2>
        <table class="w-full border border-gray-300 text-left">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Id</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Age</th>
                    <th class="px-4 py-2">Gender</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $std)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{$std->student->Id}}</td>
                        <td class="px-4 py-2">{{ $std->student->user->Name }}</td>
                        <td 
                        class="px-4 py-2">{{ $std->student->Age }}</td>
                        <td class="px-4 py-2">{{ $std->student->user->gender->Value }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-2 text-center text-gray-500">No students found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    

@endsection