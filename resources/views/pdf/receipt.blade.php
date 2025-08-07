<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sales Receipt</title>
    <style>
        body {
            position: relative;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            margin: 20px;
            color: #000;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            max-height: 70px;
        }

        .company-info {
            text-align: right;
            font-size: 13px;
        }

        .title {
            text-align: center;
            color: #007BFF;
            font-weight: bold;
            margin: 10px 0;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .details div {
            width: 48%;
        }

        .details p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #ccc;
            /* color: #fff; */
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        tfoot td {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .text-end {
            text-align: right;
        }

        .summary-box {
            width: 300px;
            float: right;
            margin-top: 20px;
        }

        .summary-box table {
            width: 100%;
            border: none;
        }

        .summary-box td {
            padding: 5px;
            border: none;
        }

        .footer-note {
            clear: both;
            margin-top: 60px;
            font-size: 13px;
            color: #555;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 12px;
        }

        .footer-bottom div {
            width: 48%;
        }

        .watermark {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: 0;
            width: 500px;
            height: 700px;
            text-align: center;
            pointer-events: none;
        }

        .watermark img {
            width: 100%;
            height: 100%;
        }

        .content {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body>

    <div class="watermark">
        <img src="{{ $base64Logo }}" alt="Watermark">
    </div>

    <div class="content">
        <div class="header-section">
            <div class="logo">
                @if ($base64Logo)
                    <img src="{{ $base64Logo }}" alt="Company Logo" class="logo">
                @endif
            </div>
            <div class="company-info">
                <strong>{{ $companyData->name }}</strong><br>
                {{ $companyData->address ?? '' }}<br>
                TIN: {{ $companyData->TIN }}<br>
                Email: {{ $companyData->email ?? '' }}<br>
                Website: {{ $companyData->webiste ?? '' }}
            </div>
        </div>

        <div class="title">SALES RECEIPT</div>

        <div class="details">
            <div>
                <p><strong>Receipt No:</strong> {{ $paymentData->id }}</p>
                <p><strong>Sold To:</strong> {{ $customerData->name ?? 'N/A' }}</p>
            </div>
            <div class="text-start">
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($paymentData->created_at)->format('l, F j, Y') }}</p>
                <p><strong>Sale ID:</strong> {{ $saleAutoId }}</p>
            </div>
        </div>

        <table>
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
                    <td colspan="5" class="text-end">Total Discount</td>
                    <td class="text-end">{{ number_format($totalDiscount, 2) }}</td>
                    <td class="text-end">{{ number_format($totalAmountWithDiscount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="summary-box">
            <table>
                <tr>
                    <td>Sub Total:</td>
                    <td class="text-end" style="color: #007BFF;">
                        <strong>{{ number_format($totalAmountWithDiscount, 2) }}</strong> TZS
                    </td>
                </tr>
                @if ($hasVrn)
                    @php
                        $vat = $totalAmountWithDiscount * 0.18;
                    @endphp
                    <tr>
                        <td>VAT (18%):</td>
                        <td class="text-end" style="color: #007BFF;">
                            <strong>{{ number_format($vat, 2) }}</strong> TZS
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>VAT (18%):</td>
                        <td class="text-end" style="color: #007BFF;">
                            <strong>{{ number_format(0, 2) }}</strong> TZS
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>Total Paid + VAT (18%):</td>
                    <td class="text-end" style="color: #007BFF;">
                        <strong>{{ number_format($paymentData->amount_paid, 2) }}</strong> TZS
                    </td>
                </tr>
                <tr>
                    <td>Payment Method:</td>
                    <td class="text-end" style="color: #007BFF;">
                        <strong>{{ $paymentData->payment_method }}</strong>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer-note">
            <p><em>Thank you for your business!</em></p>
            <p>This is a computer-generated receipt and does not require a signature.</p>
            <div class="footer-bottom">
                <div>Printed on: {{ now()->format('F j, Y g:i A') }}</div>
                {{-- <div class="text-end">Printed by: {{ auth()->user()->name }}</div> --}}
            </div>
        </div>
    </div>
</body>

</html>
