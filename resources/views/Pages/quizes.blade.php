@extends('layout.app')

@section('title', 'Quizzes')

@section('content')
<div class="max-w-7xl text-black mx-auto bg-white p-8 rounded shadow space-y-10">

    {{-- Top Create Button --}}
    <div class="flex justify-end">
        <a href="{{ route('quiz.create') }}">
            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create Quiz
            </button>
        </a>
    </div>

    {{-- New Quizzes Table --}}
    <div>
        <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700">New Quizzes</h3>
        <table class="w-full border border-gray-300 text-left">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">Subject</th>
                    <th class="px-4 py-2">Duration</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Start Time</th>
                    <th class="px-4 py-2">End Time</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($newQuizzes as $newQuiz)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $newQuiz->Title }}</td>
                        <td class="px-4 py-2">{{ $newQuiz->teacherSubject->subject->Name }}</td>
                        <td class="px-4 py-2">{{ $newQuiz->Duration }} mins</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($newQuiz->Date)->format('d M, Y') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($newQuiz->Start_Time)->format('h:i A') }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($newQuiz->End_Time)->format('h:i A') }}</td>
                        <td>
                            <a href="{{ route('quiz.add_question', $newQuiz->Id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 mx-2">Add Questions</a>
                            <a href="{{ route('quiz.preview_quiz', $newQuiz->Id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 mx-2">Preview</a>
                            <a href="{{ route('quiz.edit', $newQuiz->Id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 mx-2">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">No new quizzes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination Links --}}
        <div class="mt-4">
            {{ $newQuizzes->links() }}
        </div>
    </div>

    @if (isset($attemptedQuizzes))
        {{-- Attempted Quizzes Table --}}
        <div>
            <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700 mt-8">Attempted Quizzes</h3>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 text-left">
                    <thead class="bg-gray-100 text-left">
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Subject</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Start Time</th>
                            <th class="px-4 py-2">End Time</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attemptedQuizzes as $quiz)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $quiz->Title }}</td>
                                <td class="px-4 py-2">{{ $quiz->teacherSubject->subject->Name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($quiz->Date)->format('d M, Y') }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($quiz->Start_Time)->format('h:i A') }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($quiz->End_Time)->format('h:i A') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('quiz.result', $quiz->Id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                        View Result
                                    </a>
                                    <a href="{{ route('quiz.preview_quiz', $quiz->Id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 mx-2">Preview</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4 text-gray-500">No attempted quizzes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination Links --}}
                <div class="mt-4">
                    {{ $attemptedQuizzes->links() }}
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
