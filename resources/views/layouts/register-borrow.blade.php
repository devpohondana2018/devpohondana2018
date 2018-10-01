<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,500i,700|Roboto:400,400i,500,700" rel="stylesheet">
    <!-- Document Title
      ============================================= -->
    <style type="text/css">
        .tt-menu {
            border: 1px solid #d9d9d9;
            background: white;
            padding: 10px;
            width: 100%;
            height: 200px;
            overflow-y: auto;
        }
        
        .twitter-typeahead {
            width: 100%;
        }
        
        .tt-suggestion {
            margin-bottom: 5px;
            color: black;
            padding-bottom: 5px;
            border-bottom: 1px solid #d9d9d9;
        }
        
        .tt-suggestion:hover {
            cursor: pointer;
        }

        @media only screen and (max-width: 1024px) {
            .modal-dialog {
                position: relative;
                top: 10%;
            } 
        }
        @media only screen and (min-width: 1025px) {
            .modal-dialog {
                position: relative;
                top: 20%;
            } 
        }
    </style>

    <title>Pohon Dana | @yield('title', 'Pengajuan Pinjaman')</title>
    <!-- Style Canvas -->
    <link rel="stylesheet" href="{{ asset('css/pohondana/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/style.css') }}?ver={{ date('m.d.h') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/swiper.css') }}" type="" pe="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/dark.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/font-icons.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/animate.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/magnific-popup.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/responsive.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/custom-style.css') }}?ver=1.{{ date('d.i.s') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/custom-register-borrow.css') }}?ver=1.{{ date('d.i.s') }}" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('images/pohondana/favicon.png') }}" />
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!--Start of Zendesk Chat Script-->
    <script type="text/javascript">
    window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
    d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
    _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
    $.src="https://v2.zopim.com/?5aEJ0MlMV8MdY784JxoTlL0LcVajI95l";z.t=+new Date;$.
    type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
    </script>
    <!--End of Zendesk Chat Script-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    @yield('style')
    
</head>

