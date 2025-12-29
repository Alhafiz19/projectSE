<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Sales</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">📊 Weekly Sales Report</h1>
            <a href="/admin/dashboard" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">← Back to Menu</a>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="p-4">Week Start Date</th>
                        <th class="p-4">Total Orders</th>
                        <th class="p-4 text-right">Total Earnings</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($weeklySales as $week)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-bold text-gray-700">{{ $week['start_date'] }}</td>
                        <td class="p-4">{{ $week['count'] }} Orders</td>
                        <td class="p-4 text-right font-bold text-green-600">${{ number_format($week['total'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-gray-500">No sales data found yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>