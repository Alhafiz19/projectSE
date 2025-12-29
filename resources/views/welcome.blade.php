<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBites - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
        }
        .hero-overlay { background-color: rgba(0, 0, 0, 0.7); }
        
        /* Modal Animation */
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow-x: hidden; overflow-y: hidden !important; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    @if(session('success'))
        <div onclick="this.remove()" class="fixed top-0 w-full bg-green-500 text-white text-center py-4 z-50 cursor-pointer font-bold shadow-lg">
            {{ session('success') }} (Click to close)
        </div>
    @endif

    <nav class="bg-white shadow fixed w-full z-40 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <span class="font-bold text-2xl text-orange-600">QuickBites</span>
                
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="/" class="text-gray-700 hover:text-orange-600 font-medium">Home</a>
                    <a href="/menu" class="text-gray-700 hover:text-orange-600 font-medium">Menu (View Only)</a>
                    
                    <a href="/admin/login" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-gray-700 transition">
                        🔒 Admin Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="relative hero-bg h-screen flex items-center justify-center">
        <div class="absolute inset-0 hero-overlay"></div>
        <div class="relative z-10 text-center px-4">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-white mb-6 drop-shadow-lg">
                Welcome to QuickBites
                <span class="block text-orange-500 text-3xl sm:text-4xl mt-2">Delicious Food, Served Fast</span>
            </h1>
            
            <button onclick="toggleModal()" class="bg-orange-600 text-white text-xl font-bold px-10 py-4 rounded-full shadow-lg hover:bg-orange-700 transition transform hover:scale-105 cursor-pointer">
                Start Order
            </button>

            <div class="mt-4">
                <a href="/menu" class="text-gray-300 underline hover:text-white text-sm">Just browsing? View Menu</a>
            </div>
        </div>
    </div>

    <div class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        
        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold text-gray-800">Your Details</p>
                    <div class="modal-close cursor-pointer z-50" onclick="toggleModal()">
                        <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                        </svg>
                    </div>
                </div>

                <form action="/checkin" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="John Doe" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">Phone Number</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone" name="phone" type="tel" placeholder="0123456789" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="table">Table Number</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="table" name="table_number" type="number" placeholder="10" required>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="button" onclick="toggleModal()" class="px-4 bg-transparent p-3 rounded-lg text-orange-500 hover:bg-gray-100 hover:text-orange-400 mr-2">Cancel</button>
                        <button type="submit" class="px-4 bg-orange-600 p-3 rounded-lg text-white hover:bg-orange-700 font-bold">Confirm & Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal() {
            const body = document.querySelector('body');
            const modal = document.querySelector('.modal');
            modal.classList.toggle('opacity-0');
            modal.classList.toggle('pointer-events-none');
            body.classList.toggle('modal-active');
        }
        
        const overlay = document.querySelector('.modal-overlay');
        overlay.addEventListener('click', toggleModal);
    </script>
</body>
</html>