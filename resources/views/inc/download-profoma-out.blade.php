<!DOCTYPE html>
<html>

<head>
    <title>Profoma Invoice PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .invoice-header {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }

        .invoice-row {
            text-align: left;
            /* Or center if you want */
            font-size: 0;
            /* Prevent white space between inline-blocks */
        }

        .invoice-col {
            display: inline-block;
            width: 30%;
            vertical-align: top;
            font-size: 14px;
            /* Reset font size */
            padding: 0 6px;
            box-sizing: border-box;
        }

        .invoice-col h4 {
            margin: 4px 0;
            color: #333;
        }

        @media (max-width: 768px) {
            .invoice-row {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <h2><strong>Profoma Invoice</strong></h2>
    <div class="card invoice-header">
        <div class="invoice-row">
            <div class="invoice-col">
                <h4>Invoice #: <span
                        style="color: #007BFF;">{{ str_pad($profomaInvoiceId, 4, '0', STR_PAD_LEFT) }}</span></h4>
                <h4>Issued: {{ \Carbon\Carbon::parse($issuesDate->created_at)->format('M d, Y') }}</h4>
            </div>

            <div class="invoice-col">
                <h4><strong>Bill From:</strong></h4>
                <h4>Akili Soft Co Ltd</h4>
                <h4>P.O.Box 75032</h4>
                <h4>Dar es Salaam, TZ</h4>
                <h4>support@akilisoft.com</h4>
            </div>

            <div class="invoice-col">
                <h4><strong>Bill To:</strong></h4>
                <h4>Open University of Tanzania</h4>
                <h4>P.O.Box 131</h4>
                <h4>Dar es Salaam, TZ</h4>
                <h4>out.ac.tz@gmail.com</h4>
            </div>
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
                    $totalDiscount += $item->quantity * $item->discount;
                    $totalAmountWithoutDiscount += $item->unitPrice * $item->quantity;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->itemName }}</td>
                    <td>{{ number_format($item->unitPrice, 2) }}</td>
                    <td>{{ number_format($item->quantity) }}</td>
                    <td>{{ $item->discount }}</td>
                    <td>{{ number_format($item->quantity * $item->discount, 2) }}</td>
                    <td>{{ number_format($item->unitPrice * $item->quantity) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"><strong>Sub Total</strong></td>
                <td><strong></strong></td>
                <td><strong>{{ number_format($totalAmountWithoutDiscount, 2) }}</strong></td>
            </tr>
            @php
                $finalAmount =
                    $totalAmountWithoutDiscount -
                        $totalDiscount -
                        (($totalAmountWithoutDiscount - $totalDiscount) * 0) / 100 ??
                    0;
            @endphp
            <tr>
                <td colspan="5"><strong>Totals (TSH)</strong></td>
                <td>
                    <strong class="p-3">
                        Discount: {{ number_format($totalDiscount, 2) }}
                    </strong>
                    <hr class="mt-2 mb-2">
                    <strong class="p-3">
                        VAT (0%): {{ number_format(0, 2) }}
                    </strong>
                </td>
                <td><strong>{{ number_format($finalAmount, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <br><br>
    <div class="text-center">
        <p><strong>QR Code</strong></p>
        @if (isset($qrImageBase64))
            <div style="text-align: center; margin-top: 20px;">
                <img src="{{ $qrImageBase64 }}" width="150" height="150" alt="QR Code">
            </div>
        @endif
    </div>

</body>

</html>
