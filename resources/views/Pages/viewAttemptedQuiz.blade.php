@extends('layout.app')

@section('title', 'Quiz Attempt Details')

@section('content')
<div class="max-w-6xl mx-auto bg-white text-black p-8 rounded-2xl shadow-md space-y-10">
    {{-- Quiz and Student Info --}}
    <div class="space-y-2 text-gray-800">
        <h2 class="text-3xl font-bold text-blue-700 mb-2">Quiz Attempt Details</h2>
        <p><span class="font-semibold">Student:</span> {{ $attempt->student->user->name }}</p>
        <p><span class="font-semibold">Quiz Title:</span> {{ $attempt->quiz->Title }}</p>
        <p><span class="font-semibold">Subject:</span> {{ $attempt->quiz->teacherSubject->subject->Name ?? '-' }}</p>
        <p><span class="font-semibold">Date:</span> {{ \Carbon\Carbon::parse($attempt->quiz->Date)->format('d M, Y') }}</p>
        <p>
            <span class="font-semibold">Start Time:</span> {{ \Carbon\Carbon::parse($attempt->Started_At)->format('h:i A') }}
            <span class="ml-6 font-semibold">End Time:</span> {{ \Carbon\Carbon::parse($attempt->Ended_At)->format('h:i A') }}
        </p>
        <p><span class="font-semibold">Total Marks:</span> {{ $attempt->Obtained_Marks }}</p>
    </div>

    <hr class="border-t-2 border-gray-200 my-6">

    <div class="space-y-10">
       @foreach($attempt->quiz->questions as $index => $question)
    @php
        $studentAnswer = $attempt->answers->firstWhere('Question_Id', $question->Id);
        $selectedOptionId = $studentAnswer?->selectedOption?->Id ?? null;
    @endphp

    <div class="space-y-2">
        <h3 class="text-xl font-semibold text-gray-900">
            Q{{ $index + 1 }}: {{ $question->Text }}
            <span class="text-sm text-gray-500">({{ $question->Marks }} marks)</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            @foreach($question->options as $option)
                @php
                    $isSelected = $option->Id == $selectedOptionId;
                    $isCorrect = $option->Is_Correct ?? $option->IsCorrect;
                    $class = $isCorrect
                        ? 'border-green-500 bg-green-50 text-green-800 font-medium'
                        : ($isSelected ? 'border-red-400 bg-red-50 text-red-800' : 'border-gray-300');
                @endphp

                <div class="px-4 py-2 rounded border {{ $class }}">
                    <strong>{{ chr(65 + $loop->index) }}.</strong> {{ $option->Text }}
                </div>
            @endforeach
        </div>

        @if(!$studentAnswer)
            <p class="text-sm text-red-600 font-semibold mt-2">Not Attempted</p>
        @endif
    </div>

    <hr class="border-t border-gray-300">
@endforeach

    </div>
    @php
            $role = session('user_role'); 
 
        @endphp
        @if($role == 'Teacher')
    <div class="text-center mt-10">
        <a href="{{ route('quiz.result', $attempt->quiz->Id) }}" class="bg-gray-700 text-white px-6 py-2 rounded hover:bg-gray-800 transition">
            Back to Results
        </a>
    </div>
    @endif
    @if($role == 'Student')
    <div class="text-center mt-10">
        <a href="{{route('students.quizzes')}}" class="bg-gray-700 text-white px-6 py-2 rounded hover:bg-gray-800 transition">
          Back to Quizes
        </a>
    </div>
    @endif
</div>
@endsection
