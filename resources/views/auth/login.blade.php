<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Login</title>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="max-w-xl text-black mx-auto bg-white p-8 rounded shadow w-full ">
        <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

        <form method="POST" action="{{route('users.verify')}}" class="grid grid-cols-1 gap-4 mb-8">
            @csrf
            <input type="email" name="email" placeholder="Email" class="border rounded px-3 py-2" required>
            <input type="password" name="password" placeholder="Password" class="border rounded px-3 py-2" required>
            @if(session('error'))
                <div class="text-red-600 font-medium text-sm">
                    {{ session('error') }}
                </div>  
            @endif  

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Login
                </button>
            </div>
        </form>     
    </div>
</body>
</html>