@extends('layout.app')

@section('title', 'Quiz Result')

@section('content')
<div class="max-w-3xl mx-auto p-8 bg-white rounded-xl shadow space-y-8 text-black">

    <h2 class="text-3xl font-bold text-center text-blue-700">Quiz Result</h2>

    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <h4 class="font-semibold text-gray-700">Quiz Title</h4>
                <p>{{ $attempt->quiz->Title }}</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-700">Subject</h4>
                <p>{{ $attempt->quiz->teacherSubject->subject->Name }}</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-700">Date</h4>
                <p>{{ $attempt->quiz->Date }}</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-700">Time Taken</h4>
                <p>{{ $attempt->Time_Taken }} mins</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-700">Total Marks</h4>
                <p>{{ $attempt->Total_Marks }}</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-700">Marks Obtained</h4>
                <p class="font-bold text-green-700 text-lg">{{ $attempt->Obtained_Marks }}</p>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('students.quizzes') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
             Back to Quizzes
        </a>
    </div>
    <div class="text-center">
        <a href="{{route('quiz.attempt', $attempt->Id)}}" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
            Preview Quiz
        </a>
    </div>
</div>
@endsection
