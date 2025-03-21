@extends('layouts.mainLayout')

@section('content')
<div class="transparent" onclick="hideAll(event)"></div>

@include('partials.sideNav')

<x-messages />

<div class="shortcut-report">
    <div class="md-7">
        <h3>{{ __('Users List') }}</h3>
        <button type="button" class="btn btn-secondary" onclick="popFormReg(event)"><i class="fa fa-plus"></i> Add New User</button>
<br>
        <form action="{{ route('users') }}" method="GET" class="form-data">
            @csrf
            <div class="meta-data">
                <span><i class="fa fa-search"></i></span>
                <input
                 type="text" 
                 name="search" 
                 id=""
                 placeholder="Search From List"
                >
            </div>
            <div class="export-print">
                <button type="button"> <a href="file.xlsx" class="btn btn-outline-success" download>
                    <i class="fa fa-file-excel"></i>
                </a>
                </button>
                <button type="button"> <a href="file.pdf" class="btn btn-outline-success">
                    <i class="fa fa-file-pdf" style="color: orange;"></i>
                </a>
                </button>
                <button type="button"> <a href="#"  class="btn btn-outline-success">
                    <i class="fa fa-print" style="color: #007BFF;"></i>
                </a>
                </button>
            </div>
            <br><br>
        </form>

        @include('partials.user-reg')

        <table class="table table-bordered table-striped">
            <thead class="table-white">
                <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                @php
                $role = $roles->firstWhere('id', $user->role_id);
                $dep = $departments->firstWhere('id', $user->department_id);
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
                        {{ $role->slug }}
                    </td>
                    <td>
                        {{ $dep->name }}
                    </td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td class="action-btn">
                        <button class="view-usr" style="color: #007BFF;"><a href="#"><i class="fa fa-eye"></i></a></button>
                        <button class="edit-usr" style="color: #008800;" onclick="editUserForm(event, {{ $user->id }})"><i class="fa fa-pencil"></i></button>
                        <button class="delete-usr" style="color: red;" onclick="deleteUserForm(event, {{ $user->id }} )"><i class="fa fa-trash"></i></button>

                        @include('partials.user-update')
                        @include('partials.user-delete')
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @if(count($users) == 0)
    <span>No data found!</span>
    @endif
    </div>
</div>
@stop