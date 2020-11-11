@extends('layouts.global')
@section('title')
Data Angkatan
@endsection
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.22/b-1.6.5/r-2.2.6/sc-2.0.3/datatables.min.css" />
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>
<script src="{{ asset('js/angkatan.js') }}"></script>
@endsection
@section('menudb1')
active
@endsection
@section('menudb2')
show
@endsection
@section('content')
<div class="page-header">
    <h4 class="page-title">Angkatan</h4>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <h4 class="card-title">Daftar Angkatan</h4>
                    <div class="card-tools">
                        <button type="submit" class="btn btn-primary btn-round btn-sm"
                            onclick="addForm()">Tambah</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="data_table_angkatan" class="display table table-hover nowrap  w-100 " cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Angkatan</th>
                                <th>ID</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@can('roleAdminOpeartor')
@include('modal.angkatan')
@endcan
@endsection
