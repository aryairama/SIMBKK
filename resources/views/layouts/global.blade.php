<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>SIMBKK - @yield('title')</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('/assets/img/icon.ico') }}" type="image/x-icon" />
    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
			google: {"families":["Open+Sans:300,400,600,700"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"], urls: ['../assets/css/fonts.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
    </script>
    @yield('css')
    <!-- Font -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css"
        integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/azzara.min.css') }}">
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
    <!-- add css -->
</head>

<body>
    <div class="wrapper">
        <!--
			Tip 1: You can change the background color of the main header using: data-background-color="blue | purple | light-blue | green | orange | red"
		-->
        <div class="main-header" data-background-color="purple">
            <!-- Logo Header -->
            <div class="logo-header">
                <a href="index.html" class="logo text-white font-weight-bold">
                    {{-- <img src="{{ asset('assets/img/logoazzara.svg') }}" alt="navbar brand" class="navbar-brand">
                    --}}
                    SIMBKK
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                    data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="fa fa-bars"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="fa fa-ellipsis-v"></i></button>
                <div class="navbar-minimize">
                    <button class="btn btn-minimize btn-rounded">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->
            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg">
                <div class="container-fluid">
                    <div class="collapse" id="search-nav">
                        <form class="navbar-left navbar-form nav-search mr-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pr-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input type="text" placeholder="Search ..." class="form-control">
                            </div>
                        </form>
                    </div>
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item toggle-nav-search hidden-caret">
                            <a class="nav-link" data-toggle="collapse" href="#search-nav" role="button"
                                aria-expanded="false" aria-controls="search-nav">
                                <i class="fa fa-search"></i>
                            </a>
                        </li>
                        {{--  --}}
                        @can('roleOperatorSekolah')
                        <li class="nav-item dropdown hidden-caret">
                            <a class="nav-link dropdown-toggle" href="{{ route('import.index') }}"  role="button">
                                <i class="fas fa-file-upload"></i>
                                <span class="notification">1</span>
                            </a>
                        </li>
                        @endcan
                        {{--  --}}
                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"
                                aria-expanded="false">
                                <div class="avatar-sm">
                                    <img src="{{ asset('assets/img/profile.jpg') }}" alt="..."
                                        class="avatar-img rounded-circle">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <li>
                                    <div class="user-box">
                                        <div class="avatar-lg"><img src="{{ asset('assets/img/profile.jpg') }}"
                                                alt="image profile" class="avatar-img rounded"></div>
                                        <div class="u-text">
                                            <h4>
                                                @if (Auth::user()->roles == "admin")
                                                {{ Auth::user()->username }}
                                                @elseif(Auth::user()->roles == "operator_sekolah")
                                                {{ Auth::user()->sekolahs->sekolah_nama }}
                                                @endif
                                            </h4>
                                            <p class="text-muted">
                                                @if(Auth::user()->roles == "operator_sekolah")
                                                {{ Auth::user()->sekolahs->sekolah_email }}
                                                @endif</p>
                                                @can('roleOperatorSekolah')
                                                <a href="{{ route('profile.index') }}"
                                                class="btn btn-rounded btn-danger btn-sm mt-1">View
                                                Profile</a>
                                                @endcan
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                    @can('roleOperatorSekolah')
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">Edit Profile</a>
                                    @endcan
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        <button type="submit" class="dropdown-item">
                                            @csrf
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-background"></div>
            <div class="sidebar-wrapper scrollbar-inner">
                <div class="sidebar-content">
                    <div class="user">
                        <div class="avatar-sm float-left mr-2">
                            <img src="{{ asset('assets/img/profile.jpg') }}" alt="..."
                                class="avatar-img rounded-circle">
                        </div>
                        <div class="info">
                            <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                                <span class="text-truncate">
                                    <span class="text-truncate d-block">
                                        @if (Auth::user()->roles == "admin")
                                        {{ Auth::user()->username }}
                                        @elseif(Auth::user()->roles == "operator_sekolah")
                                        {{ Auth::user()->sekolahs->npsn }}-{{ Auth::user()->sekolahs->sekolah_nama }}
                                        @endif
                                    </span>
                                    <span class="user-level">{{ Auth::user()->roles }}</span>
                                    <span class="caret"></span>
                                </span>
                            </a>
                            <div class="clearfix"></div>
                            @can('roleOperatorSekolah')
                            <div class="collapse in" id="collapseExample">
                                <ul class="nav">
                                    <li>
                                        <a href="{{ route('profile.index') }}">
                                            <span class="link-collapse">Edit Profile</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @endcan
                        </div>
                    </div>
                    <ul class="nav">
                        @can('roleAdminOpeartor')
                        <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        @endcan
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Menu</h4>
                        </li>
                        <li class="nav-item @yield('menudb1')">
                            <a data-toggle="collapse" href="#base">
                                <i class="fas fa-layer-group"></i>
                                <p>Database</p>
                                <span class="badge badge-count badge-primary">
                                    @can('roleAdmin')
                                    6
                                    @endcan
                                    @can('roleOperatorSekolah')
                                    4
                                    @endcan
                                </span>
                            </a>
                            <div class="collapse @yield('menudb2')" id="base">
                                <ul class="nav nav-collapse">
                                    @can('roleAdmin')
                                    <li>
                                        <a href="{{ route('user.index') }}">
                                            <span class="sub-item"><span
                                                    class="{{ Route::is('user.index') ? 'fas fa-angle-double-right' : '' }}">
                                                </span>User</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('sekolah.index') }}">
                                            <span class="sub-item"><span
                                                    class="{{ Route::is('sekolah.index') ? 'fas fa-angle-double-right' : '' }}">
                                                </span>Sekolah</span>
                                        </a>
                                    </li>
                                    @endcan
                                    @can('roleAdminOpeartor')
                                    <li>
                                        <a href="{{ route('siswa.index') }}">
                                            <span class="sub-item"><span
                                                    class="{{ Route::is('siswa.index') ? 'fas fa-angle-double-right' : '' }}">
                                                </span>Siswa</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('angkatan.index') }}">
                                            <span class="sub-item"><span
                                                    class="{{ Route::is('angkatan.index') ? 'fas fa-angle-double-right' : '' }}">
                                                </span>Angkatan</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('komli.index') }}">
                                            <span class="sub-item"><span
                                                    class="{{ Route::is('komli.index') ? 'fas fa-angle-double-right' : '' }}">
                                                </span>Komli</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('keterserapan.index') }}">
                                            <span class="sub-item"><span
                                                    class="{{ Route::is('keterserapan.index') ? 'fas fa-angle-double-right' : '' }}">
                                                </span>Keterserapan</span>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item {{ Route::is('rekap.index') ? 'active' : '' }}">
                            <a href="{{ route('rekap.index') }}">
                                <i class="fas fa-file-excel"></i>
                                <p>Rekap</p>
                            </a>
                        </li>
                        @can('roleOperatorSekolah')
                        <li class="nav-item {{ Route::is('import.index') ? 'active' : '' }}">
                            <a href="{{ route('import.index') }}">
                                <i class="fas fa-file-upload"></i>
                                <p>Import Data Siswa</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->
        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    {{-- <div class="page-header">
						<h4 class="page-title">Dashboard</h4>
						<div class="btn-group btn-group-page-header ml-auto">
							<button type="button" class="btn btn-light btn-round btn-page-header-dropdown dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-ellipsis-h"></i>
							</button>
							<div class="dropdown-menu">
								<div class="arrow"></div>
								<a class="dropdown-item" href="#">Action</a>
								<a class="dropdown-item" href="#">Another action</a>
								<a class="dropdown-item" href="#">Something else here</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Separated link</a>
							</div>
						</div>
                    </div> --}}
                    @yield('content')
                    {{-- end --}}
                </div>
            </div>
        </div>

        <!-- Custom template | don't include it in your project! -->
        <div class="custom-template">
            <div class="title">Settings</div>
            <div class="custom-content">
                <div class="switcher">
                    <div class="switch-block">
                        <h4>Topbar</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeMainHeaderColor" data-color="blue"></button>
                            <button type="button" class="selected changeMainHeaderColor" data-color="purple"></button>
                            <button type="button" class="changeMainHeaderColor" data-color="light-blue"></button>
                            <button type="button" class="changeMainHeaderColor" data-color="green"></button>
                            <button type="button" class="changeMainHeaderColor" data-color="orange"></button>
                            <button type="button" class="changeMainHeaderColor" data-color="red"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Background</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeBackgroundColor" data-color="bg2"></button>
                            <button type="button" class="changeBackgroundColor selected" data-color="bg1"></button>
                            <button type="button" class="changeBackgroundColor" data-color="bg3"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-toggle">
                <i class="flaticon-settings"></i>
            </div>
        </div>
        <!-- End Custom template -->
    </div>
    @include('preloader.loadContent')
    <!--   Core JS Files   -->
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.22/b-1.6.5/cr-1.5.2/fc-3.3.1/fh-3.1.7/r-2.2.6/sc-2.0.3/sb-1.0.0/sp-1.2.1/datatables.min.js">
    </script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- jQuery UI -->
    <script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.8.1/sweetalert2.all.js"
        integrity="sha512-y+SzBgK5bG6k2mrAuqylPSreQJECjbQG/svvwlLmPdxTcQIoRgTMwiqZe0IXiiue8HRsMkofE+irjH0IrR1iPw=="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.8.1/sweetalert2.js"
        integrity="sha512-C3N4rWlefvoc27G9AdklS/p48qyXXFR0x728TGB1ASAMYy3i2+ETvh8Tflc5F0OvvJvJWmdSmzJ/DPLGNU0HJQ=="
        crossorigin="anonymous"></script>
    <!-- Azzara JS -->
    <script src="assets/js/ready.min.js"></script>
    <!-- Azzara DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="{{ asset('js/load.js') }}"></script>
    @yield('js')
</body>

</html>
