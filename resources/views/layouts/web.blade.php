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
    <title>Pohon Dana | {{ ucfirst(Route::current()->getName())}}</title>
    <!-- Style Canvas -->
    <link rel="stylesheet" href="{{ asset('css/pohondana/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/style.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/swiper.css') }}" type="" pe="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/dark.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/font-icons.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/animate.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/magnific-popup.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/responsive.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/custom-style.css') }}" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('images/pohondana/favicon.png') }}" />
    <!--Start of Zendesk Chat Script-->
        <script type="text/javascript">
        window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
        d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
        _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
        $.src="https://v2.zopim.com/?5aEJ0MlMV8MdY784JxoTlL0LcVajI95l";z.t=+new Date;$.
        type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
        </script>
        <!--End of Zendesk Chat Script-->
	

</head>
<body class="stretched" data-loader-html="<img src='{{ asset('images/pohondana/logo_pd-ojk.png') }}' alt='Loading Page' style='position:absolute; width:200px; auto; top:50%; left:50%; margin-left:-100px; margin-top:-50px;'">

    <!-- Document Wrapper
    ============================================= -->
    <div id="wrapper" class="clearfix">

    <!-- Header
        ============================================= -->
        <header id="header" class="sticky-header"  data-sticky-class="not-dark">

            <div id="header-wrap">

                <div class="container clearfix">

                    <div id="primary-menu-trigger"><i class="icon-reorder"></i></div>

                <!-- Logo
                    ============================================= -->
                    <div id="logo">
                        <a href="{{ url('/') }}" class="standard-logo" data-dark-logo="{{ asset('images/pohondana/logo_pd-ojk.png') }}">
                            <img src="{{ asset('images/pohondana/logo_pd-ojk.png') }}" alt="Pohondana">
                        </a>
                        <a href="{{ url('/') }}" class="retina-logo hidden-laptop" data-dark-logo="{{ asset('images/main-logo.png') }}">
                        <img src="{{ asset('images/pohondana/logo_pd-ojk.png') }}" alt="Pohondana Logo">
                        </a>
                    </div><!-- #logo end -->

                <!-- Primary Navigation
                    ============================================= -->
                    <nav id="primary-menu" class="dark">
                        <ul>
                            @auth
                                <li><a href="{{ url('/pendanaan') }}"><div>Pendanaan</div></a></li>
                                <li><a href="{{ url('/register') }}"><div>Pinjaman</div></a></li>
                                <li><a href="{{ url('/tentang-kami') }}"><div>Tentang Pohon Dana</div></a></li>
                                <li><a href="{{ url('member/dashboard') }}">My Account</a></li>
                                <!-- <li><a href="{{ url('member') }}">Profil</a></li> -->
                                <!-- <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"><div>
                                    Logout</div>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li> -->
                            @else
                            <li><a href="{{ url('/pendanaan') }}"><div>Pendanaan</div></a></li>
                            <!-- <li><a href="#borrow-modal" data-toggle="modal" data-target=".borrow-modal">Borrow</a></li> -->
                            <li><a href="{{ url('/register') }}"><div>Pinjaman</div></a></li>
                            <li><a href="{{ url('/tentang-kami') }}"><div>Tentang Pohon Dana</div></a></li>
                           
                            <li><a href="{{ route('login') }}"><div>Login</div></a></li>
                            @endauth
                        </ul>
                    </nav><!-- #primary-menu end -->
                </div>
            </div>

        </header><!-- #header end -->


        @if(Route::current()->getName() == 'frontpage')

        <section id="slider" class="slider-parallax swiper_wrapper full-screen force-full-screen clearfix">
                <div class="slider-parallax-inner">
                    <div class="swiper-container swiper-parent">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide dark" style="background-image: url('{{ asset('images/pohondana/slider-home.jpg') }}');">
                                <div class="container clearfix">
                                    <div class="slider-caption slider-caption-center">
                                        <h2 data-caption-animate="fadeInUp" style="font-size:30px"><img src="{{ asset('images/pohondana/logo_pd-ojk.png') }}"></h2>
                                        <h2 data-caption-animate="fadeInUp" style="font-size:30px">SOLUSI KEBUTUHAN FINANSIAL ANDA</h2>
                                        <p data-caption-animate="fadeInUp" data-caption-delay="200" style="font-size: 18px; margin-bottom: 25px;">Bersama POHON DANA semua kebutuhan finansial ANDA dapat terwujud. <br> Saatnya maraih impian anda dengan perencanaan finansial yang tepat</p>

                                        @auth
                                        @else
                                        <div class="register-choice">
                                          <p data-caption-animate="fadeInUp" data-caption-delay="200" style="font-size: 18px; margin-bottom: 25px;">Anda ingin bergabung sebagai</p>
                                            <a href="{{url ('register') }}" class="button btn-primary-mayapada btn-left-home">Peminjam</a>
                                            <a href="{{url ('register/lender') }}" class="button btn-primary-mayapada">Pendana</a>
                                        </div>
                                        @endauth
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <a href="#" data-scrollto="#content" data-offset="100" class="dark one-page-arrow"><i class="icon-angle-down infinite animated fadeInDown"></i></a> -->

                </div>
            </section>
        @endif
        
        @yield('content')

        <!-- Footer
        ============================================= -->
        <footer id="footer" class="white">
            <div class="container text-footer-desc">
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
                    <p class="mb15">Informasi di situs ini bukan merupakan penawaran untuk menjual sekuritas atau permintaan untuk membeli sekuritas. Selanjutnya, tidak ada informasi yang terkandung di situs ini adalah rekomendasi untuk pendanaan dalam sekuritas apapun.
                    <p>Jaringan Pohon Dana dapat menjual sekuritas itu hanya menerbitkan kepada pendana terakreditasi secara rahasia atas permintaan mereka untuk dipertimbangkan.</p>
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
                    <!--     <div class="col-md-1 col-xs-12 nopadding">
                            <div class="fright clearfix">
                                <a href="#" class="social-icon si-small si-borderless si-facebook">
                                    <i class="icon-facebook"></i>
                                    <i class="icon-facebook"></i>
                                </a>

                                <a href="#" class="social-icon si-small si-borderless si-twitter">
                                    <i class="icon-twitter"></i>
                                    <i class="icon-twitter"></i>
                                </a>
                            </div>
                        </div> -->

                </div>

            </div><!-- #copyrights end -->

        </footer><!-- #footer end -->

    </div><!-- #wrapper end -->

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
</body>
</html>