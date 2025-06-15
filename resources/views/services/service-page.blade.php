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
                                    data-bs-target="#nav-service-list" type="button" role="tab"
                                    aria-controls="nav-home" aria-selected="true">Service List</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-create-service" type="button" role="tab"
                                    aria-controls="nav-profile" aria-selected="false">Create Service</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Service Profoma Invoice</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <!-- Invoice Tab -->
                                <div class="tab-pane fade show active" id="nav-service-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Service Name</th>
                                                    <th>Price</th>
                                                    <th>Category</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($services as $service)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $service->name }}</td>
                                                        <td>{{ number_format($service->price, 2) }}</td>
                                                        <td>{{ $service->category ?? 'Unavaillable' }}</td>
                                                        <td>
                                                            @if ($service->active == true)
                                                                <i class="fa fa-circle"></i> {{ __('Active') }}
                                                            @else
                                                            <i class="fa fa-circle text-secondary"></i> {{ __('Inactive') }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Profoma Invoice Tab -->
                                <div class="tab-pane fade" id="nav-create-service" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    <form action="{{ route('store.service') }}" method="POST">
                                        @include('partials.create-service')
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <form action="{{ route('service.profoma.invoice') }}" method="POST">
                                        @include('partials.service-profoma')
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
