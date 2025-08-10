@extends('layout.app')

@section('title', 'Add Student')

@section('content')
<div class="max-w-3xl mx-auto bg-white text-black p-8 mt-8 rounded-2xl shadow-md space-y-6">

    {{-- Title --}}
    <h2 class="text-3xl font-extrabold text-center text-blue-700 mb-6">
        Add New Student
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
    <form method="POST" action="{{ route('students.store') }}" class="space-y-8">
        @csrf

        {{-- Personal Info Section --}}
        <div>
            <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700">Personal Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="name" placeholder="Full Name" class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>

                <input type="number" name="age" placeholder="Age" class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>

                <select name="gender" class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Gender</option>
                    @foreach ($genders as $gender)
                        <option value="{{ $gender->Value }}">{{ $gender->Value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Login Credentials Section --}}
        <div>
            <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700">Login Credentials</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" autocomplete="off">

                <input type="email" name="email" placeholder="Email" autocomplete="new-email" class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>

                <input type="text" name="password" placeholder="Password" autocomplete="new-password" class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 rounded shadow hover:bg-blue-700 transition">
                Add Student
            </button>
        </div>
    </form>
</div>
@endsection
