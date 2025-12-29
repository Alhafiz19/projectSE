<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 h-screen flex justify-center items-center">

    <div class="bg-white p-8 rounded shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-4 text-center text-gray-800">Admin Login</h1>
        
        @if(session('error')) 
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm text-center">
                {{ session('error') }}
            </div> 
        @endif

        <form action="/admin/login" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" name="username" placeholder="admin" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" placeholder="admin123" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2 rounded font-bold transition">
                Login
            </button>
        </form>

        <p class="text-xs text-gray-500 mt-4 text-center">
            <a href="/" class="underline hover:text-gray-800">← Back to Home</a>
        </p>
    </div>

</body>
</html>