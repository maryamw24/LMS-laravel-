
@extends('layout.app')

@section('title', 'Attempt Quiz')

@section('content')
<div class="max-w-5xl mx-auto p-8 bg-white rounded-2xl shadow space-y-10 text-black">
    {{-- Quiz Info --}}
    <div class="text-center space-y-2">
        <h2 class="text-3xl font-bold text-blue-700">{{ $quiz->Title }}</h2>
        <p class="text-gray-600">
            Date: {{ $quiz->Date }} | Time: {{ \Carbon\Carbon::parse($quiz->Start_Time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($quiz->End_Time)->format('h:i A') }}
        </p>
        <p class="text-sm text-gray-500">Subject: {{ $quiz->teacherSubject->subject->Name }}</p>
    </div>

    {{-- Timer --}}
    <div class="text-red-500 text-center text-lg font-semibold"
         id="quiz-timer"
         data-duration="{{ $remainingSeconds ?? $quiz->Duration }}">
    </div>
    {{-- Attempt Form --}}
    <form action="{{ route('students.submit_quiz', ['id' => $quiz->Id]) }}" method="POST" class="space-y-8" id="quiz-form">
        @csrf
        <input type="hidden" name="quiz_id" value="{{ $quiz->Id }}">
        <input type="hidden" name="start_time" id="start_time" value="{{ $start_time }}">
        <input type="hidden" name="time_consumed" id="time_consumed">
        <input type="hidden" id="attempt_id" name="attempt_id" value="{{ $attempt->Id ?? '' }}">
        <input type="hidden" name="question_id" id="question_id">
        <input type="hidden" name="selected_option_id" id="selected_option_id">
        <div id="questions-wrapper">
            @foreach($quiz->questions as $index => $question)
                <div class="question-step {{ $index === 0 ? '' : 'hidden' }} space-y-6" data-question-id="{{ $question->Id }}">
                    <div class="p-6 border border-gray-300 rounded-xl space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold">Q{{ $index + 1 }}. {{ $question->Text }}</h3>
                            <p class="text-sm text-gray-500">Marks: {{ $question->Marks }}</p>
                        </div>

                        {{-- Options --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php $letters = ['A', 'B', 'C', 'D']; @endphp
                            @foreach($question->options as $i => $option)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio"
                                           name="answers[{{ $index === count($quiz->questions) - 1 ? $question->Id : 'temp' }}]"
                                           value="{{ $option->Id }}"
                                           class="answer-radio"
                                           data-question="{{ $index }}"
                                           @if(isset($answers[$question->Id]) && $answers[$question->Id] == $option->Id) checked @endif
                                    >
                                    <span><strong>{{ $letters[$i] }}-</strong> {{ $option->Text }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="flex justify-between items-center pt-4">
                        {{-- Previous Button --}}
                        @if($index > 0)
                            <button type="button"
                                    class="prev-btn bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 transition"
                                    data-index="{{ $index }}">
                                Previous
                            </button>
                        @else
                            <div></div>
                        @endif

                        {{-- Next or Submit --}}
                        @if($index < count($quiz->questions) - 1)
                            <button type="button"
                                    class="next-btn bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition hidden"
                                    data-index="{{ $index }}">
                                Next
                            </button>
                        @else
                            <button type="submit"
                                    class="submit-btn bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition hidden">
                                Submit Quiz
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </form>

    {{-- Time Up Modal --}}
    <div id="timeUpModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-xl shadow-xl w-80 text-center space-y-4">
            <h2 class="text-xl font-bold text-red-600">Time's Up!</h2>
            <p class="text-gray-700">Your time for this quiz has ended.</p>
            <button id="closeModalBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Back to Quizzes
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const steps = document.querySelectorAll('.question-step');
            const radios = document.querySelectorAll('.answer-radio');
            const timerDiv = document.getElementById('quiz-timer');
            const modal = document.getElementById('timeUpModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const redirectURL = "{{ route('students.quizzes') }}";
            const saveAnswerRoute = "{{ route('students.save_answer') }}";
            const attemptId = document.getElementById('attempt_id').value;
            const startTime = document.getElementById('start_time').value;
            const timeConsumedInput = document.getElementById('time_consumed');
            let currentStep = 0;

            function calculateTimeConsumed(startTime) {
                const now = new Date().getTime();
                const start = new Date(startTime).getTime();
                const diff = now - start;
                return Math.max(0, Math.floor(diff / 1000));
            }

            function showStep(index) {
                steps.forEach((step, i) => {
                    step.classList.toggle('hidden', i !== index);
                });
                if (index === steps.length - 1) {
                    const currentQuestionId = steps[index].dataset.questionId;
                    radios.forEach(radio => {
                        if (radio.dataset.question == index) {
                            radio.name = `answers[${currentQuestionId}]`;
                        }
                    });
                } else {
                    radios.forEach(radio => {
                        if (radio.dataset.question == index) {
                            radio.name = `answers[temp]`;
                        }
                    });
                }
            }

            function getFirstUnansweredIndex() {
                for (let i = 0; i < steps.length; i++) {
                    const radios = steps[i].querySelectorAll('input[type="radio"]');
                    let answered = false;
                    radios.forEach(r => {
                        if (r.checked) answered = true;
                    });
                    if (!answered) return i;
                }
                return 0;
            }

            radios.forEach(radio => {
                radio.addEventListener('change', function () {
                    const questionIndex = parseInt(this.dataset.question);
                    const nextBtn = document.querySelector(`.next-btn[data-index='${questionIndex}']`);
                    const isLast = questionIndex === steps.length - 1;

                    if (isLast) {
                        document.querySelector('.submit-btn').classList.remove('hidden');
                    } else if (nextBtn) {
                        nextBtn.classList.remove('hidden');
                    }
                });
            });

            document.querySelectorAll('.next-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const stepElement = steps[currentStep];
                    const selectedRadio = stepElement.querySelector('input[type="radio"]:checked');
                    if (!selectedRadio) {
                        alert("Please select an answer before moving to the next question.");
                        return;
                    }

                    const questionId = stepElement.dataset.questionId;
                    const optionId = selectedRadio.value;
                    fetch(saveAnswerRoute, {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            start_time: startTime,
                            attempt_id: attemptId,
                            question_id: questionId,
                            selected_option_id: optionId,
                            time_consumed: calculateTimeConsumed(startTime)
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(() => {
                        currentStep++;
                        showStep(currentStep);
                    })
                    .catch(error => {
                        console.error("Error saving answer:", error);
                        alert("Failed to save your answer: " + (error.message || "Please try again."));
                    });
                });
            });

            document.querySelectorAll('.prev-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

           document.getElementById('quiz-form').addEventListener('submit', function (event) {
                const stepElement = steps[currentStep];
                const selectedRadio = stepElement.querySelector('input[type="radio"]:checked');

                if (!selectedRadio) {
                    event.preventDefault();
                    alert("Please select an answer before submitting.");
                    return;
                }

                document.getElementById('question_id').value = stepElement.dataset.questionId;
                document.getElementById('selected_option_id').value = selectedRadio.value;
                document.getElementById('time_consumed').value = calculateTimeConsumed(startTime);
            });


            const durationSeconds = parseInt(timerDiv.getAttribute('data-duration'), 10);
            const endTime = new Date().getTime() + durationSeconds * 1000;

            function updateTimer() {
                const now = new Date().getTime();
                const remaining = endTime - now;

                if (remaining <= 0) {
                    clearInterval(timerInterval);
                    timerDiv.textContent = "Time Left: 00:00:00";
                    modal.classList.remove('hidden');
                    // Auto-submit the form if on the last question
                    if (currentStep === steps.length - 1) {
                        const selectedRadio = steps[currentStep].querySelector('input[type="radio"]:checked');
                        if (selectedRadio) {
                            timeConsumedInput.value = calculateTimeConsumed(startTime);
                            document.getElementById('quiz-form').submit();
                        }
                    }
                    return;
                }

                const hours = Math.floor(remaining / (1000 * 60 * 60));
                const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

                timerDiv.textContent = `Time Left: ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            const timerInterval = setInterval(updateTimer, 1000);

            closeModalBtn.addEventListener('click', () => {
                window.location.href = redirectURL;
            });

            currentStep = {{ $resumeIndex ?? 0 }};
            showStep(currentStep);
            const preSelectedRadio = steps[currentStep].querySelector('input[type="radio"]:checked');
            if (preSelectedRadio) {
                const nextBtn = document.querySelector(`.next-btn[data-index='${currentStep}']`);
                if (nextBtn) {
                    nextBtn.classList.remove('hidden');
                } else {
                    document.querySelector('.submit-btn')?.classList.remove('hidden');
                }
            }
        });
    </script>
@endsection