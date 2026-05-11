<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Approved</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.5;">
    <h1>Purchase Order Approved</h1>
    <p>Dear {{ $purchaseOrder->supplier->name }},</p>
    <p>The purchase order <strong>#{{ $purchaseOrder->id }}</strong> has been approved by the procurement team.</p>
    <p>Order details:</p>
    <ul>
        <li>Supplier: {{ $purchaseOrder->supplier->name }}</li>
        <li>Total Amount: UGX {{ number_format($purchaseOrder->total_amount) }}</li>
        <li>Status: {{ ucfirst($purchaseOrder->status) }}</li>
    </ul>
    <h2>Items</h2>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->items as $item)
                <tr>
                    <td>{{ $item->product->sku }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>UGX {{ number_format($item->unit_cost) }}</td>
                    <td>UGX {{ number_format($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Thank you,<br>The Central Warehouse Procurement Team</p>
</body>
</html>
