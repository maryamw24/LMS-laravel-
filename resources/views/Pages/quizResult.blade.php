@extends('layout.app')

@section('title', 'Quiz Results')

@section('content')
<div class="max-w-6xl mx-auto p-8 bg-white rounded-2xl shadow space-y-10 text-black">

    <h2 class="text-3xl font-bold text-blue-700 text-center">Results: {{ $quiz->Title }}</h2>
    <p class="text-center text-gray-600 mb-8">Subject: {{ $quiz->teacherSubject->subject->Name }} | Date: {{ $quiz->Date }}</p>

    <table class="w-full table-auto border-collapse border border-gray-300 text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Student Name</th>
                <th class="border px-4 py-2">Total Marks</th>
                <th class="border px-4 py-2">Obtained Marks</th>
                <th class="border px-4 py-2">Time Started</th>
                <th class="border px-4 py-2">Time Ended</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quiz->attempts as $index => $attempt)
                <tr>
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $attempt->student->user->Name }}</td>
                    <td class="border px-4 py-2">{{$quiz->Total_Marks}}</td>
                    <td class="border px-4 py-2">{{ $attempt->Obtained_Marks }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($attempt->Started_At)->format('h:i A') }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($attempt->Ended_At)->format('h:i A')}}</td>
                    <td class="border px-4 py-2">
                        <a href="{{route('quiz.attempt', $attempt->Id)}}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            View Details
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
