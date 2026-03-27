<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; }
        .invoice-wrapper { padding: 40px; }

        .header { width: 100%; margin-bottom: 40px; }
        .header-left { float: left; }
        .header-right { float: right; text-align: right; }
        .clearfix { clear: both; }
        .company-name { font-size: 24px; font-weight: bold; color: #2d6a4f; }
        .company-details { font-size: 12px; color: #666; margin-top: 5px; line-height: 1.6; }
        .invoice-title h1 { font-size: 32px; color: #2d6a4f; text-transform: uppercase; }
        .invoice-title p { font-size: 12px; color: #666; margin-top: 4px; }

        .info-row { width: 100%; margin-bottom: 30px; }
        .info-left  { float: left;  width: 48%; }
        .info-right { float: right; width: 48%; }
        .info-box h4 { font-size: 11px; text-transform: uppercase; color: #999;
                       border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 8px; }
        .info-box p { font-size: 13px; line-height: 1.8; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        thead tr { background: #2d6a4f; color: white; }
        thead th { padding: 10px 12px; text-align: left; font-size: 12px; }
        tbody tr { border-bottom: 1px solid #f0f0f0; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        tbody td { padding: 10px 12px; font-size: 13px; }

        .totals { float: right; width: 280px; }
        .totals table { margin-bottom: 0; }
        .totals td { padding: 7px 12px; }
        .totals .total-row td { font-weight: bold; font-size: 15px;
                                background: #2d6a4f; color: white; }

        .footer { clear: both; margin-top: 60px; text-align: center;
                  font-size: 11px; color: #999;
                  border-top: 1px solid #eee; padding-top: 15px; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px;
                 font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .badge-delivered { background: #d4edda; color: #155724; }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
        .badge-shipped   { background: #cce5ff; color: #004085; }
        .badge-confirmed { background: #d1ecf1; color: #0c5460; }
        .badge-processing{ background: #e2d9f3; color: #432874; }
    </style>
</head>
<body>
<div class="invoice-wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="header-left">
            <div class="company-name">🌿 Biomer</div>
            <div class="company-details">
                Your Company Address<br>
                City, State, PIN<br>
                info@biomer.com | +91 XXXXXXXXXX
            </div>
        </div>
        <div class="header-right invoice-title">
            <h1>Invoice</h1>
            <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
            <p>
                <span class="badge badge-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
        </div>
        <div class="clearfix"></div>
    </div>

    {{-- Bill To & Ship To --}}
    <div class="info-row">
        <div class="info-left info-box">
            <h4>Bill To</h4>
            <p>
                <strong>{{ $order->name }}</strong><br>
                {{ $order->email }}<br>
                {{ $order->phone }}
            </p>
        </div>
        <div class="info-right info-box">
            <h4>Ship To</h4>
            <p>
                {{ $order->address }}<br>
                {{ $order->city }}, {{ $order->state }}<br>
                PIN: {{ $order->pincode }}
                @if($order->notes)
                    <br><em>Note: {{ $order->notes }}</em>
                @endif
            </p>
        </div>
        <div class="clearfix"></div>
    </div>

    {{-- Items Table --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>SKU</th>
                <th>Variation</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->sku ?? '—' }}</td>
                <td>{{ $item->variation_name ?? '—' }}</td>
                <td>₹{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₹{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <table>
            <tr>
                <td>Subtotal</td>
                <td align="right">₹{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Shipping</td>
                <td align="right">Free</td>
            </tr>
            <tr class="total-row">
                <td>Total</td>
                <td align="right">₹{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Thank you for your order! For queries contact us at info@biomer.com</p>
        <p>This is a computer generated invoice and does not require a signature.</p>
    </div>

</div>
</body>
</html>