<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ rand(1000,9999) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none; } }
        body { font-family: 'Courier New', Courier, monospace; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center pt-10">

    <div class="bg-white p-8 w-80 shadow-lg relative">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold">QUICKBITES</h1>
            <p class="text-xs">123 Food Street, KL</p>
            <p class="text-xs">Date: {{ date('d-m-Y H:i') }}</p>
        </div>

        <div class="border-b-2 border-dashed border-gray-400 mb-4"></div>

        <div class="text-sm mb-4">
            <p><strong>Customer:</strong> {{ $customer->name }}</p>
            <p><strong>Table:</strong> {{ $customer->table_number }}</p>
            <p><strong>Payment:</strong> {{ strtoupper($method) }}</p>
        </div>

        <table class="w-full text-sm mb-4">
            <thead>
                <tr class="text-left">
                    <th>Item</th>
                    <th class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->item_name }}</td>
                    <td class="text-right">{{ number_format($order->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="border-b-2 border-dashed border-gray-400 mb-4"></div>

        <div class="flex justify-between font-bold text-lg">
            <span>TOTAL</span>
            <span>${{ number_format($total, 2) }}</span>
        </div>

        <div class="text-center mt-8 text-xs">
            <p>Thank you for dining with us!</p>
            <p>Please come again.</p>
        </div>
    </div>

    <div class="mt-6 space-x-4 no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-3 rounded font-bold shadow hover:bg-blue-700">
            🖨 Print Receipt
        </button>
        <a href="/" class="bg-gray-600 text-white px-6 py-3 rounded font-bold shadow hover:bg-gray-700">
            Back to Home
        </a>
    </div>

</body>
</html>