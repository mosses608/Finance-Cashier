@if ($transactions->isEmpty())
    <p>No transactions found.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Bank Name</th>
                <th>Account Number</th>
                <th>Account Name</th>
                <th>Amount</th>
                <th>Type</th>
                {{-- <th>Module</th> --}}
                <th>Date</th>
            </tr>
        </thead>
        @php
            $n = 1;
        @endphp
        <tbody>
            @foreach ($transactions as $tx)
                <tr>
                    <td>{{ $n++ }}</td>
                    <td class="text-success">{{ $tx->bankName }}</td>
                    <td>{{ $tx->accountNumber }}</td>
                    <td>{{ $tx->accountName }}</td>
                    <td class="text-secondary">{{ number_format($tx->amount, 2) }}</td>
                    <td><strong>{{ ucfirst($tx->accType) }}</strong></td>
                    {{-- <td>{{ $tx->related_module }}</td> --}}
                    <td>{{ \Carbon\Carbon::parse($tx->transactionDate)->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
