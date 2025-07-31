<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sales Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #000;
        }

        .receipt-container {
            border: 1px solid #ccc;
            padding: 30px;
            max-width: 900px;
            margin: auto;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .company-logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }

        .header {
            margin-bottom: 30px;
        }

        .company-info {
            font-size: 14px;
            color: #333;
        }

        .details-table,
        .items-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table td {
            padding: 6px 10px;
            vertical-align: top;
        }

        .items-table th,
        .items-table td,
        .summary-table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .items-table th {
            background-color: #f0f0f0;
        }

        .summary-table td {
            border: none;
        }

        .summary-wrapper {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .footer-note {
            margin-top: 50px;
            font-size: 13px;
            color: #555;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="receipt-container">

        <!-- Header & Company Info -->
        <div class="header text-center">
            @if ($base64Logo)
                <img src="{{ $base64Logo }}" alt="Company Logo" class="company-logo">
            @endif
            <h2>{{ $companyData->name }}</h2>
            <p class="company-info">
                {{ $companyData->address }}<br>
                TIN: {{ $companyData->TIN }}<br>
                Email: {{ $companyData->email }} |
                Website: {{ $companyData->webiste }}
            </p>
        </div>

        <!-- Receipt Details -->
        <table class="details-table">
            <tr>
                <td><strong style="color: #0000FF;">Receipt No:</strong> {{ $paymentData->id }}</td>
                <td><strong>Date:</strong> {{ \Carbon\Carbon::parse($paymentData->created_at)->format('l, F j, Y') }}
                </td>
            </tr>
            <tr>
                <td><strong>Sold To:</strong> {{ $customerData->name ?? 'N/A' }}</td>
                <td><strong>Sale ID:</strong> {{ $saleAutoId }}</td>
            </tr>
        </table>

        <!-- Item Table -->
        <h3>Items Sold</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Discount</th>
                    <th>Discount Value</th>
                    <th>Amount After Discount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $serial = 1;
                    $totalDiscount = 0;
                    $totalAmountWithDiscount = 0;
                @endphp
                @foreach (array_merge($receiptSalesOutOfStore->toArray(), $receiptSalesFromStore->toArray(), $salesReceiptFromServices->toArray()) as $item)
                    @php
                        $itemTotal = $item->quantity * ($item->amountPay ?? $item->amount);
                        $discountValue = ($itemTotal * $item->discount) / 100;
                        $amountAfterDiscount = $itemTotal - $discountValue;
                        $totalDiscount += $discountValue;
                        $totalAmountWithDiscount += $amountAfterDiscount;
                    @endphp
                    <tr>
                        <td>{{ $serial++ }}</td>
                        <td>{{ $item->product_name ?? $item->name }}</td>
                        <td>{{ number_format($item->quantity) }}</td>
                        <td class="text-end">{{ number_format($item->amountPay ?? $item->amount, 2) }}</td>
                        <td class="text-end">{{ $item->discount }}%</td>
                        <td class="text-end">{{ number_format($discountValue, 2) }}</td>
                        <td class="text-end">{{ number_format($amountAfterDiscount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end"><strong>Total Discount</strong></td>
                    <td class="text-end"><strong>{{ number_format($totalDiscount, 2) }}</strong></td>
                    <td class="text-end"><strong>{{ number_format($totalAmountWithDiscount, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Summary -->
        <div class="summary-wrapper">
            <table class="summary-table">
                <tr>
                    <td><strong>Total Paid + VAT (18%):</strong></td>
                    <td class="text-end" style="color: #007BFF;">
                        <strong>{{ number_format($paymentData->amount_paid, 2) }}</strong> TZS</td>
                </tr>
                <tr>
                    <td><strong>Payment Method:</strong></td>
                    <td class="text-end" style="color: #007BFF;"><strong>{{ $paymentData->payment_method }}</strong>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer-note">
            <p><em>Thank you for your business!</em></p>
            <p>This is a computer-generated receipt and does not require a signature.</p>
        </div>

    </div>

</body>

</html>
