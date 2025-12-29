<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Customer;

// 1. HOME PAGE
Route::get('/', function () {
    return view('welcome');
});

// 2. CHECK-IN (Save Customer & Session)
Route::post('/checkin', function (Request $request) {
    $customer = Customer::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'table_number' => $request->table_number
    ]);
    
    // Save ID to session so we remember them
    session(['customer_id' => $customer->id]);
    
    return redirect('/menu');
});

// 3. MENU PAGE
Route::get('/menu', function () {
    if (!Schema::hasTable('menu_items')) return "Run php artisan migrate";
    
    $menuItems = MenuItem::all();
    
    // Check if the user has checked in
    $canOrder = session()->has('customer_id'); 

    // Pass the boolean $canOrder to the view
    return view('menu', [
        'menuItems' => $menuItems, 
        'canOrder' => $canOrder
    ]);
});

// 4. ORDER ITEM (Ajax Click)
Route::post('/order', function (Request $request) {
    // Get the current customer ID from the session
    $customerId = session('customer_id');

    if (!$customerId) {
        return response()->json(['message' => 'Session expired. Please check in again.'], 401);
    }

    Order::create([
        'item_name' => $request->item_name,
        'price' => $request->price,
        'customer_id' => $customerId // <--- LINKING ORDER TO CUSTOMER
    ]);

    return response()->json(['message' => 'Added to bill']);
});

// 5. PAYMENT PAGE (New!)
Route::get('/payment', function () {
    $customerId = session('customer_id');
    
    if (!$customerId) return redirect('/'); // Redirect home if no session

    // Get Customer Details
    $customer = Customer::find($customerId);
    
    // Get Their Orders
    $orders = Order::where('customer_id', $customerId)->get();
    
    // Calculate Total
    $total = $orders->sum('price');

    return view('payment', [
        'customer' => $customer,
        'orders' => $orders,
        'total' => $total
    ]);
});

// 6. SEEDER (Run http://127.0.0.1:8000/seed once to fill menu)
Route::get('/seed', function() {
    MenuItem::truncate(); // Clear old items so we don't get duplicates
    
    $items = [
        ['name' => 'Classic Burger', 'category' => 'food', 'price' => 10.50, 'description' => 'Beef patty with cheese'],
        ['name' => 'Spicy Chicken', 'category' => 'food', 'price' => 12.00, 'description' => 'Fried chicken with hot sauce'],
        ['name' => 'Margherita Pizza', 'category' => 'food', 'price' => 14.00, 'description' => 'Tomato, mozzarella, basil'],
        ['name' => 'Carbonara Pasta', 'category' => 'food', 'price' => 13.50, 'description' => 'Creamy sauce with bacon'],
        ['name' => 'Caesar Salad', 'category' => 'food', 'price' => 9.00, 'description' => 'Fresh romaine lettuce'],
        ['name' => 'Cola', 'category' => 'beverage', 'price' => 3.00, 'description' => 'Ice cold soda'],
        ['name' => 'Iced Coffee', 'category' => 'beverage', 'price' => 4.50, 'description' => 'Brewed coffee with milk'],
        ['name' => 'Lemonade', 'category' => 'beverage', 'price' => 3.50, 'description' => 'Freshly squeezed'],
        ['name' => 'Green Tea', 'category' => 'beverage', 'price' => 2.50, 'description' => 'Hot and soothing'],
    ];

    foreach($items as $item) { MenuItem::create($item); }
    return "Menu Updated! <a href='/menu'>Go to Menu</a>";
});

// 8. REMOVE SINGLE ITEM
Route::delete('/order/{id}', function ($id) {
    $order = Order::find($id);
    
    // Security: Only allow deleting if it belongs to the current customer
    if ($order && $order->customer_id == session('customer_id')) {
        $order->delete();
        return response()->json(['success' => true]);
    }
    
    return response()->json(['success' => false], 403);
});

// 9. CANCEL ENTIRE ORDER (Restart)
Route::post('/cancel-all', function () {
    $customerId = session('customer_id');
    
    if ($customerId) {
        // Delete all orders for this customer
        Order::where('customer_id', $customerId)->delete();
        
        // Remove the customer from the database (optional, keeps data clean)
        Customer::destroy($customerId);
        
        // Clear the session
        session()->forget(['customer_id', 'table_number']);
    }

    return redirect('/')->with('success', 'Order cancelled. Hope to see you again!');
});