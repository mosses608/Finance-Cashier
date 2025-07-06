<form id="create-sale-form" method="POST" action="{{ route('store.sales') }}">
    @csrf
    <div class="card border-primary mb-3">
        <div class="card-body">
            <div class="row align-items-start">
                <!-- Invoice ID -->
                <div class="col-md-4 mb-3">
                    <h5 class="card-title">
                        Invoice <strong style="color: #007BFF;">#{{ $invoiceData->id }}</strong>
                    </h5>
                </div>

                <!-- Customer Info -->
                <div class="col-md-4 mb-3">
                    <p class="mb-1"><strong>Customer Information:</strong></p>
                    <strong>Name: </strong><label>{{ $invoiceData->customer_name }}</label><br>
                    <strong>TIN: </strong><label>{{ $invoiceData->TIN }}</label><br>
                    <strong>VRN: </strong><label>{{ $invoiceData->vrn }}</label><br>
                    <strong>Phone: </strong><label>{{ $invoiceData->phone }}</label>
                </div>

                <!-- Invoice Info -->
                <div class="col-md-4 mb-3">
                    <p class="mb-1"><strong>Invoice Information:</strong></p>
                    <strong>Date Created: </strong>
                    {{ \Carbon\Carbon::parse($invoiceData->created_at)->format('M d, Y') }}<br>
                    <strong>Bill Amount: </strong> TSH {{ number_format($invoiceData->amount, 2) }} <br>
                    @if ($invoiceData->vrn != null)
                    @php
                        $vat = 0.18 * $invoiceData->amount;
                    @endphp
                    <strong>VAT (18%): </strong> {{ number_format($vat, 2) }}
                    @else
                    <strong>VAT (0%): </strong> {{ number_format(0, 2) }}
                    @endif
                    <br>
                    <strong>Invoice Status: </strong> <span class="btn btn-secondary btn-sm p-1 rounded-5 w-50"
                        style="color: orange;">{{ $invoiceData->statusName }}</span>
                </div>
            </div>
        </div>
    </div>

    @if ($invoiceData->status === 3)
    <div class="row mt-3 p-3 text-center">
        <span class="fw-900 text-primary fs-6">This invoice with ID: <strong class="text-secondary">{{ '#' . $invoiceData->id }}</strong> is already paid...</span>
    </div>
    @endif

    @if ($invoiceData->status != 3)
        <div class="row">
            <div class="col-6 mb-3">
                <label for="invoice_id" class="form-label d-flex"><strong>Invoice
                        ID</strong></label>
                <div class="input-group">
                    <input type="number" id="invoice_id" name="invoice_id" value="{{ $invoiceData->id }}"
                        class="form-control" readonly>
                </div>
            </div>

            <!-- Tax -->
            <div class="col-6 mb-3">
                <label for="tax" class="form-label"><strong>VAT
                        (18%)</strong></label>
                @if ($invoiceData->vrn != null)
                    @php
                        $vat = 0.18 * $invoiceData->amount;
                    @endphp
                    <input type="hidden" name="amount_paid" id="" value="{{ $invoiceData->amount + $vat }}">
                    <input type="number" name="tax" class="form-control" value="{{ $vat }}" readonly>
                @else
                    <input type="hidden" name="amount_paid" id="" value="{{ $invoiceData->amount }}">
                    <input type="number" name="tax" class="form-control"
                        value="{{ number_format(0 * $invoiceData->amount, 2) }}" placeholder="Enter tax rate, e.g. 18"
                        readonly>
                @endif
            </div>
        </div>

        <!-- Notes -->
        <div class="mb-3">
            <label for="notes" class="form-label"><strong>Notes</strong></label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
        </div>

        <!-- Submit -->
        <div class="row">
            <div class="col-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="checked" value="1" id="checkDefault"
                        required>
                    <label class="form-check-label text-primary fw-600" for="checkDefault">
                        I, confirm this invoice is successfully paid...
                    </label>
                </div>
            </div>
            <div class="col-6 text-end">
                <button type="submit" class="btn btn-primary">Save Sale</button>
            </div>
        </div>
    @endif
</form>
