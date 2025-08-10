@extends('layout.app')

@section('title', 'Create Quiz')

@section('content')
<div class="max-w-4xl mx-auto bg-white text-black p-8 mt-8 rounded-2xl shadow-md space-y-6">

    {{-- Title --}}
    <h2 class="text-3xl font-extrabold text-center text-blue-700 mb-6">
        Create Quiz
    </h2>

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

    {{-- Form --}}
    <form method="POST" action="{{ route('quiz.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Quiz Title --}}
            <div>
                <label for="title" class="block font-semibold mb-1">Quiz Title</label>
                <input type="text" id="title" name="title" placeholder="Enter quiz title" class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- Subject --}}
            <div>
                <label for="subject_id" class="block font-semibold mb-1">Subject</label>
                <select id="subject_id" name="subject_id" class="border border-gray-300 text-black rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->Id }}">{{ $subject->subject->Name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date --}}
            <div>
                <label for="date" class="block font-semibold mb-1">Date</label>
                <input type="date" id="date" name="date" class=" appearance-auto border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- Start Time & End Time --}}
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="start_time" class="block font-semibold mb-1">Start Time</label>
                    <input type="time" id="start_time" name="start_time" class=" appearance-auto border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="end_time" class="block font-semibold mb-1">End Time</label>
                    <input type="time" id="end_time" name="end_time" class=" appearance-auto border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="duration" class="block font-semibold mb-1">Duration (mins)</label>
                    <input type="number" id="duration" name="duration" class=" appearance-auto border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 rounded shadow hover:bg-blue-700 transition">
                Create Quiz
            </button>
        </div>
    </form>
</div>
@endsection
