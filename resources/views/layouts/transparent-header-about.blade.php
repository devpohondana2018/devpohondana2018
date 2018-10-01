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
    <link rel="stylesheet" href="{{ asset('css/pohondana/style.css') }}?ver={{ date('m.d.h') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/swiper.css') }}" type="" pe="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/dark.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/font-icons.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/animate.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/magnific-popup.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/responsive.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/pohondana/custom-style.css') }}?ver={{ date('m.d.h') }}" type="text/css" />
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
        <header id="header" class="transparent-header header-about" data-sticky-class="not-dark">

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
                            <div class="col-md-5 nopadding img-footer-ojk">
                                <img src="{{ asset('images/logo_ojk.png') }}" width="100">
                            </div>
                        </div>

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
    <script src="{{ asset('js/pohondana/swiper.min.js') }}"></script>

    @if(Route::current()->getName() == 'tentang-kami')
	<!-- Initialize Swiper -->
	<script>
		if (screen.width > 1024){
			var swiper = new Swiper('.swiper-container', {
				direction: 'vertical',
				pagination: {
					el: '.swiper-pagination',
					clickable: true,
				},
			});

			$('.nav-swiper').click(function() {
				var pos = $(this).data('id');
				swiper.slideTo(pos,2000);
			});
			swiper.mousewheel.enable();
		} else  {
			var swiper = new Swiper('.swiper-container', {
				pagination: {
					el: '.swiper-pagination',
				},
			});

			
		}
	</script>
	@endif
</body>
</html>