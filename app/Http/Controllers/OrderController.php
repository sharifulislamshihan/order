<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function showForm()
    {
        return view('order');
    }

    public function submitOrder(Request $request)
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

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('uploads', 'public');
        }

        $order = Order::create([
            'recipient_id' => $request->recipient_id,
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $request->buyer_email,
            'phone_number' => $request->phone_number,
            'note' => $request->note,
            'buyer_id' => $request->buyer_id,
            'attachment' => $attachmentPath,
            'ip_address' => $request->ip(),
        ]);

        foreach ($request->product_name as $index => $name) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_name' => $name,
                'price' => $request->price[$index],
                'quantity' => $request->quantity[$index],
                'total_price' => $request->price[$index] * $request->quantity[$index],
            ]);
        }

        return response()->json(['message' => 'Order submitted successfully']);
    }
}
