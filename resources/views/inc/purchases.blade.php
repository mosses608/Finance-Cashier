@extends('layouts.mainLayout')

@section('content')

<div class="transparent" onclick="hide(event)"></div>

@include('partials.sideNav')

<x-messages />

<div class="shortcut-report">
    <div class="md-7">
    <h3>{{ __('Journal List') }}</h3>
    <div class="md-7">
        <button type="button" class="btn btn-secondary" onclick="filterJournal(event)"><i class="fa fa-filter"></i> Filter</button>
        <button type="button" class="btn btn-secondary" onclick="addJournal(event)"><a href="#"><i class="fa fa-plus"></i> Create Journal</a></button>
<br>
    <form action="#" method="GET" class="form-data">
        @csrf
        <div class="meta-data">
            <span><i class="fa fa-search"></i></span>
            <input
                type="text" 
                name="search" 
                id=""
                placeholder="Search Journal"
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
</div>
@stop