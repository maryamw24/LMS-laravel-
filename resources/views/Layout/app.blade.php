<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Study System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <div class="navbar bg-blue-900 shadow-sm">
      <div class="flex-1">
        <a class="btn bg-blue-900 text-xl btn-ghost">Study System</a>
      </div>
      <div class="flex gap-2">
        <input type="text" placeholder="Search" class="input input-bordered bg-blue-900 w-30 md:w-auto" />
        <div class="dropdown dropdown-end">
          <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
            <div class="w-10 rounded-full">
              <img
                alt="Tailwind CSS Navbar component"
                src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
            </div>
          </div>
          <ul
            tabindex="0"
            class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
            <li><a href="{{route('users.logout')}}">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>

<div class="flex h-screen">
    <!-- Sidebar -->
<aside class="w-65 bg-blue-900 text-white flex flex-col p-6 space-y-6">
    <nav class="flex flex-col gap-4 mt-8">

        @php
            $role = session('user_role'); 
            $studentId = session('student_id'); 
            $userId = session('user_id'); 
        @endphp
        @if ($role == 'Admin')
            <a href="{{ route('students.index') }}" class="btn btn-outline btn-accent text-white hover:bg-blue-700">Students</a>
            <a href="{{ route('teachers.index') }}" class="btn btn-outline btn-accent text-white hover:bg-blue-700">Teachers</a>
            <a href="#" class="btn btn-outline btn-accent text-white hover:bg-blue-700">Subjects</a>
        @elseif ($role == 'Teacher')
            <a href="{{route('teachers.getSubjects', ['teacherId' => $userId])}}" class="btn btn-outline btn-accent text-white hover:bg-blue-700">My Subjects</a>
            <a href="{{ route('quiz.index') }}" class="btn btn-outline btn-accent text-white hover:bg-blue-700">Quizes</a>
        @elseif ($role == 'Student')
            <a href="{{route('students.dashboard')}}" class="btn btn-outline btn-accent text-white hover:bg-blue-700">Dashboard</a>
            <a href="{{route('users.view_my_subjects', ['studentId' => $studentId, 'student' => true])}}" class="btn btn-outline btn-accent text-white hover:bg-blue-700">My Subjects</a>
            <a href="{{route('students.quizzes')}}" class="btn btn-outline btn-accent text-white hover:bg-blue-700">Quizes</a>
        @endif

    </nav>
</aside>


    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        @yield('content')
    </main>
</div>

</body>
</html>
