<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row main-content bg-success text-center">
            <div class="col-md-4 text-center company__info d-lg-flex d-md-flex d-none">
                <img src="{{ asset('/img/logo-simbkk.png') }}" alt="" class=" img-fluid ">
            </div>
            <div class="col-md-8 col-xs-12 col-sm-12 login_form ">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 pt-3">
                            <p class="h2">Log In</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('login') }}" class="form-group">
                        @csrf
                        <div class="">
                            <input type="text" name="npsn" id="npsn"
                                class="form__input @error('npsn') is-invalid @enderror" placeholder="NPSN" autofocus
                                value="{{ old('npsn') }}" required autocomplete="npsn">
                            @error('npsn')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="">
                            <!-- <span class="fa fa-lock"></span> -->
                            <input id="password" type="password" class="form__input @error(" password") is-invalid
                                @enderror" name="password" required autocomplete="current-password"
                                placeholder="Password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="">
                            <input type="checkbox" name="remember_me" id="remember_me" class="">
                            <label for="remember_me">Remember Me!</label>
                        </div>
                        <div class="">
                            <input type="submit" value="Submit" class="btn">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <div class="container-fluid text-center footer">
        Coded with &hearts; by <a href="https://www.facebook.com/arya.yol">Arya Irama Wahono.</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>
    @include('preloader.loadContent')
    <script src="{{ asset('js/load.js') }}"></script>
</body>
