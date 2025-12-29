<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon; // Required for time calculation

// ====================================================
//                 CUSTOMER ROUTES
// ====================================================

// 1. HOME & CHECK-IN
Route::get('/', function () { 
    return view('welcome'); 
});

Route::post('/checkin', function (Request $request) {
    $request->validate(['name'=>'required', 'table_number'=>'required']);
    
    $customer = Customer::create([
        'name' => $request->name,
        'phone' => $request->phone, 
        'table_number' => $request->table_number
    ]);

    session(['customer_id' => $customer->id, 'table_number' => $request->table_number]);
    return redirect('/menu');
});

// 2. MENU (Public - No Tracking Button)
Route::get('/menu', function () {
    if (!Schema::hasTable('menu_items')) return "Run migrate first";
    
    return view('menu', [
        'menuItems' => MenuItem::all(),
        'canOrder' => session()->has('customer_id')
    ]);
});

// 3. ORDERING
Route::post('/order', function (Request $request) {
    if (!session('customer_id')) return response()->json(['message' => 'Session expired'], 401);
    
    Order::create([
        'item_name' => $request->item_name,
        'price' => $request->price,
        'customer_id' => session('customer_id'),
        'status' => 'pending'
    ]);
    
    return response()->json(['message' => 'Added']);
});

// 4. REMOVE SINGLE ITEM
Route::delete('/order/{id}', function ($id) {
    $order = Order::find($id);
    if ($order && $order->customer_id == session('customer_id')) {
        $order->delete();
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 403);
});

// 5. PAYMENT & RECEIPT
Route::get('/payment', function () {
    if (!session('customer_id')) return redirect('/');
    
    $orders = Order::where('customer_id', session('customer_id'))->get();
    
    return view('payment', [
        'customer' => Customer::find(session('customer_id')),
        'orders' => $orders,
        'total' => $orders->sum('price')
    ]);
});

Route::post('/pay', function (Request $request) {
    $cid = session('customer_id');
    
    if(!$cid) return redirect('/');

    // Update Orders with Payment Method and mark as Completed
    Order::where('customer_id', $cid)->update([
        'status' => 'completed', 
        'payment_method' => $request->payment_method
    ]);

    $customer = Customer::find($cid);
    $orders = Order::where('customer_id', $cid)->get();
    $total = $orders->sum('price');
    $method = $request->payment_method;

    session()->forget(['customer_id', 'table_number']);

    return view('receipt', compact('customer', 'orders', 'total', 'method'));
});

// ====================================================
//                  ADMIN ROUTES
// ====================================================

// 1. ADMIN LOGIN
Route::get('/admin/login', function () {
    return view('admin-login');
});

Route::post('/admin/login', function (Request $request) {
    if ($request->username === 'admin' && $request->password === 'admin123') {
        session(['is_admin' => true]);
        return redirect('/admin/dashboard');
    }
    return back()->with('error', 'Invalid credentials');
});

Route::get('/admin/logout', function () {
    session()->forget('is_admin');
    return redirect('/admin/login');
});

// 2. ADMIN DASHBOARD
Route::get('/admin/dashboard', function () {
    if (!session('is_admin')) return redirect('/admin/login');
    return view('admin-dashboard', ['menuItems' => MenuItem::all()]);
});

Route::post('/admin/menu', function (Request $request) {
    if (!session('is_admin')) return redirect('/admin/login');
    MenuItem::create($request->all());
    return back()->with('success', 'Item Added!');
});

Route::delete('/admin/menu/{id}', function ($id) {
    if (!session('is_admin')) return redirect('/admin/login');
    MenuItem::destroy($id);
    return back()->with('success', 'Item Deleted!');
});

Route::get('/admin/sales', function () {
    if (!session('is_admin')) return redirect('/admin/login');

    $sales = Order::where('status', 'completed')
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('W');
        });

    $weeklySales = [];
    foreach ($sales as $week => $orders) {
        $weeklySales[] = [
            'week' => $week,
            'count' => $orders->count(),
            'total' => $orders->sum('price'),
            'start_date' => Carbon::now()->setISODate(Carbon::now()->year, $week)->startOfWeek()->format('d M Y')
        ];
    }

    return view('admin-sales', ['weeklySales' => $weeklySales]);
});

// 3. AUTOMATED KITCHEN TRACKER (Admin Only)
Route::get('/kitchen', function () {
    if (!session('is_admin')) return redirect('/admin/login');

    // --- NEW FAST AUTOMATION LOGIC (Seconds) ---
    $activeOrders = Order::where('status', '!=', 'completed')->get();
    
    foreach($activeOrders as $order) {
        // Calculate SECONDS since order was created
        $seconds = $order->created_at->diffInSeconds(now());

        // 10 Seconds -> Move to Cooking
        if ($seconds >= 10 && $order->status == 'pending') {
            $order->status = 'cooking';
            $order->save();
        }
        // 20 Seconds -> Move to Ready
        if ($seconds >= 20 && $order->status == 'cooking') {
            $order->status = 'ready';
            $order->save();
        }
    }

        // Show ALL orders, even completed ones
        $orders = Order::orderBy('created_at', 'desc')->get();

    // Prevent Browser Caching so it refreshes correctly
    return response()
        ->view('kitchen', ['orders' => $orders])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
});

