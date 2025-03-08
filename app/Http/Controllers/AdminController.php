<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminController extends Controller
{
    // Protect all methods with auth middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show all orders
    public function index()
    {
        $orders = Order::with('products')->get();
        return view('admin.index', compact('orders'));
    }

    // Show edit form for an order
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.edit', compact('order'));
    }

    // Update an order
    public function update(Request $request, $id)
    {
        $request->validate([
            'recipient_id' => 'required|regex:/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]+$/',
            'buyer_name' => 'required|string',
            'buyer_email' => 'required|email',
            'phone_number' => 'required|string',
            'note' => 'nullable|string',
            'buyer_id' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png|max:2048',
            'product_name.*' => 'required|string',
            'price.*' => 'required|numeric|min:0',
            'quantity.*' => 'required|integer|min:1',
        ], [
            'recipient_id.regex' => 'Recipient ID must contain both letters and numbers.',
            'buyer_email.email' => 'Please enter a valid email address.',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'recipient_id' => $request->recipient_id,
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $request->buyer_email,
            'phone_number' => $request->phone_number,
            'note' => $request->note,
            'buyer_id' => $request->buyer_id,
            'attachment' => $request->attachment,
            'ip_address' => $request->ip_address,
        ]);

        // Delete existing products and recreate
        $order->products()->delete();
        $totalAmount = 0;
        foreach ($request->products as $product) {
            $order->products()->create([
                'product_name' => $product['product_name'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'total_price' => $product['total_price'],
            ]);
            $totalAmount += $product['total_price'];
        }

        return redirect()->route('admin.index')->with('success', 'Order updated successfully!');
    }

    // Delete an order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.index')->with('success', 'Order deleted!');
    }
}
