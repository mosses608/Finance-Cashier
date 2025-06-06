<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .container {
            padding: 20px;
            border: 1px solid #ccc;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .bordered-box {
            border: 1px solid #ccc;
            padding: 10px;
            min-height: 80px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table th, table td {
            border: 1px solid #999;
            padding: 6px;
        }

        table th {
            background-color: #f0f8ff;
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-blue {
            color: #007BFF;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h3>Sales Receipt</h3>
            <div>
                <p><strong>Payment Date:</strong>
                    @if ($paymentData->status == 0)
                        <span class="text-blue">Pending Payment...</span>
                    @else
                        {{ \Carbon\Carbon::parse($paymentData->updated_at)->format('M d, Y') }}
                    @endif
                </p>
                <p><strong>Receipt #:</strong>
                    <span class="text-blue">{{ str_pad($saleAutoId, 4, '0', STR_PAD_LEFT) }}</span>
                </p>
            </div>
        </div>

        <!-- Addresses -->
        <div class="section">
            <div style="width: 48%; float: left;">
                <p><strong>From:</strong></p>
                <div class="bordered-box">
                    <p>Akili Soft Tech</p>
                    <p>Dar es salaam, Kigamboni</p>
                    <p>Dar es salaam, Tanzania</p>
                    <p>255 694 235 858 | support@akilisoft.com</p>
                </div>
            </div>
            <div style="width: 48%; float: right;">
                <p><strong>Sold To:</strong></p>
                <div class="bordered-box">
                    <p>{{ $customerData->name }}</p>
                    <p>{{ $customerData->address ?? '' }}</p>
                    <p>Tanzania</p>
                    <p>{{ $customerData->phone ?? '' }}</p>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit Price (TSH)</th>
                    <th>Total (TSH)</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAmount = 0; @endphp

                @foreach ($receiptSalesOutOfStore as $item)
                    @php
                        $lineTotal = $item->quantity * $item->amountPay;
                        $totalAmount += $lineTotal;
                    @endphp
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-center">{{ number_format($item->quantity) }}</td>
                        <td class="text-end">{{ number_format($item->amountPay, 2) }}</td>
                        <td class="text-end">{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach

                @foreach ($receiptSalesFromStore as $item)
                    @php
                        $lineTotal = $item->quantity * $item->amount;
                        $totalAmount += $lineTotal;
                    @endphp
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-center">{{ number_format($item->quantity) }}</td>
                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                        <td class="text-end">{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals and Payment Info -->
        <div class="section">
            <div style="width: 48%; float: left;">
                <p><strong>Payment Method:</strong>
                    @if ($paymentData->status == 0)
                        <span class="text-blue">Pending Payment</span>
                    @else
                        {{ $paymentData->payment_method }}
                    @endif
                </p>
                <p><strong>Amount Paid:</strong>
                    <span class="text-blue">{{ number_format($paymentData->amount_paid, 2) }} TSH</span>
                </p>
            </div>
            <div style="width: 48%; float: right;">
                <p><strong>Subtotal:</strong>
                    <span class="text-blue">{{ number_format($totalAmount, 2) }} TSH</span>
                </p>
                <p><strong>Tax Rate:</strong> <span class="text-blue">0%</span></p>
                <p><strong>Total Amount Due:</strong>
                    <span class="text-blue">{{ number_format($totalAmount, 2) }} TSH</span>
                </p>
            </div>
            <div style="clear: both;"></div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><em>Thank you for your business!</em></p>
        </div>
    </div>
</body>
</html>
