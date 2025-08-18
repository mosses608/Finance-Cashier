@extends('layouts.part')
@include('partials.nav-bar')
@section('content')
    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Stock Out List</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Stock Out Products</button>
                                {{-- <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Profoma Out From Store</button> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                {{-- Stock Out List --}}
                                <form action="{{ route('approve.reject.transactions') }}" method="POST"
                                    class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    @csrf
                                    <h4 class="fs-6 text-success">Today's Stock Outs | <span
                                            class="text-primary">{{ \Carbon\Carbon::today()->format('M d, Y') }}</span></h4>
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Serial No</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity Out</th>
                                                    <th>Staff</th>
                                                    <th>Due Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($todayStockOuts as $stock)
                                                    @php
                                                        $status = $stock->status;
                                                        $data = [
                                                            'tranxt' => $stock->autoId,
                                                        ];
                                                        $validData = \Illuminate\Support\Facades\Crypt::encrypt(
                                                            json_encode($data),
                                                        );
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            @if (in_array($stock->autoId, $stockOutExistsIds))
                                                                <input type="checkbox" disabled name=""
                                                                    id="">
                                                            @else
                                                                <input type="checkbox" name="transaction_id[]"
                                                                    value="{{ $validData }}" id="">
                                                            @endif
                                                        </td>
                                                        <td>{{ $stock->serialNo }}</td>
                                                        <td>{{ $stock->productName }}</td>
                                                        <td>{{ number_format($stock->quantityOut, 0) }}</td>
                                                        <td>{{ $stock->userName }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($stock->dueDate)->format('M d, Y') }}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            @if ($status == null)
                                                                <span class="text-warning"><i
                                                                        class="fas fa-spinner fa-spin"></i>
                                                                    pending...</span>
                                                            @endif
                                                            @if ($status == 1)
                                                                <span class="text-success"><i
                                                                        class="fas fa-check-circle text-success"></i>
                                                                    approved...</span>
                                                            @endif
                                                            @if ($status == 2)
                                                                <span class="text-danger"><i
                                                                        class="fas fa-times-circle text-danger"></i>
                                                                    rejected...</span>
                                                            @endif
                                                        </td>
                                                        @php
                                                            $encryptedId = Crypt::encrypt($stock->autoId);
                                                        @endphp
                                                        <td class="text-center"><a
                                                                href="{{ route('stock.out.receipt', $encryptedId) }}"
                                                                class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if (count($todayStockOuts) > 0)
                                        <div class="row mt-3 px-3">
                                            <div class="col-12">
                                                <h5 class="fs-6">
                                                    To approve or reject these request, check on the check boxes the <strong
                                                        class="text-success">approve</strong> or <strong
                                                        class="text-danger">reject</strong> the request
                                                </h5>
                                            </div>
                                            <div class="col-6 mt-3 mb-3">
                                                <button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal-approve" class="btn btn-primary">
                                                    <i class="fas fa-check-circle"></i>
                                                    Approve</button>

                                                <button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal-reject" class="btn btn-danger">
                                                    <i class="fas fa-times-circle"></i>
                                                    Reject</button>
                                            </div>
                                            {{-- approve modal --}}
                                            <div class="modal fade" id="exampleModal-approve" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Approve with
                                                                comments
                                                            </h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-floating">
                                                                <textarea class="form-control" name="approve_comment" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                                                <label for="floatingTextarea">Comments</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="action" value="accept"
                                                                class="btn btn-primary">Approve</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- reject modal --}}
                                            <div class="modal fade" id="exampleModal-reject" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Reject
                                                                with
                                                                comments
                                                            </h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-floating">
                                                                <textarea class="form-control" name="reject_comment" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                                                <label for="floatingTextarea">Comments</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="action" value="reject"
                                                                class="btn btn-danger">Reject</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </form>
                                {{-- Stock out product --}}
                                <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    <form action="{{ route('stock.out.product') }}" method="POST">
                                        @include('partials.stock-out')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('change', '.product-select', function() {
                const selectedOption = $(this).find('option:selected');

                const availableQuantity = selectedOption.data('available-quantity');
                const sellingPrice = selectedOption.data('selling-price');

                const container = $(this).closest('.row');

                container.find('.available-quantity').val(availableQuantity);
                container.find('.selling-price').val(sellingPrice);
            });

            // Initialize select2
            $('.select2').select2({
                placeholder: '--select product--',
                width: '100%'
            });
        });
    </script>

    <style>
        .blink {
            animation: blink-animation 1.5s steps(2, start) infinite;
        }

        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selector = document.querySelector('#selected select');

            selector.addEventListener('change', function() {
                const value = this.value;

                // Hide all initially
                document.getElementById('selected1').style.display = 'none';
                document.getElementById('name').style.display = 'none';
                document.getElementById('phone').style.display = 'none';
                document.getElementById('TIN').style.display = 'none';
                document.getElementById('address').style.display = 'none';

                if (value === '1') {
                    // Show existing customer select
                    document.getElementById('selected1').style.display = 'block';
                } else if (value === '2') {
                    // Show new customer input fields
                    document.getElementById('name').style.display = 'block';
                    document.getElementById('phone').style.display = 'block';
                    document.getElementById('TIN').style.display = 'block';
                    document.getElementById('address').style.display = 'block';
                }
            });
        });
    </script>

@stop
