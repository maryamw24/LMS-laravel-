@extends('layout.app')

@section('title', 'Subjects')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <h1 class="text-3xl font-bold text-center mb-8">Subjects</h1>
    
        @php 
            $userId = session('user_id'); 
        @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($subjects as $subject)
            <div class="card shadow-xl bg-white hover:shadow-2xl transition">
                <div class="card-body items-center text-center">
                    <h2 class="card-title text-xl text-indigo-700 font-semibold">{{ $subject->subject->Name }}</h2>
                    <p class="text-gray-600 mt-2">Students: <span class="font-bold">{{ $subject->student_teacher_subjects_count }}</span></p>
                    <div class="card-actions mt-4">
                        <a href="{{route('teachers.getSubjectStudents',['teacherId'=>$subject->teacher->Id, 'subjectId'=> $subject->subject->Id])}}" class="btn btn-primary btn-sm">View Subject</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
