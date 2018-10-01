<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pohon Dana</title>
    <!-- Fonts -->
    <link href="{{ asset('css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
     <link href="{{ asset('css/pohondana/custom-register.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('logo-text-ojk.png') }}" height="35">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#primaryNavbar" aria-controls="primaryNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="primaryNavbar">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        <li><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                        <li><a class="nav-link" href="{{ route('register') }}">Daftar</a></li>
                        @else
                        <span class="navbar-text">
                            Wallet: 0 |
                        </span>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{Storage::disk('public')->url(Auth::user()->avatar)}}" height="25" class="mr-1">
                                Hi, {{ ucfirst(Auth::user()->name) }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('member/profile') }}">
                                    Profil
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                Keluar
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-pd-dark" id="secondary-navbar">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#secondaryNavbar" aria-controls="secondaryNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="secondaryNavbar">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="{{ url('member/dashboard') }}" class="nav-link">Dasbor</a>
                    </li>
                    @role('borrower')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('member/loans') }}">Riwayat Pinjaman</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('member/loans/create') }}">Pengajuan Pinjaman</a>
                    </li>
                    @endrole
                    @role('lender')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('member/investments') }}">Riwayat Pendanaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('marketplace') }}">Pengajuan Pendanaan</a>
                    </li>
                    @endrole
                    <li class="nav-item">
                        <a class="nav-link" href="#">Riwayat Transaksi</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth

    <main class="py-4">
        @yield('content')
    </main>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

</body>
<footer>

    <!-- scripts for separator in every 3 digit -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://localhost:8000/js/jquery.maskMoney.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://localhost:8000/js/jquery.maskMoney.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.maskMoney.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#id_input_pinjam").maskMoney({thousands:".", precision:0});
        });
    </script>

   
</footer>
</html>