// Manual override (Optional, in case Admin wants to click buttons)
Route::post('/kitchen/update/{id}', function ($id, Request $request) {
    if (!session('is_admin')) return response()->json(['error' => 'Unauthorized'], 401);

    $order = Order::find($id);
    if ($order) {
        $order->status = $request->status;
        $order->save();
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 404);
});

// ==========================================
// SEEDER (20 Items)
// ==========================================
Route::get('/seed', function() {
    MenuItem::truncate(); 
    
    $items = [
        ['name' => 'Classic Burger', 'category' => 'food', 'price' => 10.50, 'description' => 'Juicy beef patty with cheddar cheese.', 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&q=80'],
        ['name' => 'Spicy Chicken', 'category' => 'food', 'price' => 12.00, 'description' => 'Crispy fried chicken with hot sauce.', 'image' => 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?w=500&q=80'],
        ['name' => 'Margherita Pizza', 'category' => 'food', 'price' => 14.00, 'description' => 'Wood-fired pizza with basil.', 'image' => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=500&q=80'],
        ['name' => 'Caesar Salad', 'category' => 'food', 'price' => 9.00, 'description' => 'Romaine lettuce with parmesan.', 'image' => 'https://images.unsplash.com/photo-1550304943-4f24f54ddde9?w=500&q=80'],
        ['name' => 'Grilled Steak', 'category' => 'food', 'price' => 22.50, 'description' => 'Premium ribeye steak.', 'image' => 'https://images.unsplash.com/photo-1600891964092-4316c288032e?w=500&q=80'],
        ['name' => 'Creamy Pasta', 'category' => 'food', 'price' => 13.50, 'description' => 'Fettuccine alfredo white sauce.', 'image' => 'https://images.unsplash.com/photo-1645112411341-6c4fd023714a?w=500&q=80'],
        ['name' => 'Street Tacos', 'category' => 'food', 'price' => 8.50, 'description' => 'Three soft shell beef tacos.', 'image' => 'https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?w=500&q=80'],
        ['name' => 'Sushi Platter', 'category' => 'food', 'price' => 18.00, 'description' => 'Assorted fresh sushi rolls.', 'image' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=500&q=80'],
        ['name' => 'Club Sandwich', 'category' => 'food', 'price' => 11.50, 'description' => 'Turkey, bacon, and lettuce.', 'image' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=500&q=80'],
        ['name' => 'Fish and Chips', 'category' => 'food', 'price' => 15.00, 'description' => 'Crispy battered fish with fries.', 'image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=500&q=80'],

        ['name' => 'Ice Cola', 'category' => 'beverage', 'price' => 3.00, 'description' => 'Ice cold refreshing soda.', 'image' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&q=80'],
        ['name' => 'Iced Coffee', 'category' => 'beverage', 'price' => 4.50, 'description' => 'Brewed coffee over ice.', 'image' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=500&q=80'],
        ['name' => 'Lemonade', 'category' => 'beverage', 'price' => 3.50, 'description' => 'Freshly squeezed lemons.', 'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=500&q=80'],
        ['name' => 'Orange Juice', 'category' => 'beverage', 'price' => 4.00, 'description' => 'Cold pressed orange juice.', 'image' => 'https://images.unsplash.com/photo-1613478223719-2ab802602423?w=500&q=80'],
        ['name' => 'Strawberry Shake', 'category' => 'beverage', 'price' => 5.50, 'description' => 'Creamy strawberry milkshake.', 'image' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500&q=80'],
        ['name' => 'Green Tea', 'category' => 'beverage', 'price' => 2.50, 'description' => 'Hot soothing green tea.', 'image' => 'https://images.unsplash.com/photo-1627435601361-ec25f5b1d0e5?w=500&q=80'],
        ['name' => 'Mineral Water', 'category' => 'beverage', 'price' => 1.50, 'description' => 'Still spring water.', 'image' => 'https://images.unsplash.com/photo-1564419320461-6870880221ad?w=500&q=80'],
        ['name' => 'Mango Smoothie', 'category' => 'beverage', 'price' => 5.00, 'description' => 'Fresh mango blend.', 'image' => 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=500&q=80'],
        ['name' => 'Peach Iced Tea', 'category' => 'beverage', 'price' => 3.50, 'description' => 'Sweet tea with peach.', 'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&q=80'],
        ['name' => 'Double Espresso', 'category' => 'beverage', 'price' => 3.00, 'description' => 'Strong shot of coffee.', 'image' => 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=500&q=80'],
    ];

    foreach($items as $item) { MenuItem::create($item); }
    return "Menu Updated! <a href='/menu'>Go to Menu</a>";
});

// DEBUG ROUTE - DELETE LATER
Route::get('/debug-orders', function () {
    $allOrders = App\Models\Order::all();
    return $allOrders; // This will show raw JSON data of all orders
});