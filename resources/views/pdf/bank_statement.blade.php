<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Bank Statement</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
        }

        .header-table td {
            border: none;
        }

        .text-end {
            text-align: right;
        }

        .text-primary {
            color: #007bff;
        }

        .text-success {
            color: #28a745;
        }

        .text-warning {
            color: #fd7e14;
        }

        .text-secondary {
            color: #6c757d;
        }

        .bg-light {
            background-color: #f2f2f2;
        }

        .bold {
            font-weight: bold;
        }

        a {
            color: #6f42c1;
            text-decoration: none;
        }

        .no-border td {
            border: none !important;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td><strong>Account Name</strong></td>
            <td class="text-end bold">{{ $accoutData->bank_name }} - {{ $accoutData->account_name }}</td>
        </tr>
        <tr>
            <td><strong>Statement Date</strong></td>
            <td class="text-end">{{ \Carbon\Carbon::today()->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td><strong>Statement Period</strong></td>
            <td class="text-end">
                {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }} -
                {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr class="bg-light">
                <th>S/N</th>
                <th>Date</th>
                <th>Details</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance (TZS)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $n = 1;
                $balance = $balanceBroughtForward;
            @endphp

            <tr class="bg-light">
                <td>#</td>
                <td class="text-primary">{{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}</td>
                <td>Balance Brought Forward <strong class="text-secondary">(B/Forward)</strong></td>
                <td></td>
                <td></td>
                <td class="text-primary"><strong>{{ number_format($balance, 2) }}</strong></td>
            </tr>

            @foreach ($statements as $statement)
                @php
                    if ($statement->accType == 'Cr') {
                        $balance -= $statement->amount;
                    } elseif ($statement->accType == 'Dr') {
                        $balance += $statement->amount;
                    }
                @endphp
                <tr>
                    <td>{{ $n++ }}</td>
                    <td class="text-primary">{{ \Carbon\Carbon::parse($statement->date)->format('M d, Y') }}</td>
                    <td>
                        {{ $statement->expName }} -
                        <a href="#">{{ $statement->decription }}</a>
                    </td>
                    <td class="text-success">
                        @if ($statement->accType == 'Dr')
                            {{ number_format($statement->amount, 2) }}
                        @endif
                    </td>
                    <td class="text-warning">
                        @if ($statement->accType == 'Cr')
                            {{ number_format($statement->amount, 2) }}
                        @endif
                    </td>
                    <td class="text-primary">
                        <strong>{{ number_format($balance, 2) }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="text-nowrap" colspan="3"><strong>Total (TZS)</strong></td>
                <td class="text-success"><strong>{{ number_format($totalDr, 2) }}</strong></td>
                <td class="text-warning"><strong>{{ number_format($totalCr, 2) }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
