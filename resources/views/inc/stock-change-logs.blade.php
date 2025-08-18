@extends('layouts.part')

@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <x-messages />
                        <div class="card-header">
                            <h4 class="card-title">Stock Change History</h4>
                        </div>
                        <form action="{{ route('approve.reject.stock.change') }}" method="POST" class="card-body">
                            @csrf
                            <div class="table-responsive">
                                <table id="basic-datatablesxzy" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Item Name</th>
                                            <th>Change Price</th>
                                            <th>Change Store</th>
                                            <th>Date Created</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($stockChangeLogs as $log)
                                            @php
                                                $logData = [
                                                    'logId' => $log->logId,
                                                ];

                                                $logIds = \Illuminate\Support\Facades\Crypt::encrypt(
                                                    json_encode($logData),
                                                );
                                            @endphp
                                            <tr>
                                                <td>
                                                    @if (in_array($log->logId, $approvedOrRejectedStockChangeIds))
                                                        <input type="checkbox" disabled name="" id="">
                                                    @else
                                                        <input type="checkbox" name="log_id[]" value="{{ $logIds }}"
                                                            id="">
                                                    @endif
                                                </td>
                                                <td>{{ $log->productName }}</td>
                                                <td>{{ number_format($log->changePrice, 2) }}</td>
                                                <td>{{ $log->storeName }}</td>
                                                <td>{{ \Carbon\Carbon::parse($log->dateCreated)->format('M d, Y') }}</td>
                                                <td>
                                                    @if ($log->status == 'pending')
                                                        <span class="text-warning"><i class="fas fa-spinner fa-spin"></i>
                                                            pending...</span>
                                                    @endif

                                                    @if ($log->status == 'approved')
                                                        <span class="text-success"><i
                                                                class="fas fa-check-circle text-success"></i>
                                                            approved...</span>
                                                    @endif

                                                    @if ($log->status == 'rejected')
                                                        <span class="text-warning"><i
                                                                class="fas fa-times-circle text-danger"></i>
                                                            rejected...</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-3 px-3">
                                <div class="col-12">
                                    <h5 class="fs-6">
                                        To approve or reject these request, check on the check boxes the <strong
                                            class="text-success">approve</strong> or <strong
                                            class="text-danger">reject</strong> the request
                                    </h5>
                                </div>
                                <div class="col-6 mt-3 mb-3">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal-approve"
                                        class="btn btn-primary">
                                        <i class="fas fa-check-circle"></i>
                                        Approve</button>

                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal-reject"
                                        class="btn btn-danger">
                                        <i class="fas fa-times-circle"></i>
                                        Reject</button>
                                </div>
                                {{-- approve modal --}}
                                <div class="modal fade" id="exampleModal-approve" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Approve with comments
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
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Reject with comments
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new DataTable("#basic-datatablesxzy");
    </script>
@stop
