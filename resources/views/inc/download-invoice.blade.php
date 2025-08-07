<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            margin: 20px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #007BFF;
        }

        .invoice-header {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin-bottom: 25px;
        }

        .invoice-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .invoice-col {
            flex: 0 0 33.33%;
            min-width: 250px;
        }

        .invoice-col h4 {
            margin: 4px 0;
            font-weight: normal;
        }

        .invoice-col strong {
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #007BFF;
            color: #fff;
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 6px;
            border: 1px solid #ccc;
        }

        tfoot td {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .qr-section {
            margin-top: 40px;
            text-align: center;
        }

        .qr-section img {
            margin-top: 10px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo-container img {
            max-height: 80px;
        }

        @media print {
            body {
                margin: 0;
            }

            .invoice-header {
                page-break-inside: avoid;
            }

            .qr-section {
                page-break-before: avoid;
            }
        }
    </style>
</head>

<body>

    <h2>Invoice</h2>

    <div class="logo-container">
        @if (isset($logoBase64))
            <img src="{{ $logoBase64 }}" alt="Company Logo">
        @endif
    </div>

    <div class="invoice-header">
        <div class="invoice-row">
            <div class="invoice-col">
                <h4><strong>Invoice #:</strong> {{ str_pad($profomaInvoiceId, 4, '0', STR_PAD_LEFT) }}</h4>
                <h4><strong>Issued:</strong> {{ \Carbon\Carbon::parse($issuesDate->created_at)->format('M d, Y') }}</h4>
            </div>

            <div class="invoice-col">
                <h4><strong>Bill From:</strong></h4>
                <h4>{{ $companyData->company_name }} - {{ $companyData->company_reg_no }}</h4>
                <h4>{{ $companyData->address }}</h4>
                <h4>TIN: {{ $companyData->tin }}</h4>
                <h4>Email: {{ $companyData->company_email }}</h4>
            </div>

            <div class="invoice-col">
                <h4><strong>Bill To:</strong></h4>
                <h4>{{ $customerDetails->customerName }}</h4>
                @if ($customerDetails->address)
                    <h4>{{ $customerDetails->address }}</h4>
                @endif
                <h4>TIN: {{ $customerDetails->TIN }}</h4>
                <h4>VRN: {{ $customerDetails->VRN ?? '—' }}</h4>
                <h4>Phone: {{ $customerDetails->phoneNumber }}</h4>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>S/N</th>
                <th>Item Name</th>
                <th>Unit Price (TSH)</th>
                <th>Quantity</th>
                <th>Discount (%)</th>
                <th>Discount Value (TSH)</th>
                <th>Total Amount (TSH)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subTotal = 0;
                $totalDiscount = 0;
            @endphp

            @foreach ($profomaInvoiceItems as $item)
                @php
                    $discountValue = (($item->unitPrice * $item->discount) / 100) * $item->quantity;
                    $lineTotal = $item->unitPrice * $item->quantity;
                    $subTotal += $lineTotal;
                    $totalDiscount += $discountValue;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->itemName }}</td>
                    <td>{{ number_format($item->unitPrice, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->discount }}</td>
                    <td>{{ number_format($discountValue, 2) }}</td>
                    <td>{{ number_format($lineTotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @php
                $finalAmount = $subTotal - $totalDiscount;
                $vat = $customerDetails->VRN ? $finalAmount * 0.18 : 0;
                $grandTotal = $finalAmount + $vat;
            @endphp
            <tr>
                <td colspan="6" class="text-end">Sub Total</td>
                <td>{{ number_format($subTotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="6" class="text-end">Total Discount</td>
                <td>{{ number_format($totalDiscount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="6" class="text-end">VAT ({{ $customerDetails->VRN ? '18%' : '0%' }})</td>
                <td>{{ number_format($vat, 2) }}</td>
            </tr>
            <tr>
                <td colspan="6" class="text-end"><strong>Grand Total (TSH)</strong></td>
                <td><strong>{{ number_format($grandTotal, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="qr-section">
        <p><strong>QR Code</strong></p>
        @if (isset($qrImageBase64))
            <img src="{{ $qrImageBase64 }}" width="150" height="150" alt="QR Code">
        @endif
    </div>

</body>

</html>
