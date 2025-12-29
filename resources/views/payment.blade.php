<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bill & Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10">

    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl p-8">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Final Bill</h1>
            <p class="text-gray-500">Review your items before paying</p>
        </div>

        <div class="bg-orange-50 p-4 rounded-lg mb-6 border border-orange-100">
            <div class="flex justify-between border-b border-orange-200 pb-2 mb-2">
                <span class="font-bold text-gray-700">Table Number:</span>
                <span class="text-orange-600 font-bold text-xl">{{ $customer->table_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Customer Name:</span>
                <span class="font-medium">{{ $customer->name }}</span>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Your Orders</h2>
            @if($orders->count() > 0)
                <ul class="space-y-3">
                    @foreach($orders as $order)
                        <li class="flex justify-between items-center text-gray-700 bg-gray-50 p-3 rounded">
                            <div class="flex items-center">
                                <button data-id="{{ $order->id }}" onclick="removeItem(this)" class="text-red-500 font-bold hover:text-red-700 mr-3 px-2 py-1 border border-red-200 rounded hover:bg-red-50 text-xs">
                                    ✕ Remove
                                </button>
                                <span>{{ $order->item_name }}</span>
                            </div>
                            <span class="font-mono">${{ number_format($order->price, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-500">No items ordered yet.</p>
                    <a href="/menu" class="text-orange-600 font-bold underline">Go to Menu</a>
                </div>
            @endif
        </div>

        <div class="flex justify-between items-center border-t-2 border-gray-800 pt-4 mb-8">
            <span class="text-2xl font-bold text-gray-800">TOTAL</span>
            <span class="text-3xl font-bold text-orange-600">${{ number_format($total, 2) }}</span>
        </div>

        <div class="space-y-3">
            <form action="/pay" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-600 text-white font-bold py-4 rounded-lg hover:bg-green-700 transition shadow-lg">
                    Pay & Finish Dining
                </button>
            </form>

            <a href="/menu" class="block w-full text-center bg-gray-800 text-white font-bold py-3 rounded-lg hover:bg-gray-700 transition">
                + Add More Items
            </a>

            <form action="/cancel-all" method="POST" onsubmit="return confirm('Are you sure you want to cancel everything and leave?');">
                @csrf
                <button type="submit" class="block w-full text-center text-red-500 hover:text-red-700 text-sm mt-4 font-semibold">
                    Cancel Entire Order & Restart
                </button>
            </form>
        </div>
    </div>

    <script>
        async function removeItem(element) {
            // Read the ID from the data attribute
            const orderId = element.getAttribute('data-id');

            if(!confirm('Remove this item from the bill?')) return;

            try {
                const response = await fetch('/order/' + orderId, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Could not remove item.');
                }
            } catch (error) {
                console.error(error);
                alert('Connection error.');
            }
        }
    </script>
</body>
</html>