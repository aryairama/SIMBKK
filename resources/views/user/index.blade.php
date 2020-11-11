@extends('layouts.global')
@section('title')
Semua User
@endsection
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.22/b-1.6.5/cr-1.5.2/fc-3.3.1/fh-3.1.7/r-2.2.6/sc-2.0.3/sb-1.0.0/sp-1.2.1/datatables.min.css" />
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="{{ asset('js/user.js') }}"></script>
@endsection
@section('menudb1')
active
@endsection
@section('menudb2')
show
@endsection
@section('content')
<div class="page-header">
    <h4 class="page-title">Pengguna</h4>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <h4 class="card-title">Daftar Pengguna</h4>
                    <div class="card-tools">
                        <button type="submit" class="btn btn-primary btn-round btn-sm"
                            onclick="addForm()">Tambah</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab-nobd" data-toggle="pill" href="#operator"
                            role="tab" aria-controls="operator" aria-selected="true">Operator Sekolah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab-nobd" data-toggle="pill" href="#admin" role="tab"
                            aria-controls="admin" aria-selected="false">Admin</a>
                    </li>
                </ul>
                <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                    <div class="tab-pane fade show active" id="operator" role="tabpanel"
                        aria-labelledby="pills-home-tab-nobd">
                        <div class="table-responsive">
                            <table id="data_table_user_operator" class="display table table-hover nowrap  w-100 "
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NPSN</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Sekolah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">NPSN</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Sekolah</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="pills-profile-tab-nobd">
                        <div class="table-responsive">
                            <table id="data_table_user_admin" class="display table table-hover nowrap  w-100 "
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
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
    </div>
</div>
@can('roleAdmin')
@include('modal.user')
@endcan
@endsection
