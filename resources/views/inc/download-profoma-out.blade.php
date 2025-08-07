<!DOCTYPE html>
<html>

<head>
    <title>Proforma Invoice</title>
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
            position: relative;
            z-index: 1;
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
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }

        .invoice-meta {
            text-align: right;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .bill-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .bill-column {
            width: 48%;
        }

        .bill-column h4 {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            position: relative;
            z-index: 1;
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
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .total-box {
            width: 300px;
            float: right;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }

        .total-box table {
            width: 100%;
            border: none;
        }

        .total-box td {
            padding: 5px;
            border: none;
        }

        .bank-info {
            margin-top: 40px;
            position: relative;
            z-index: 1;
        }

        .bank-info p {
            margin: 3px 0;
        }

        .qr-section {
            text-align: center;
            margin-top: 60px;
            position: relative;
            z-index: 1;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
            position: relative;
            z-index: 1;
        }


        .watermark {
            position: absolute;
            /* Use absolute instead of fixed */
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            z-index: 0;
            width: 500px;
            text-align: center;
            pointer-events: none;
        }

        .watermark img {
            width: 100%;
            height: auto;
        }

        .content {
            position: relative;
            z-index: 1;
        }


        @media print {
            .qr-section {
                page-break-before: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ $logoBase64 }}" alt="Watermark">
    </div>

    <div class="content">
        <div class="header-section">
            <div class="logo">
                @if (isset($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Company Logo" class="logo">
                @endif
            </div>
            <div class="company-info">
                <strong style="text-transform: uppercase;">{{ $companyData->company_name }}</strong><br>
                {{ $companyData->address }}, Tanzania<br>
                {{ $companyData->company_reg_no }}<br>
                {{ $companyData->company_email }}<br>
                TIN: {{ $companyData->tin }}<br>
                VRN: {{ $companyData->vrn ?? '—' }}
            </div>
        </div>

        <div class="title">PROFORMA INVOICE</div>

        <div class="invoice-meta">
            <strong>Invoice No:</strong> S/{{ str_pad($profomaInvoiceId, 5, '0', STR_PAD_LEFT) }}<br>
            <strong>Date:</strong> {{ \Carbon\Carbon::parse($issuesDate->created_at)->format('jS F Y') }}<br>
            <strong>Sale ID:</strong> {{ $saleAutoId ?? 'N/A' }}
        </div>

        <div class="bill-section">
            <div class="bill-column">
                <h4><strong style="color: #0000FF;">From</strong></h4>
                <h4>{{ $companyData->company_name }} ({{ $companyData->company_reg_no }})</h4>
                <h4>{{ $companyData->address }}</h4>
                <h4>Email: {{ $companyData->company_email }}</h4>
                <h4>TIN: {{ $companyData->tin }}</h4>
            </div>
            <hr>
            <div class="bill-column">
                <h4><strong style="color: #0000FF;">Bill To</strong></h4>
                <h4>{{ $customerDetails->customerName }}</h4>
                @if ($customerDetails->address)
                    <h4>{{ $customerDetails->address }}</h4>
                @endif
                <h4>TIN: {{ $customerDetails->TIN }}</h4>
                <h4>VRN: {{ $customerDetails->VRN ?? '—' }}</h4>
                <h4>Phone: {{ $customerDetails->phoneNumber }}</h4>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Item Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Discount (%)</th>
                    <th>Discount Value</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDiscount = 0;
                    $totalAmountWithoutDiscount = 0;
                @endphp
                @foreach ($profomaInvoiceItems as $item)
                    @php
                        $discountValue = $item->quantity * $item->discount;
                        $lineTotal = $item->unitPrice * $item->quantity;
                        $totalDiscount += $discountValue;
                        $totalAmountWithoutDiscount += $lineTotal;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->itemName }}</td>
                        <td>{{ number_format($item->unitPrice, 2) }}</td>
                        <td>{{ number_format($item->quantity, 2) }}</td>
                        <td>{{ $item->discount }}</td>
                        <td>{{ number_format($discountValue, 2) }}</td>
                        <td>{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $finalAmount = $totalAmountWithoutDiscount - $totalDiscount;
            $vat = $customerDetails->VRN ? $finalAmount * 0.18 : 0;
            $grandTotal = $finalAmount + $vat;
        @endphp

        <div class="total-box">
            <table>
                <tr>
                    <td class="text-right">Sub Total:</td>
                    <td class="text-right">{{ number_format($totalAmountWithoutDiscount, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right">Total Discount:</td>
                    <td class="text-right">{{ number_format($totalDiscount, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right">VAT ({{ $customerDetails->VRN ? '18%' : '0%' }}):</td>
                    <td class="text-right">{{ number_format($vat, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Grand Total (TZS):</strong></td>
                    <td class="text-right"><strong>{{ number_format($grandTotal, 2) }}</strong></td>
                </tr>
                <tr>
                    <td class="text-right" colspan="2" style="color: blue;"><strong>Balance Due: TZS
                            {{ number_format($grandTotal, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="bank-info">
            <p><strong>Account Name:</strong> {{ $bankInformation->account_name }}</p>
            <p><strong>Bank Name:</strong> {{ $bankInformation->bank_name }}</p>
            <p><strong>Account Number:</strong> {{ $bankInformation->account_number }}</p>
            <p><strong>Bank Code:</strong> {{ $bankInformation->bank_code ?? '—' }}</p>
            <p><strong>Swift Code:</strong> {{ $bankInformation->swift_code ?? '—' }}</p>
        </div>

        <div class="qr-section">
            <p><strong>QR Code</strong></p>
            @if (isset($qrImageBase64))
                <img src="{{ $qrImageBase64 }}" width="150" height="150" alt="QR Code">
            @endif
        </div>

        <div class="footer">
            <p>Thank you for doing business with us.</p>
            <p>{{ $companyData->company_name }} | {{ $companyData->company_email }} | {{ $companyData->address }}</p>
        </div>
    </div>

</body>

</html>
