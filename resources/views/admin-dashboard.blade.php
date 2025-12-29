<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">🍔 Menu Management</h1>
            <div class="space-x-2">
                <a href="/admin/sales" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">📊 View Sales</a>
                <a href="/admin/logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow mb-8">
            <h2 class="text-xl font-bold mb-4">Add New Item</h2>
            <form action="/admin/menu" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @csrf
                <input type="text" name="name" placeholder="Item Name" class="border p-2 rounded" required>
                <select name="category" class="border p-2 rounded">
                    <option value="food">Food</option>
                    <option value="beverage">Beverage</option>
                </select>
                <input type="number" step="0.01" name="price" placeholder="Price" class="border p-2 rounded" required>
                <input type="text" name="image" placeholder="Image URL" class="border p-2 rounded">
                <input type="text" name="description" placeholder="Description" class="border p-2 rounded">
                <button class="bg-green-600 text-white p-2 rounded font-bold md:col-span-5 hover:bg-green-700">Add Item</button>
            </form>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3">Image</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Category</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menuItems as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3"><img src="{{ $item->image }}" class="w-10 h-10 object-cover rounded"></td>
                        <td class="p-3 font-bold">{{ $item->name }}</td>
                        <td class="p-3"><span class="px-2 py-1 text-xs rounded {{ $item->category=='food'?'bg-orange-100 text-orange-800':'bg-blue-100 text-blue-800' }}">{{ strtoupper($item->category) }}</span></td>
                        <td class="p-3">${{ $item->price }}</td>
                        <td class="p-3">
                            <form action="/admin/menu/{{ $item->id }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 font-bold">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>