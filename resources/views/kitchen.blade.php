<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kitchen Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="refresh" content="10"> 
</head>
<body class="bg-gray-900 text-white p-6">

    <div class="flex justify-between items-center mb-8 border-b border-gray-700 pb-4">
        <div>
            <h1 class="text-3xl font-bold text-orange-500">👨‍🍳 Kitchen Tracker</h1>
            <p class="text-gray-400 text-sm">Updates automatically: 1 min (Cooking) -> 2 mins (Ready)</p>
        </div>
        <div class="space-x-4">
            <button onclick="window.location.reload()" class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600">🔄 Refresh</button>
            <a href="/admin/dashboard" class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-500">Back to Admin</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($orders as $order)
            <div class="bg-gray-800 rounded-xl p-5 border-l-8 shadow-lg relative
                {{ $order->status == 'pending' ? 'border-red-500' : '' }}
                {{ $order->status == 'cooking' ? 'border-yellow-500' : '' }}
                {{ $order->status == 'ready' ? 'border-green-500' : '' }}
                {{ $order->status == 'completed' ? 'border-gray-600 opacity-50' : '' }}">

                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase">Table #{{ $order->customer->table_number ?? '?' }}</span>
                        <h2 class="text-xl font-bold">{{ $order->item_name }}</h2>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-bold uppercase
                        {{ $order->status == 'pending' ? 'bg-red-500 text-white' : '' }}
                        {{ $order->status == 'cooking' ? 'bg-yellow-500 text-black' : '' }}
                        {{ $order->status == 'ready' ? 'bg-green-500 text-white' : '' }}
                        {{ $order->status == 'completed' ? 'bg-gray-600 text-gray-300' : '' }}">
                        {{ $order->status }}
                    </span>
                </div>

                <p class="text-gray-400 text-sm mb-4">
                    Ordered: {{ $order->created_at->format('H:i:s') }} <br>
                    Time elapsed: {{ $order->created_at->diffInMinutes(now()) }} mins
                </p>

                <div class="w-full bg-gray-700 rounded-full h-2.5 dark:bg-gray-700 mt-2">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500
                        {{ $order->status == 'pending' ? 'w-[10%]' : ($order->status == 'cooking' ? 'w-1/2' : 'w-full') }}">
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500 mt-10">
                <p class="text-2xl">No active orders.</p>
                <p>The kitchen is clear! 🧹</p>
            </div>
        @endforelse
    </div>
</body>
</html>