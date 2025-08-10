@extends('layout.app')

@section('title', 'Quiz Details')

@section('content')
<div class="max-w-5xl mx-auto bg-white text-black p-8  rounded-2xl shadow-md space-y-10">

    {{-- Title --}}
    <h2 class="text-3xl font-extrabold text-center text-blue-700 mb-6">Quiz Details</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="text-base text-gray-800 space-y-2">
        <p><span class="font-semibold">Quiz Title:</span> {{ $quiz->Title }}</p>
        <p><span class="font-semibold">Subject:</span> {{ $quiz->teacherSubject->subject->Name }}</p>
        <p><span class="font-semibold">Date:</span> {{ \Carbon\Carbon::parse($quiz->Date)->format('d M, Y') }}</p>
        <p>
            <span class="font-semibold">Start Time:</span> {{ \Carbon\Carbon::parse($quiz->Start_Time)->format('h:i A') }}
            <span class="ml-6 font-semibold">End Time:</span> {{ \Carbon\Carbon::parse($quiz->End_Time)->format('h:i A') }}
        </p>
    </div>

    <hr class="my-10 border-t-2 border-gray-200">

    {{-- Add New Question --}}
    <h3 class="text-2xl font-bold text-gray-800 mb-4">Add New Question</h3>

    <form method="POST" action="{{route('quiz.add_question_to_quiz')}}" class="space-y-6">
        @csrf
        <input type="hidden" name="quiz_id" value="{{ $quiz->Id }}">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label for="question_text" class="block font-semibold mb-1">Question Text</label>
                <textarea id="question_text" name="question_text" rows="2" class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>
            <div>
                <label for="marks" class="block font-semibold mb-1">Marks</label>
                <input type="number" name="marks" min="1" class="border border-gray-300 rounded px-4 py-2 w-full" required>
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-2">Options (Select the correct one):</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach (['A', 'B', 'C', 'D'] as $option)
                <label class="flex items-center space-x-2">
                    <input type="radio" name="correct_option" value="{{ $option }}" required>
                    <input type="text" name="option_{{ strtolower($option) }}" placeholder="Option {{ $option }}" class="border border-gray-300 rounded px-4 py-2 w-full" required>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-green-600 text-white font-semibold px-6 py-2 rounded shadow hover:bg-green-700 transition">
                Add Question
            </button>
        </div>
    </form>
</div>
@endsection
