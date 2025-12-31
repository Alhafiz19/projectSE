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
            <p class="text-gray-400 text-sm">Grouped by Table • Updates every 10s</p>
        </div>
        <div class="space-x-4">
            <button onclick="window.location.reload()" class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600">🔄 Refresh</button>
            <a href="/admin/dashboard" class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-500">Back to Admin</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- LARAVEL MAGIC: Group the list by Customer ID --}}
        @forelse($orders->groupBy('customer_id') as $customerId => $items)
            
            {{-- Get the first item to grab Customer Details (Name/Table) --}}
            @php $customer = $items->first()->customer; @endphp

            <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-700 flex flex-col overflow-hidden">
                
                <div class="bg-gray-750 p-4 border-b border-gray-600 flex justify-between items-center bg-gray-700">
                    <div>
                        <span class="block text-xl font-bold text-orange-400">
                            TABLE #{{ $customer->table_number ?? '?' }}
                        </span>
                        <span class="text-gray-300 text-sm">👤 {{ $customer->name ?? 'Guest' }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-400 block">Total Items</span>
                        <span class="font-bold text-lg">{{ $items->count() }}</span>
                    </div>
                </div>

                <div class="p-0">
                    @foreach($items as $order)
                        <div class="flex justify-between items-center p-4 border-b border-gray-700 hover:bg-gray-750 transition
                            {{ $order->status == 'completed' ? 'opacity-50 bg-gray-800' : '' }}">
                            
                            <div class="flex-1 pr-2">
                                <h3 class="font-bold text-lg {{ $order->status == 'completed' ? 'text-gray-500 line-through' : 'text-white' }}">
                                    {{ $order->item_name }}
                                </h3>
                                <p class="text-xs text-gray-500">
                                    {{ $order->created_at->format('H:i') }} 
                                    ({{ $order->created_at->diffInMinutes(now()) }}m ago)
                                </p>
                            </div>

                            <div class="flex flex-col items-end gap-1">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider w-20 text-center
                                    {{ $order->status == 'pending' ? 'bg-red-500 text-white' : '' }}
                                    {{ $order->status == 'cooking' ? 'bg-yellow-500 text-black' : '' }}
                                    {{ $order->status == 'ready' ? 'bg-green-500 text-white' : '' }}
                                    {{ $order->status == 'completed' ? 'bg-gray-600 text-gray-300' : '' }}">
                                    {{ $order->status }}
                                </span>
                                
                                <div class="w-20 bg-gray-600 rounded-full h-1.5 mt-1">
                                    <div class="h-1.5 rounded-full transition-all duration-500
                                        {{ $order->status == 'pending' ? 'bg-red-400 w-[10%]' : '' }}
                                        {{ $order->status == 'cooking' ? 'bg-yellow-400 w-1/2' : '' }}
                                        {{ $order->status == 'ready' ? 'bg-green-400 w-full' : '' }}
                                        {{ $order->status == 'completed' ? 'bg-gray-500 w-full' : '' }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
                
                @if($items->where('status', 'ready')->count() > 0)
                    <div class="p-2 bg-green-900/30 text-center border-t border-green-800">
                        <span class="text-green-400 text-xs font-bold uppercase">⚡ Some items are Ready!</span>
                    </div>
                @endif
            </div>

        @empty
            <div class="col-span-3 text-center text-gray-500 mt-10">
                <p class="text-2xl">No active orders.</p>
                <p>The kitchen is quiet... 🧹</p>
            </div>
        @endforelse
    </div>

</body>
</html>