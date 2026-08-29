<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f7f9fc; color: #4a5568; margin: 0; padding: 20px 0; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { text-align: center; padding: 20px 0; background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); border-radius: 10px 10px 0 0; color: white; }
        .brand-name { font-size: 28px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
        .content { background-color: #ffffff; border-radius: 0 0 10px 10px; padding: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .confirmation-banner { background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%); padding: 15px; border-radius: 8px; text-align: center; color: white; margin-bottom: 25px; }
        .order-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .order-table th { background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%); color: white; text-align: left; padding: 12px 15px; }
        .order-table td { padding: 12px 15px; border-bottom: 1px solid #e2e8f0; }
        .summary { background: #f8f9fa; border-radius: 8px; padding: 20px; margin-top: 20px; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e2e8f0; }
        .total-row { font-weight: bold; font-size: 18px; color: #2d3748; }
        .footer { text-align: center; padding: 20px 0; color: #a0aec0; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand-name">NextLevelGaming</div>
        </div>
        <div class="content">
            <p>Hello {{ $order['name'] }},</p>

            <div class="confirmation-banner">
                <h2>Order Confirmed!</h2>
                <div>#{{ $order['order_id'] }}</div>
            </div>

            <table class="order-table">
                <thead>
                    <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
                </thead>
                <tbody>
                    @foreach ($order['items'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ number_format($item['price'], 2) }}Dh</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['price'] * $item['quantity'], 2) }}Dh</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary">
                @if($order['discount'] > 0)
                <div class="summary-row">
                    <div>Discount</div>
                    <div>-{{ number_format($order['discount'], 2) }}Dh</div>
                </div>
                @endif
                <div class="summary-row total-row">
                    <div>TOTAL</div>
                    <div>{{ number_format($order['total'] - $order['discount'], 2) }}Dh</div>
                </div>
            </div>

            <p style="text-align:center; margin-top:30px;">Thank you for shopping with us! 🎮<br><strong>The NextLevelGaming Team</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} NextLevelGaming. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
