@extends('layout.app')

@section('title', 'Review Quiz')

@section('content')
<div class="max-w-6xl mx-auto bg-white text-black p-8  rounded-2xl shadow-md space-y-10">
    <div class="space-y-2 text-gray-800">
        <h2 class="text-3xl font-bold text-blue-700 mb-2">Quiz Preview</h2>
</br>
        <p><span class="font-semibold">Title:</span> {{ $quiz->Title }}</p>
        <p><span class="font-semibold">Subject:</span> {{ $quiz->teacherSubject->subject->Name }}</p>
        <p><span class="font-semibold">Date:</span> {{ \Carbon\Carbon::parse($quiz->Date)->format('d M, Y') }}</p>
        <p>
            <span class="font-semibold">Start Time:</span> {{ \Carbon\Carbon::parse($quiz->Start_Time)->format('h:i A') }}
            <span class="ml-6 font-semibold">End Time:</span> {{ \Carbon\Carbon::parse($quiz->End_Time)->format('h:i A') }}
        </p>
    </div>

    <hr class="border-t-2 border-gray-200 my-6">

    <div class="space-y-10">
        @foreach($quiz->questions as $index => $question)
            <div class="space-y-2">
                <h3 class="text-xl font-semibold text-gray-900">
                    Q{{ $index + 1 }}: {{ $question->Text }}
                    <span class="text-sm text-gray-500">({{ $question->Marks }} marks)</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    @foreach($question->options as $option)
                        <div class="px-4 py-2 rounded border 
                            @if($option->Is_Correct) border-green-500 bg-green-50 text-green-800 font-medium @else border-gray-300 @endif">
                            {{ $option->Text }}
                        </div>
                    @endforeach
                </div>
            </div>
            <hr class="border-t border-gray-300">
        @endforeach
    </div>
</div>
@endsection
