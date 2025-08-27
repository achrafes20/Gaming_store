<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>

        body,
        body *:not(html):not(style):not(br):not(tr):not(code) {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            margin: 0;
            padding: 0;
        }

        body {
            -webkit-text-size-adjust: none;
            background-color: #f7f9fc;
            color: #4a5568;
            line-height: 1.4;
            width: 100% !important;
            padding: 20px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }


        .header {
            text-align: center;
            padding: 20px 0;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border-radius: 10px 10px 0 0;
            color: white;
        }

        .logo {
            height: 80px;
            margin-bottom: 15px;
        }

        .brand-name {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .tagline {
            font-style: italic;
            margin-top: 5px;
            opacity: 0.9;
        }


        .content {
            background-color: #ffffff;
            border-radius: 0 0 10px 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .greeting {
            font-size: 22px;
            color: #2d3748;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .confirmation-banner {
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            color: white;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .confirmation-banner h2 {
            margin: 0;
            font-size: 24px;
        }

        .order-number {
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 5px;
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
        }


        .section {
            margin-bottom: 25px;
            padding: 20px;
            border-radius: 8px;
            background: #f8f9fa;
            border-left: 4px solid #6a11cb;
        }

        .section-title {
            color: #2d3748;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
        }

        .section-title svg {
            margin-right: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #4a5568;
        }

        .info-value {
            color: #2d3748;
        }


        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .order-table th {
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            color: white;
            text-align: left;
            padding: 12px 15px;
            font-weight: bold;
        }

        .order-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .order-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .order-table tr:hover {
            background-color: #edf2f7;
        }


        .summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .total-row {
            font-weight: bold;
            font-size: 18px;
            color: #2d3748;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #e2e8f0;
        }


        .footer {
            text-align: center;
            padding: 20px 0;
            color: #a0aec0;
            font-size: 14px;
        }

        .social-links {
            margin: 15px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #6a11cb;
            text-decoration: none;
            font-weight: bold;
        }

        .game-icon {
            display: inline-block;
            margin: 0 5px;
            font-size: 20px;
        }


        @keyframes confetti {
            0% { transform: translateY(0) rotate(0); opacity: 1; }
            100% { transform: translateY(100px) rotate(360deg); opacity: 0; }
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f00;
            opacity: 0;
        }

        
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .order-table {
                font-size: 14px;
            }

            .order-table th,
            .order-table td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand-name">NextLevelGaming</div>
            <div class="tagline">Your destination for ultimate gaming!</div>
        </div>

        <div class="content">
            <div class="greeting">Hello {{ $order->name }},</div>

            <div class="confirmation-banner">
                <h2>Order Confirmed!</h2>
                <div class="order-number">#{{ $order->id }}</div>
            </div>

            <div class="section">
                <div class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Customer Information
                </div>
                <div class="info-grid">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $order->name }}</div>

                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $order->email }}</div>

                    <div class="info-label">Address:</div>
                    <div class="info-value">{{ $order->address }}</div>

                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $order->phone }}</div>

                    <div class="info-label">Note:</div>
                    <div class="info-value">{{ $order->note ?: 'No special instructions' }}</div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                    Order Details
                </div>

                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderDetails as $detail)
                        <tr>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ number_format($detail->product->price, 2) }} Dh</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->quantity * $detail->product->price, 2) }} Dh</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="summary">
                <div class="section-title">Order Summary</div>

                <div class="summary-row">
                    <div>Subtotal</div>
                    <div>{{ number_format($order->orderDetails->sum(fn($x) => $x->product->price * $x->quantity), 2) }} Dh</div>
                </div>

                <div class="summary-row">
                    <div>Shipping</div>
                    <div style="margin-left: 5px">FREE</div>
                </div>

                <div class="summary-row">
                    <div>Tax</div>
                    <div style="margin-left: 5px">0.00 Dh</div>
                </div>

                @if($order->discount > 0)
                <div class="summary-row">
                    <div>Discount</div>
                    <div style="margin-left: 5px">-{{ number_format($order->discount, 2) }} Dh</div>
                </div>
                @endif

                <div class="summary-row total-row">
                    <div>TOTAL </div>
                    <div style="margin-left: 5px">{{ number_format($order->orderDetails->sum(fn($x) => $x->product->price * $x->quantity) - $order->discount, 2) }} Dh</div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <p>Thank you for shopping with us! 🎮</p>
                <p><strong>The NextLevelGaming Team</strong></p>


            </div>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} NextLevelGaming. All rights reserved.</p>
            <p>You're receiving this email because you placed an order on our website.</p>
            <div>
                <span class="game-icon">🎮</span>
                <span class="game-icon">🕹️</span>
                <span class="game-icon">👾</span>
            </div>
        </div>
    </div>

    <script>
        // Small confetti effect to celebrate the order
        document.addEventListener('DOMContentLoaded', function() {
            const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'];
            const container = document.querySelector('.confirmation-banner');

            for (let i = 0; i < 20; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.top = Math.random() * 100 + '%';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 10 + 5 + 'px';
                confetti.style.height = Math.random() * 10 + 5 + 'px';
                confetti.style.animation = `confetti ${Math.random() * 2 + 1}s ease-out forwards`;
                container.appendChild(confetti);
            }
        });
    </script>
</body>
</html>
