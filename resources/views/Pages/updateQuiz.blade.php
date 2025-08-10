@extends('layout.app')

@section('title', 'Edit Quiz')

@section('content')
<div class="max-w-5xl mx-auto bg-white text-black p-8 mt-8 rounded-2xl shadow-md space-y-10">

    {{-- Title --}}
    <h2 class="text-3xl font-extrabold text-center text-blue-700 mb-6">Edit Quiz</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            <ul class="list-disc ml-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form to Update Quiz Info --}}
    <div class="space-y-6">
        <form method="POST" action="{{ route('quiz.update', $quiz->Id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-6">
                {{-- Quiz Title --}}
                <div>
                    <label for="title" class="block font-semibold mb-1">Quiz Title</label>
                    <input type="text" id="title" name="title" value="{{ $quiz->Title }}" class="appearance-auto border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                {{-- Subject --}}
                <div>
                    <label for="subject_id" class="block font-semibold mb-1">Subject</label>
                    <select id="subject_id" name="subject_id" class="border border-gray-300 text-black rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->Id }}" {{ $quiz->TeacherSubjectId == $subject->Id ? 'selected' : '' }}>
                                {{ $subject->subject->Name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block font-semibold mb-1">Date</label>
                    <input type="date" id="date" name="date" value="{{ $quiz->Date }}" class="appearance-auto border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                {{-- Start & End Time --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="start_time" class="block font-semibold mb-1">Start Time</label>
                        <input type="time" id="start_time" name="start_time" value="{{ $quiz->Start_Time }}" class="appearance-auto border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="end_time" class="block font-semibold mb-1">End Time</label>
                        <input type="time" id="end_time" name="end_time" value="{{ $quiz->End_Time }}" class="appearance-auto border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="duration" class="block font-semibold mb-1">Duration</label>
                        <input type="number" id="duration" name="duration" value="{{ $quiz->Duration }}" class="appearance-auto border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="submit" class="bg-yellow-500 text-white font-semibold px-6 py-2 rounded shadow hover:bg-yellow-600 transition">
                    Update Quiz Info
                </button>
        </form>
        <form method="POST" action="{{ route('quiz.destroy', $quiz->Id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete this quiz?')" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition">
                Delete Quiz
            </button>
        </form>
    </div>

    {{-- Divider --}}
    <hr class="my-10 border-t-2 border-gray-300">

    {{-- Questions Section --}}
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Questions</h2>

    @if($quiz->questions->isEmpty())
        <p class="text-gray-600">No questions added yet.</p>
    @else
        <div class="space-y-10">
            @foreach($quiz->questions as $index => $question)
            <div class='bg-gray-50 border border-gray-200 p-6 rounded-xl shadow space-y-4'>
                <form action="{{ route('quiz.update_question',['id'=> $quiz->Id,'questionId' => $question->Id]) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type='hidden' name='quiz_id' value='{{ $quiz->Id }}'>
                    <div class="flex flex-col md:flex-row gap-4 items-start md:items-end">
                        <div class="flex-1">
                            <label class="block font-semibold mb-1">Q{{ $index + 1 }}: Question Text</label>
                            <textarea name="question_text" rows="2" required class="w-full border rounded px-4 py-2">{{ $question->Text }}</textarea>
                        </div>
                        <div class="w-32">
                            <label class="block font-semibold mb-1">Marks</label>
                            <input type="number" name="marks" value="{{ $question->Marks }}" required class="w-full border rounded px-4 py-2">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">Options (Select the correct one):</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php $optionKeys = ['A', 'B', 'C', 'D']; @endphp
                            @foreach($question->options as $i => $option)
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="correct_option" value="{{ $optionKeys[$i] }}" {{ $option->Is_Correct ? 'checked' : '' }} required>
                                    <input type="hidden" name="options[{{ $option->Id }}][key]" value="{{ $optionKeys[$i] }}">
                                    <input type="text" name="options[{{ $option->Id }}][text]" value="{{ $option->Text }}" required class="w-full border rounded px-4 py-2">
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                            Update Question
                        </button>
                    </form>
                    <form method="POST" action="{{ route('quiz.remove_question', ['id' => $quiz->Id, 'questionId' => $question->Id]) }}">
                        @csrf   
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection