@extends('layouts.global')
@section('title')
Rekap Data
@endsection
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="{{ asset('js/rekap.js') }}"></script>
@endsection
@section('content')
<div class="page-header">
    <h4 class="page-title">
        Rekap Data
    </h4>
</div>
<div class="row">
    <div class="col-md-12">
        @can('roleAdmin')
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <h4 class="card-title">Rekap Semua Sekolah</h4>
                    <div class="card-tools">
                        @if (session('status_semua_sekolah'))
                        <span class="badge badge-danger">{{ session('status_semua_sekolah') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <select name="semua" id="semua" class=" form-control w-50" aria-placeholder="Rekap Semua Sekolah"
                        disabled></select>
                </div>
                <a href="{{ route('export.semua.sekolah') }}" class="btn btn-secondary ml-2">Download</a>
            </div>
        </div>
        @endcan
        @can('roleAdminOpeartor')
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <h4 class="card-title text-capitalize">
                        @can('roleOperatorSekolah')
                        Rekap {{ \Auth::user()->sekolahs->sekolah_nama }}
                        @elsecan('roleAdmin',)
                        Rekap Berdasarkan Sekolah
                        @endcan
                    </h4>
                    <div class="card-tools">
                        @if (session('status_persekolah'))
                        <span class="badge badge-danger">{{ session('status_persekolah') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('export.per.sekolah') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        @can('roleAdmin')
                        <select name="npsn" id="npsn" class="@error('npsn') is-invalid @enderror"
                            style="width: 50% !important;"></select>
                        @error('npsn')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                        @endcan
                        @can('roleOperatorSekolah')
                        <select name="npsn" id="npsn" class=" form-control w-50" disabled></select>
                        @endcan
                    </div>
                    <button type="submit" class="btn btn-secondary ml-2">Download</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <h4 class="card-title">Rekap Berdasarkan Angkatan</h4>
                    <div class="card-tools">
                        @if (session('status_angkatan'))
                        <span class="badge badge-danger">{{ session('status_angkatan') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('export.per.angkatan') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <select name="angkatan" id="angkatan" class="@error('angkatan') is-invalid @enderror"
                            style="width: 50% !important;"></select>
                        @error('angkatan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-secondary ml-2">Download</button>
                </form>
            </div>
        </div>
        @endcan
        @can('roleAdmin')
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <h4 class="card-title">Rekap Berdasarkan Jurusan/Komli</h4>
                    <div class="card-tools">
                        @if (session('status_komli'))
                        <span class="badge badge-danger">{{ session('status_komli') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('export.per.komli') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <select name="komli" id="komli" class="@error('komli') is-invalid @enderror"
                            style="width: 50% !important;"></select>
                        @error('komli')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-secondary ml-2">Download</button>
                </form>
            </div>
        </div>
        @endcan
    </div>
</div>
@endsection
