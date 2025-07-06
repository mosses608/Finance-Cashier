<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sales Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #000;
            background-color: #fff;
        }

        .container {
            width: 90%;
            margin: auto;
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

        table th,
        table td {
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

        .btn-download {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            float: right;
        }

        .btn-download i {
            margin-right: 5px;
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
                        <i class="fas fa-spinner fa-spin text-warning"></i>
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
                    <p>Dar es Salaam, Kigamboni</p>
                    <p>Tanzania</p>
                    <p>255 694 235 858 | support@akilisoft.com</p>
                </div>
            </div>
            <div style="width: 48%; float: right;">
                <p><strong>Sold To:</strong></p>
                <div class="bordered-box">
                    <p class="mb-0">Customer: {{ $customerData->name }}</p>
                    <p class="mb-0">Region: {{ $customerData->address ?? '' }}</p>
                    <p class="mb-0">Nation: Tanzania</p>
                    <p class="mb-0">Phone: {{ $customerData->phone ?? '' }}</p>
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
                    <th>Unit Price</th>
                    <th>Discount (TSH)</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalAmount = 0;
                    $totalDiscount = 0;
                @endphp

                @foreach ($receiptSalesOutOfStore as $item)
                    @php
                        $discount = $item->discount * $item->amountPay;
                        $lineTotal = $item->amountPay * $item->quantity - $discount;
                        $totalDiscount += $discount;
                        $totalAmount += $lineTotal;
                    @endphp
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-center">{{ number_format($item->quantity) }}</td>
                        <td class="text-end">{{ number_format($item->amountPay, 2) }}</td>
                        <td class="text-center">{{ number_format($discount, 2) }}</td>
                        <td class="text-end">{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach

                @foreach ($receiptSalesFromStore as $item)
                    @php
                        $lineTotal = $item->amount * $item->quantity;
                        $totalAmount += $lineTotal;
                    @endphp
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-center">{{ number_format($item->quantity) }}</td>
                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                        <td class="text-center">0.00</td>
                        <td class="text-end">{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach

                @foreach ($salesReceiptFromServices as $item)
                    @php
                        $discount = $item->discount * $item->amount;
                        $lineTotal = $item->amount * $item->quantity - $discount;
                        $totalDiscount += $discount;
                        $totalAmount += $lineTotal;
                    @endphp
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-center">{{ number_format($item->quantity) }}</td>
                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                        <td class="text-center">{{ number_format($discount, 2) }}</td>
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
                        <i class="fas fa-spinner fa-spin text-warning"></i>
                        <span class="text-blue">Pending</span>
                    @else
                        <i class="fas fa-check-circle text-success"></i>
                        <strong class="text-blue">{{ $paymentData->payment_method }}</strong>
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
                <p><strong>VAT (18%):</strong>
                    <span class="text-blue">{{ number_format($paymentData->tax, 2) }}</span>
                </p>
                <p><strong>Total Amount Due:</strong>
                    <span class="text-blue">{{ number_format($totalAmount + $paymentData->tax, 2) }} TSH</span>
                </p>
            </div>
            <div style="clear: both;"></div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><em>Thank you for your business!</em></p>
        </div>

        @php
            $encryptedReceiptId = Crypt::encrypt($saleAutoId);
        @endphp
    </div>
</body>

</html>
