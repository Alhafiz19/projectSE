<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill & Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10">

    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl p-8">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Final Bill</h1>
            <p class="text-gray-500">Thank you for dining with QuickBites</p>
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
            <div class="flex justify-between">
                <span class="text-gray-600">Phone:</span>
                <span class="font-medium">{{ $customer->phone }}</span>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Your Orders</h2>
            @if($orders->count() > 0)
                <ul class="space-y-3">
                    @foreach($orders as $order)
                        <li class="flex justify-between items-center text-gray-700">
                            <span>{{ $order->item_name }}</span>
                            <span class="font-mono">${{ number_format($order->price, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-red-500 text-center">No items ordered yet.</p>
            @endif
        </div>

        <div class="flex justify-between items-center border-t-2 border-gray-800 pt-4 mb-8">
            <span class="text-2xl font-bold text-gray-800">TOTAL</span>
            <span class="text-3xl font-bold text-orange-600">${{ number_format($total, 2) }}</span>
        </div>

        <button onclick="alert('Proceeding to Payment Gateway...')" class="w-full bg-gray-900 text-white font-bold py-4 rounded-lg hover:bg-gray-800 transition">
            Proceed to Payment
        </button>

        <a href="/menu" class="block text-center mt-4 text-gray-500 hover:text-gray-800">
            ← Go Back to Menu
        </a>
    </div>

</body>
</html>