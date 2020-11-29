@extends('layouts.global')
@section('title')
Profile Sekolah {{$profileSekolah->sekolah_nama}}
@endsection
@section('content')
<div class="page-header">
    <h4 class="page-title">Profile</h4>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Profile {{ $profileSekolah->sekolah_nama }}</div>
            </div>
            <div class="card-body">
                @if(session("status"))
                <div class="badge badge-{{ session("type") }}">
                    {{ session("status") }}
                </div>
                @endif
                <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab-nobd" data-toggle="pill" href="#profile"
                            role="tab" aria-controls="profile" aria-selected="true">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab-nobd" data-toggle="pill" href="#password" role="tab"
                            aria-controls="password" aria-selected="false">Ubah Password</a>
                    </li>
                </ul>
                <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel"
                        aria-labelledby="pills-home-tab-nobd">
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <td>NPSN</td>
                                        <td>: {{ $profileSekolah->npsn }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nama Sekolah</td>
                                        <td>: {{ $profileSekolah->sekolah_nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kepala Sekolah</td>
                                        <td>: {{ $profileSekolah->sekolah_kepsek }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email Sekolah</td>
                                        <td>: {{ $profileSekolah->sekolah_email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat Sekolah</td>
                                        <td>: Kecamatan {{ $profileSekolah->kec }}, Kabupaten{{ $profileSekolah->kab }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kode Pos</td>
                                        <td>: {{ $profileSekolah->kode_pos }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="pills-profile-tab-nobd">
                        <form action="{{ route('profile.update',[$profileSekolah->npsn]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="old_password">Password Lama</label>
                                <input class="form-control w-50 @error("old_password") is-invalid @enderror"
                                    type="password" name="old_password" id="old_password">
                                @error('old_password')
                                <div class="invalid-feedback">
                                    {{ $message  }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="new_password">Password Lama</label>
                                <input class="form-control w-50 @error("new_password") is-invalid @enderror"
                                    type="password" name="new_password" id="new_password">
                                @error('new_password')
                                <div class="invalid-feedback">
                                    {{ $message  }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Password Lama</label>
                                <input class="form-control w-50 @error("confirm_password") is-invalid @enderror"
                                    type="password" name="confirm_password" id="confirm_password">
                                @error('confirm_password')
                                <div class="invalid-feedback">
                                    {{ $message  }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-secondary btn-rounded" value="Ubah">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
