@extends('layouts.global')
@section('title')
Dashboard
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"
    integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw=="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js"
    integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA=="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
@can('roleAdmin')
<script src="{{ asset('js/home1.js') }}"></script>
@elsecan('roleOperatorSekolah')
<script src="{{ asset('js/home2.js') }}"></script>
@endcan
@endsection
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css"
    integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w=="
    crossorigin="anonymous" />
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.22/b-1.6.5/r-2.2.6/sc-2.0.3/datatables.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
<div class="page-header">
    <h4 class="page-title">Dashboard</h4>
</div>
<div class="row">
    @can('roleAdmin')
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body ">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-school"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Sekolah</p>
                            <h4 class="card-title">{{ $countSekolah }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
    @can('roleAdminOpeartor')
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-info bubble-shadow-small">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Siswa</p>
                            <h4 class="card-title">{{ $countSiswa }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-success bubble-shadow-small">
                            <i class="fas fa-female"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Perempuan</p>
                            <h4 class="card-title">{{ $countFemale }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                            <i class="fas fa-male"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Laki-laki </p>
                            <h4 class="card-title">{{ $countMale }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
<div class="row">
    @can('roleAdmin')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Siswa Persekolah</h4>
            </div>
            <div class="card-body">
                <div>
                    {!! $chartSiswa->render() !!}
                </div>
            </div>
        </div>
    </div>
    @endcan
    @can('roleOperatorSekolah')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Siswa Perangkatan</h4>
            </div>
            <div class="card-body">
                <div>
                    {!! $chartSiswaPerangkatan->render() !!}
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
<div class="row">
    @can('roleAdminOpeartor')
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-warning bubble-shadow-small">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Bekerja</p>
                            <h4 class="card-title">{{ $countBekerja }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Melanjutkan</p>
                            <h4 class="card-title">{{ $countMelanjutkan }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Wiraswasta </p>
                            <h4 class="card-title">{{ $countWiraswasta }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-danger bubble-shadow-small">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Belum Terserap </p>
                            <h4 class="card-title">{{ $countBelumTerserap }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
<div class="row">
    @can('roleAdmin')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Keterserapan Siswa</h4>
            </div>
            <div class="card-body">
                <div>
                    {!! $chartKeterserapan->render() !!}
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Data Siswa
                </h4>
            </div>
            <div class="card-body">
                @can('roleAdminOpeartor')
                <div class="row custom-filter">
                    <div class="col-md-12">
                        @can('roleAdmin')
                        <div class="p-1">
                            <select class="w-50" id="sekolah" name="sekolah">
                                <option value="">-Sekolah-</option>
                            </select>
                        </div>
                        @endcan
                        @can('roleAdminOpeartor')
                        <div class="p-1">
                            <select class="w-50" id="angkatan" name="angkatan">
                                <option value="">-Thn Angkatan-</option>
                            </select>
                        </div>
                        <div class="p-1">
                            <select class="w-50" id="komli" name="komli">
                                <option value="">-Komli-</option>
                            </select>
                        </div>
                        @endcan
                        <div class="form-group p-1">
                            <button class="btn btn-sm btn-primary filter-submit">FIlter</button>
                            <button class="btn btn-sm btn-danger filter-reset">Reset</button>
                        </div>
                    </div>
                </div>
                <hr class="py-1">
                @endcan
                <div class="table-responsive">
                    <table id="data_table_home" class="display table table-hover nowrap  w-100 " cellspacing="0">
                        <thead>
                            <tr>
                                @can ('roleAdmin')
                                <th>No</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Sekolah</th>
                                <th>Angkatan</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Kelamin</th>
                                <th>Kejuruan</th>
                                <th>Prestasi</th>
                                <th>Keterserapan</th>
                                <th>Keterangan</th>
                                @endcan
                                @can('roleOperatorSekolah')
                                <th>No</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Angkatan</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Kelamin</th>
                                <th>Kejuruan</th>
                                <th>Prestasi</th>
                                <th>Keterserapan</th>
                                <th>Keterangan</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
