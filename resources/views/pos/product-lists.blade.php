@extends('layouts.part')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@include('partials.nav-bar')
@section('content')
    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <x-messages />
                        <div class="card-header">
                            <h4 class="card-title text-success">Products List <sup
                                    class="text-warning fs-6">({{ number_format($counter) }}) products</sup></h4>
                        </div>
                        <form action="{{ route('download.qrcode') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="basic-datatables" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>SN</th>
                                                <th>Picture</th>
                                                <th>Serial No</th>
                                                <th>Item Name</th>
                                                <th>SKU</th>
                                                <th>Store</th>
                                                <th class="text-nowrap">QR Code</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        @php
                                            $n = 1;
                                        @endphp
                                        <tbody>
                                            @foreach ($products as $list)
                                                @php
                                                    $productId = \Illuminate\Support\Facades\Crypt::encrypt(
                                                        json_encode($list->productId),
                                                    );
                                                @endphp
                                                <tr>
                                                    <td>
                                                        @if ($list->picture)
                                                            <input type="checkbox" class="checkbox-item" name="check[]"
                                                                value="{{ $productId }}" id="checkbox">
                                                        @else
                                                            <input type="checkbox" class="checkbox-item" disabled
                                                                name="check[]" value="{{ $productId }}" id="checkbox">
                                                        @endif
                                                    </td>
                                                    <td>{{ $n++ }}</td>
                                                    <td>
                                                        @if ($list->picture)
                                                            <a href="{{ asset('storage/' . $list->picture) }}"
                                                                target="__blank"><img
                                                                    src="{{ asset('storage/' . $list->picture) }}"
                                                                    width="50" height="40" alt=""></a>
                                                        @else
                                                            <button type="button" class="btn btn-danger btn-sm text-nowrap"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalx-{{ $productId }}"><i
                                                                    class="fa fa-upload"></i> Upload</button>

                                                            <div class="modal fade" id="exampleModalx-{{ $productId }}"
                                                                tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="exampleModalLabel"><i
                                                                                    class="fa fa-upload"></i> Upload Image |
                                                                                <strong
                                                                                    class="text-success">{{ $list->name }}</strong>
                                                                            </h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="productId"
                                                                                value="{{ $productId }}" id="">
                                                                            <div>
                                                                                <label for="formFileLg"
                                                                                    class="form-label">Image</label>
                                                                                <input class="form-control form-control-lg"
                                                                                    id="formFileLg" type="file"
                                                                                    name="image" accept="image/*">
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" value="btn"
                                                                                class="btn btn-primary" name="btn">
                                                                                Submit
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $list->serialNo ?? '' }}</td>
                                                    <td>{{ $list->name }}</td>
                                                    <td>{{ $list->sku ?? '' }}</td>
                                                    <td>{{ $list->store ?? '' }}</td>
                                                    <td class="text-center">
                                                        @if ($list->picture)
                                                            <button class="btn btn-primary btn-sm" type="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModal-{{ $list->productId }}"><i
                                                                    class="fa fa-eye"></i></button>
                                                            <div class="modal fade"
                                                                id="exampleModal-{{ $list->productId }}" tabindex="-1"
                                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="exampleModalLabel">
                                                                                {{ $list->name }}</h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <center>
                                                                                <div id="qrcode-{{ $list->productId }}">
                                                                                </div>
                                                                                @php
                                                                                    $productId = \Illuminate\Support\Facades\Crypt::encrypt(
                                                                                        $list->productId,
                                                                                    );
                                                                                    $url = route(
                                                                                        'check-pos-order',
                                                                                        $productId,
                                                                                    );
                                                                                @endphp
                                                                                <script>
                                                                                    document.addEventListener("DOMContentLoaded", function() {
                                                                                        new QRCode(document.getElementById("qrcode-{{ $list->productId }}"), {
                                                                                            text: "{{ $url }}",
                                                                                            // width: 250,
                                                                                            // height: 250,
                                                                                            colorDark: "#000000",
                                                                                            colorLight: "#ffffff",
                                                                                            correctLevel: QRCode.CorrectLevel.H
                                                                                        });
                                                                                    });
                                                                                </script>
                                                                            </center>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <button class="btn btn-danger btn-sm" type="button"
                                                                data-bs-toggle="modal" disabled
                                                                data-bs-target="#exampleModal-{{ $list->productId }}"><i
                                                                    class="fa fa-eye"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row p-3" style="display: none;" id="barCode">
                                <div class="col-md-12">
                                    <button type="submit" id="download-btn" class="btn btn-primary border"><i
                                            class="fa fa-download"></i>
                                        Download BarCodes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new DataTable('#basic-datatables');
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll(".checkbox-item");
            const barCodeDiv = document.getElementById("barCode");

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                    barCodeDiv.style.display = anyChecked ? "block" : "none";
                });
            });
        });
    </script>
@stop