<body class="stretched" data-loader-html="<img src='{{ asset('images/pohondana/logo_pd-ojk.png') }}' alt='Loading Page' style='position:absolute; width:200px; auto; top:50%; left:50%; margin-left:-100px; margin-top:-50px;'">

    <!-- Document Wrapper
      ============================================= -->
    <div id="wrapper" class="clearfix">

        <!-- Header
      ============================================= -->
        <header id="header" class="sticky-header" data-sticky-class="not-dark">

            <div id="header-wrap">

                <div class="container clearfix">

                    <div id="primary-menu-trigger"><i class="icon-reorder"></i></div>
                    <input type="hidden" name="autocomplete_url" value="{{url('autocomplete')}}">
                    <input type="hidden" name="checkemail_url" value="{{url('checkemail')}}">
                     <input type="hidden" name="checkmobile_url" value="{{url('checkmobile')}}">
                    <input type="hidden" name="checkKTP_url" value="{{url('checkKTP')}}">
                    <input type="hidden" name="checkNPWP_url" value="{{url('checkNPWP')}}">

                    <!-- Logo
                  ============================================= -->
                    <div id="logo">
                        <a href="{{ url('/') }}" class="standard-logo" data-dark-logo="{{ asset('images/pohondana/logo_pd-ojk.png') }}"><img src="{{ asset('images/pohondana/logo_pd-ojk.png') }}" alt="Pohondana"></a>
                        <a href="{{ url('/') }}" class="retina-logo hidden-laptop" data-dark-logo="{{ asset('images/main-logo.png') }}">
                            <img src="{{ asset('images/pohondana/logo_pd-ojk.png') }}" alt="Pohondana Logo">
                        </a>
                    </div>
                    <!-- #logo end -->

                    <!-- Primary Navigation
                  ============================================= -->
                    <nav id="primary-menu" class="dark">
                        <ul>
                            @auth
                            <li>
                                <a href="{{ url('/pendanaan') }}">
                                    <div>Pendanaan</div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/register') }}">
                                    <div>Pinjaman</div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/tentang-kami') }}">
                                    <div>Tentang Pohon Dana</div>
                                </a>
                            </li>
                            <!-- <li><a href="{{ url('member') }}">Profil</a></li> -->
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <div>
                                        Logout</div>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            @else
                            <li>
                                <a href="{{ url('/pendanaan') }}">
                                    <div>Pendanaan</div>
                                </a>
                            </li>
                            <!-- <li><a href="#borrow-modal" data-toggle="modal" data-target=".borrow-modal">Borrow</a></li> -->
                            <li>
                                <a href="{{ url('/register') }}">
                                    <div>Pinjaman</div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/tentang-kami') }}">
                                    <div>Tentang Pohon Dana</div>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('login') }}">
                                    <div>Login</div>
                                </a>
                                <!-- <li><a href="{{ route('register') }}"><div>Daftar</div></a> -->
                                @endauth

                            </li>
                        </ul>
                    </nav>
                    <!-- #primary-menu end -->
                </div>
            </div>

        </header>
        <!-- #header end -->

        @if(Route::current()->getName() == 'frontpage')

        <section id="slider" class="slider-parallax swiper_wrapper full-screen force-full-screen clearfix">
            <div class="slider-parallax-inner">
                <div class="swiper-container swiper-parent">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide dark" style="background-image: url('{{ asset('images/home/header_background_lg.jpg') }}');">
                            <div class="container clearfix">
                                <div class="slider-caption slider-caption-center">
                                    <h2 data-caption-animate="fadeInUp" style="font-size:30px">POHON DANA – SOLUSI KEBUTUHAN FINANSIAL ANDA</h2>
                                    <p data-caption-animate="fadeInUp" data-caption-delay="200" style="font-size: 18px; margin-bottom: 25px;">Bersama POHON DANA semua kebutuhan finansial ANDA dapat terwujud.
                                        <br> Saatnya maraih impian anda dengan perencanaan finansial yang tepat</p>

                                    <a href="{{ route('register') }}" class="button btn-primary-mayapada">Bergabunglah Sekarang</a>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- <a href="#" data-scrollto="#content" data-offset="100" class="dark one-page-arrow"><i class="icon-angle-down infinite animated fadeInDown"></i></a> -->

            </div>
        </section>
        @endif @yield('content')

        <!-- Footer
          ============================================= -->
        <footer id="footer" class="white">
            <div class="container">
                <!-- Footer Widgets
              ============================================= -->
                <div class="col-md-4 noleftpadding">
                    <ul>
                        <li><a href="{{ asset('tentang-kami') }}">Tentang Pohon Dana</a></li>
                        <li><a href="{{ asset('syarat-dan-ketentuan') }}">Syarat dan Ketentuan</a></li>
                        <li><a href="{{ asset('privacy-policy') }}">Kebijakan Privasi</a></li>
                        <li><a href="{{ asset('kontak-kami') }}">Kontak Kami</a></li>
                    </ul>
                </div>
                <div class="fine-print col-xs-12 col-md-8 nopadding">
                    <p class="quietText mb15">Informasi di situs ini bukan merupakan penawaran untuk menjual sekuritas atau permintaan untuk membeli sekuritas. Selanjutnya, tidak ada informasi yang terkandung di situs ini adalah rekomendasi untuk pendanaan dalam sekuritas apapun.
                        <p class="quietText bank">Jaringan Pohon Dana dapat menjual sekuritas itu hanya menerbitkan kepada pendana terakreditasi secara rahasia atas permintaan mereka untuk dipertimbangkan.</p>
                </div>

            </div>

            <div class="container">
                <hr>
            </div>
            <!-- Copyrights
              ============================================= -->
            <div id="copyrights">

                <div class="container copyrights-container clearfix nopadding">

                    <div class="col-md-7">
                        <p>Copyright © 2018 Pohon Dana. All Rights Reserved.</p>
                    </div>

                    <div class="col-md-4 div-ojk">
                        <div class="col-md-7 nopadding">
                            <p>Terdaftar dan Diawasi oleh</p>
                        </div>
                        <div class="col-md-5 nopadding">
                            <img src="{{ asset('images/logo_ojk.png') }}" width="100">
                        </div>
                    </div>

                </div>

            </div>

    </div>
    <!-- #copyrights end -->

    <div class="modal fade" id="modal-loading" style="text-align: center; padding: 0px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Infomasi</h4>
                </div>
                <div class="modal-body">
                    <img style="width: 150px; margin-bottom: 30px;" src="{{ asset('images/pohondana/logo_pd-ojk.png') }}">
                    <p>
                    Mohon tidak memuat kembali halaman ini
                    <br> 
                    Silahkan tunggu beberapa saat
                    </p>
                    <img style="width: 100px;" src="{{ asset('images/pohondana/loading-bar.gif') }}">
                </div>
            </div>
        </div>
    </div>

    </footer>
    <!-- #footer end -->

    </div>
    <!-- #wrapper end -->

    <!-- Go To Top
      ============================================= -->
    <div id="gotoTop" class="icon-angle-up"></div>

    <!-- External JavaScripts
      ============================================= -->
    <script type="text/javascript" src="{{ asset('js/pohondana/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/pohondana/plugins.js') }}"></script>

    <!-- Footer Scripts
      ============================================= -->
    <script type="text/javascript" src="{{ asset('js/pohondana/functions.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.maskMoney.min.js')}}"></script>
    <!-- <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script> -->

    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <script src="http://code.jquery.com/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/pohondana/register-borrow.js')}}?ver=1.{{ date('d.i.s') }}"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.bundle.js"></script>
    <script type="text/javascript" src="{{ asset('js/pohondana/rekanan-typeahead.js')}}?ver=1.{{ date('d.i.s') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    
    $(document).ready(function($) {
      $("#dob").datepicker(
      {
          minDate: new Date(1900,1-1,1), maxDate: '-18Y',
          dateFormat: 'dd/mm/yy',
          defaultDate: new Date(2000,1-1,1),
          changeMonth: true,
          changeYear: true,
          yearRange: '-110:-18'
        }
      );   
    });

</script>
    <!-- <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script> -->
    @yield('javascript')
</body>

</html>