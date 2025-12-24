<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBites - Food Delivery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
        }
        .hero-overlay {
            background-color: rgba(0, 0, 0, 0.6); /* Dark overlay so text pops */
        }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <nav class="bg-white shadow fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <span class="font-bold text-2xl text-orange-600">QuickBites</span>
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="/" class="text-gray-700 hover:text-orange-600 px-3 py-2 font-medium">Home</a>
                    <a href="#" class="text-gray-700 hover:text-orange-600 px-3 py-2 font-medium">Menu</a>
                    <a href="#" class="text-gray-700 hover:text-orange-600 px-3 py-2 font-medium">About</a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <span class="text-gray-700 font-medium">Hi, {{ auth()->user()->name }}</span>
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition text-sm font-bold">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-600 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-orange-600 text-white px-4 py-2 rounded-full hover:bg-orange-700 transition font-bold shadow-md">
                            Sign Up
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="relative hero-bg h-screen flex items-center justify-center">
        <div class="absolute inset-0 hero-overlay"></div>
        <div class="relative z-10 text-center px-4">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-white mb-6 drop-shadow-lg">
                Craving Delicious Food?
            </h1>
            <p class="text-xl sm:text-2xl text-gray-200 mb-8 max-w-2xl mx-auto">
                Order from the best local restaurants and get it delivered to your doorstep in minutes.
            </p>
            <a href="#" class="bg-orange-600 text-white text-lg font-bold px-8 py-4 rounded-full shadow-lg hover:bg-orange-700 transition transform hover:scale-105">
                Order Now →
            </a>
        </div>
    </div>

    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-12">How It Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="bg-white p-8 rounded-xl shadow-md transform hover:-translate-y-2 transition duration-300">
                    <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-orange-600 text-2xl font-bold">1</div>
                    <h3 class="text-xl font-bold mb-2">Choose Your Meal</h3>
                    <p class="text-gray-600">Browse our extensive menu and pick your favorite dishes.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-md transform hover:-translate-y-2 transition duration-300">
                    <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-orange-600 text-2xl font-bold">2</div>
                    <h3 class="text-xl font-bold mb-2">Easy Payment</h3>
                    <p class="text-gray-600">Pay online or choose cash on delivery securely.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-md transform hover:-translate-y-2 transition duration-300">
                    <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-orange-600 text-2xl font-bold">3</div>
                    <h3 class="text-xl font-bold mb-2">Fast Delivery</h3>
                    <p class="text-gray-600">Get your food delivered hot and fresh in no time.</p>
                </div>

            </div>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 QuickBites. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>