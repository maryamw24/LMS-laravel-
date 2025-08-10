@extends('layout.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-10">

    {{-- Header --}}
    <h2 class="text-3xl font-bold text-blue-700 text-center">Welcome to Your Dashboard</h2>

    {{-- Announcements --}}
    <div>
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Announcements</h3>


        @if(isset($announcements))
            
            <div class="space-y-6">
                @foreach($announcements as $announcement)
                    <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm space-y-3">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">{{ $announcement->Title }}</h4>
                            <p class="text-gray-700 mt-1">{{ $announcement->Description }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                By {{ $announcement->teacher->Name }} • {{ \Carbon\Carbon::parse($announcement->created_at)->diffForHumans() }}
                            </p>
                        </div>
                        <div class="text-right">
                            <a href="" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                View Quiz
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
