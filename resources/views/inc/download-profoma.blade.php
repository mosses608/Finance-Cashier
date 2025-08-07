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
        }

        .invoice-meta {
            text-align: right;
            margin-bottom: 20px;
        }

        .bill-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
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
        }

        th {
            background-color: #ccc;
            color: #000;
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

        .text-right {
            text-align: right;
        }

        .bank-info {
            margin-top: 40px;
        }

        .bank-info p {
            margin: 3px 0;
        }

        .total-box {
            width: 300px;
            float: right;
            margin-top: 20px;
        }

        .total-box table {
            width: 100%;
            border: none;
        }

        .total-box td {
            padding: 5px;
            border: none;
        }

        .qr-section {
            text-align: center;
            margin-top: 60px;
        }

        .watermark {
            position: absolute;
            /* Use absolute instead of fixed */
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: 0;
            width: 500px;
            height: 500px;
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
                VRN: {{ $companyData->vrn ?? ' ' }}
            </div>
        </div>

        <div class="title">PROFORMA INVOICE</div>

        <div class="invoice-meta">
            <strong>Invoice No:</strong> S/{{ str_pad($profomaInvoiceId, 5, '0', STR_PAD_LEFT) }}<br>
            <strong>Date:</strong> {{ \Carbon\Carbon::parse($issuesDate->created_at)->format('jS F Y') }}
        </div>

        <div class="bill-section">
            <div class="bill-column">
                <h4><strong>Bill to</strong></h4>
                <h4>{{ $customerDetails->customerName }}</h4>
                <h4>{{ $customerDetails->address }}</h4>
                <h4>Tel: {{ $customerDetails->phoneNumber }}</h4>
                <h4>TIN: {{ $customerDetails->TIN }}</h4>
                <h4>VRN: {{ $customerDetails->VRN ?? '—' }}</h4>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item and Description</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($profomaInvoiceItems as $item)
                    @php
                        $lineTotal = $item->invoiceAmount * $item->quantity;
                        $total += $lineTotal;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->itemName }}</td>
                        <td>{{ number_format($item->quantity, 2) }}</td>
                        <td>{{ number_format($item->unitPrice, 2) }}</td>
                        <td>{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $vat = $hasVrn ? $total * 0.18 : 0;
            $grandTotal = $total + $vat;
        @endphp

        <div class="total-box">
            <table>
                <tr>
                    <td class="text-right">Sub Total:</td>
                    <td class="text-right">{{ number_format($total, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right">VAT(18%):</td>
                    <td class="text-right">{{ number_format($vat, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Total:</strong></td>
                    <td class="text-right"><strong>TZS {{ number_format($grandTotal, 2) }}</strong></td>
                </tr>
                <tr>
                    <td class="text-right" colspan="2" style="color: blue;"><strong>Balance Due: TZS
                            {{ number_format($grandTotal, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="bank-info">
            <p><strong>Account Name:</strong style="text-transform: uppercase;"> {{ $bankInformation->account_name }}
            </p>
            <p><strong>Bank Name:</strong> {{ $bankInformation->bank_name }}</p>
            <p><strong>Account Number:</strong> {{ $bankInformation->account_number }}</p>
            <p><strong>Bank Code:</strong> {{ $bankInformation->bank_code ?? ' ' }}</p>
            <p><strong>Swift Code:</strong> {{ $bankInformation->bank_code ?? ' ' }}</p>
        </div>

        <div class="qr-section">
            {{-- <p><strong>QR Code</strong></p> --}}
            @if (isset($qrImageBase64))
                <img src="{{ $qrImageBase64 }}" width="150" height="150" alt="QR Code">
            @endif
        </div>
    </div>

</body>

</html>
