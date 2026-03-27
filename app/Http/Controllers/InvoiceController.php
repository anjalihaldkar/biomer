<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
{
    $orders = Order::with(['customer', 'items'])
        ->latest()
        ->paginate(20);

    return view('dashboard.invoices.index', compact('orders'));
}
    // ── Admin Download ─────────────────────────────────────────────────
    public function downloadAdmin($orderNumber)
    {
        $order = Order::with(['items.product', 'customer'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $pdf = Pdf::loadView('invoices.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    // ── Customer Download ──────────────────────────────────────────────
    public function downloadCustomer($orderNumber)
    {
        $order = Order::with(['items.product', 'customer'])
            ->where('order_number', $orderNumber)
            ->where('customer_id', auth()->guard('customer')->id())
            ->firstOrFail();

        $pdf = Pdf::loadView('invoices.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}