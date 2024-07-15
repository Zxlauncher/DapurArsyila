<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function payment()
    {
        // Retrieve the cart items for the authenticated user
        $cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->get();
    
        $data = [];
    
        // Generate a unique order ID (integer)
        $orderId = time();  // Using current timestamp as a unique integer order ID
    
        // Set transaction details
        $data['transaction_details'] = [
            'order_id' => $orderId,
            'gross_amount' => 0, // Will be calculated below
        ];
    
        // Initialize total amount
        $totalAmount = 0;
    
        // Map cart items to Midtrans item details format and calculate total amount
        $items = $cart->map(function ($item) use (&$totalAmount) {
            $product = Product::find($item->product_id);
            $itemTotal = $item->price * $item->quantity;
            $totalAmount += $itemTotal;
            return [
                'id' => $product->id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => $product->title,
            ];
        })->toArray();
    
        $data['item_details'] = $items;
    
        // Apply coupon discount if available
        if (session('coupon')) {
            $couponValue = session('coupon')['value'];
            $totalAmount -= $couponValue;
            $data['item_details'][] = [
                'id' => 'discount',
                'price' => -$couponValue,
                'quantity' => 1,
                'name' => 'Discount'
            ];
        }
    
        // Update the gross amount with the calculated total amount
        $data['transaction_details']['gross_amount'] = $totalAmount;
    
        // Update cart with order_id
        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $orderId]);
    
        // Get Snap Token
        $snapToken = Snap::getSnapToken($data);
    
        // Return view with snap token
        return view('payment.midtrans', ['snapToken' => $snapToken]);
    }
    
}
