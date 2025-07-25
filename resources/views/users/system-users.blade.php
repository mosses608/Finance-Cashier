@extends('layouts.part')
@include('partials.nav-bar')
@section('content')
    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h4 class="p-3 fs-5">System Users</h4>
                                </div>
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">System Users List</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">New System User</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr class="text-nowrap">
                                                    <th>S/N</th>
                                                    <th>Names</th>
                                                    <th>Username</th>
                                                    <th>Role</th>
                                                    <th>Department</th>
                                                    <th>Status</th>
                                                    {{-- <th>Action</th> --}}
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($systemUsersFromAdmin as $systemUser)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $systemUser->fullNames }}</td>
                                                        <td>{{ $systemUser->username }}</td>
                                                        <td>{{ $systemUser->roleName }}</td>
                                                        <td>{{ __('Administration') }}</td>
                                                        <td>
                                                            @if ($systemUser->status)
                                                                <button class="btn btn-primary btn-sm">Active</button>
                                                            @else
                                                                <button class="btn btn-warning btn-sm">In-active</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                @foreach ($systemUsersFromEmploy as $user)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $user->fName . ' ' . $user->lName }}</td>
                                                        <td>{{ $user->username }}</td>
                                                        <td>{{ $user->roleName }}</td>
                                                        <td>{{ $user->department }}</td>
                                                        <td>
                                                             @if ($user->status)
                                                                <button class="btn btn-primary btn-sm">Active</button>
                                                            @else
                                                                <button class="btn btn-warning btn-sm">In-active</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <form action="{{ route('new.susyem.user') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">User
                                                        Type</span>
                                                    <select class="form-control" aria-label="Sizing example input"
                                                        id="userTypeSelect" name="role_id"
                                                        aria-describedby="inputGroup-sizing-default" required>
                                                        <option value="" selected disabled>--select user type--
                                                        </option>
                                                        <option value="1">Administrators</option>
                                                        <option value="2">Employees</option>
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- if type is employees --}}
                                            <div class="col-4 mb-3" id="employees" style="display: none;">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">User
                                                    </span>
                                                    <select class="form-control" aria-label="Sizing example input"
                                                        id="userSelect" name="user_id"
                                                        aria-describedby="inputGroup-sizing-default">
                                                        <option value="" selected disabled>--select user--
                                                        </option>
                                                        @foreach ($employees as $user)
                                                            <option value="{{ $user->id }}"
                                                                data-phone="{{ $user->phone_number }}">
                                                                {{ $user->first_name . ' ' . $user->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- if type is administrators --}}
                                            <div class="col-4 mb-3" id="administrators-name" style="display: none;">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Names
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        aria-label="Sizing example input" name="names"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="full names">
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3" id="administrators-phone" style="display: none;">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Phone
                                                    </span>
                                                    <input type="tel" class="form-control" id="phone"
                                                        aria-label="Sizing example input" name="phone" maxlength="10"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="phone number">
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3" id="administrators-email" style="display: none;">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Email
                                                    </span>
                                                    <input type="email" class="form-control"
                                                        aria-label="Sizing example input" name="email"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="email address">
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Username
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        aria-label="Sizing example input" id="username" name="username"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="username" required>
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Password
                                                    </span>
                                                    <input type="password" class="form-control"
                                                        aria-label="Sizing example input" name="password"
                                                        aria-describedby="inputGroup-sizing-default" id="password"
                                                        placeholder="password" maxlength="12" autocomplete="off"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Confirm-Password
                                                    </span>
                                                    <input type="password" class="form-control"
                                                        aria-label="Sizing example input" name="password_confirm"
                                                        aria-describedby="inputGroup-sizing-default" id="password-confirm"
                                                        placeholder="re-enter password" maxlength="12" autocomplete="off"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="input-group mb-3">
                                                    <button type="submit" class="btn btn-primary">Submit User</button>
                                                </div>
                                            </div>
                                        </div>
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
        new DataTable('#basic-datatables');

        document.getElementById("userTypeSelect").addEventListener('change', function() {
            selectValue = this.value;

            const secondSelect = document.getElementById("employees");

            const adminSelectName = document.getElementById("administrators-name");
            const adminSelectPhone = document.getElementById("administrators-phone");
            const adminSelectEmail = document.getElementById("administrators-email");

            adminSelectName.style.display = 'none';
            adminSelectPhone.style.display = 'none';
            adminSelectEmail.style.display = 'none';
            secondSelect.style.display = 'none';

            if (selectValue === '1') {
                adminSelectName.style.display = 'block';
                adminSelectPhone.style.display = 'block';
                adminSelectEmail.style.display = 'block';
            }

            if (selectValue === '2') {
                secondSelect.style.display = 'block';
            }
        });

        document.getElementById("userSelect").addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const userPhone = selectedOption.getAttribute("data-phone");

            document.getElementById("username").textContent = userPhone;
            document.getElementById("username").value = userPhone;
        });

        document.getElementById("phone").addEventListener('input', function() {
            const phone = this.value;

            document.getElementById("username").textContent = phone;
            document.getElementById("username").value = phone;
        });

        document.getElementById("password-confirm").addEventListener('blur', function() {
            const confirmValue = this.value;
            const passwordValue = document.getElementById("password").value;

            const error = "Password do not match!"

            if (confirmValue !== passwordValue) {
                alert(error);
            }
        });
    </script>
@stop
