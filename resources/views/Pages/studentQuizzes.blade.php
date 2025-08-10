@extends('layout.app')

@section('title', 'My Quizzes')

@section('content')
<div class="max-w-5xl mx-auto p-6 space-y-12">

    <h2 class="text-3xl font-bold text-blue-700 text-center">📝 My Quizzes</h2>

    <div>
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Upcoming Quizzes</h3>

        @if($upcoming->isEmpty())
            <p class="text-gray-500">No upcoming quizzes.</p>
        @else
            <div class="space-y-4">
                @foreach($upcoming as $quiz)
                    <div class="bg-white border rounded-lg p-4 shadow-sm flex justify-between items-center">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">{{ $quiz->Title }}</h4>
                            <p class="text-sm text-gray-500">{{ $quiz->teacherSubject->subject->Name }} | {{ \Carbon\Carbon::parse($quiz->Date)->format('F j, Y') }} | {{ \Carbon\Carbon::parse($quiz->Start_Time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($quiz->End_Time)->format('h:i A')}}</p>
                            <p class="text-sm text-gray-500">Duration: {{ $quiz->Duration/60 }} mins </p>
                        </div>
                        <a href="{{ $quiz->canStart 
                                    ? ($quiz->isStarted 
                                        ? route('students.resume    _quiz', $quiz->Id) 
                                        : route('students.attempt_quiz', $quiz->Id)) 
                                    : '#' }}"
                        class="px-4 py-2 rounded transition text-white 
                                {{ $quiz->canStart ? 'xbg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed pointer-events-none' }}">
                        {{ $quiz->isStarted ? 'Resume' : 'Attempt' }}
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

   @if (isset($attempted))
    <div>
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Past Quizzes</h3>

        @if($attempted->isEmpty())
            <p class="text-gray-500">No attempted quizzes available.</p>
        @else
            <div class="space-y-4">
                @foreach($attempted as $quiz)
                    @php
                        $attempt = $quiz->attempts->firstWhere('student_id', auth()->id());
                    @endphp
                    <div class="bg-gray-50 border rounded-lg p-4 shadow-sm flex justify-between items-center">
                        <div>   
                            <h4 class="text-lg font-semibold text-gray-800">{{ $quiz->Title }}</h4>
                            <p class="text-sm text-gray-500">{{ $quiz->teacherSubject->subject->Name }} | {{ \Carbon\Carbon::parse($quiz->Date)->format('F j, Y') }}</p>
                            <p class="text-sm text-black">
                                Status:
                                @if($attempt)
                                    <span class="text-green-600 font-medium">Attempted</span> • Score: <span class="font-semibold">{{ $attempt->Obtained_Marks }}</span>
                                @else
                                    <span class="text-red-500 font-medium">Missing</span>
                                @endif
                            </p>
                        </div>
                        @if($attempt)
                            <a href="{{route('quiz.attempt', $attempt->Id)}}"class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                                Review
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif

</div>


@endsection
