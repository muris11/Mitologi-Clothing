<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Order $order)
    {
        // Load relationships needed for the invoice
        $order->load(['items', 'user', 'shippingAddress']);

        return view('invoices.show', compact('order'));
    }
}
