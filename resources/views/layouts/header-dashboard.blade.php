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
        <!--begin::Web font -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        
        <!-- Style Canvas -->
        <link rel="stylesheet" href="{{ asset('css/pohondana/bootstrap.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/style.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/swiper.css') }}" type="" pe="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/dark.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/font-icons.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/animate.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/magnific-popup.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/responsive.css') }}" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet"> -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
	   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css"/>
        <link rel="stylesheet" href="{{ asset('css/pohondana/custom-style.css') }}?ver={{ date('md') }}" type="text/css" />
        <link rel="shortcut icon" href="{{ asset('images/pohondana/favicon.png') }}" />
        <link rel="stylesheet" href="{{ asset('dashboard-member/assets/custom-member-new.css') }}?ver=1_{{ date('m.d.g') }}" type="text/css" />


        <style type="text/css">
            .label-default {
                background-color: transparent;
            }
        </style>
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
        <header id="header" class="transparent-header fixed"  data-sticky-class="not-dark">

            <div id="header-wrap">

                <div class="container clearfix">

                    <div id="primary-menu-trigger"><i class="icon-reorder"></i></div>

	                	<!-- Logo
	                    ============================================= -->
	                    <div id="logo">
	                        <a href="{{ url('/') }}" class="standard-logo" data-dark-logo="{{ asset('images/pohondana/logo-ojk-white.png') }}">
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
	                        	<li><a href="{{ url('member/dashboard') }}"><div>Home</div></a></li>
	                           	@role('borrower')
	                            <li><a href="{{ url('member/loans') }}"><div>Portofolio</div></a></li>
	                            <li><a href="{{ url('member/loans/create') }}"><div>Pengajuan Pinjaman</div></a></li>
	                            @endrole
	                           
	                           	@role('lender')
	                           	<li><a href="{{ url('member/investments') }}"><div>Portofolio</div></a></li>
	                            <li><a href="{{ url('marketplace') }}"><div>Pengajuan Pendanaan</div></a></li>
	                           	@endrole
	                           	<li class="sub-menu"><a href="#">My Account</a>
		                            <ul>
			                            <li><a href="{{ url('member/profile') }}">Profil</a></li>
			                            <li><a href="{{ url('member/account') }}">Setting</a></li>
			                            <li>
										<a href="{{ route('logout') }}" onclick="event.preventDefault();
										document.getElementById('logout-form').submit();">
										Logout
										</a>
										</li>
										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											{{ csrf_field() }}
										</form>
		                        </ul>
		                        </li>
	                           	
	                            
	                        </ul>
                        </nav><!-- #primary-menu end -->
                    </div>
                </div>

            </header><!-- #header end -->

            <section class="dashboard-banner-pd" style="background-image: url('{{ asset('images/pohondana/slider-home.jpg') }}');">
	            <h1 style="margin-bottom: 0px;">Hello, {{ ucfirst(Auth::user()->name) }}</h1>
	        </div>

            @yield('content')

            <div class="container martop-50">
				<div class="row">
			        <div class="col-xs-12 col-md-12 center help-footer">
                        Anda punya pertanyaan? <a target="_blank" href="/kontak-kami">Silahkan Kontak Kami</a> atau bisa email kami di: <a href="mailto:accountofficer@pohondana.id">accountofficer@pohondana.id</a>.
                    </div>
			    </div>
			</div>

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

<script type="text/javascript" src="{{ asset('js/pohondana/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/pohondana/plugins.js') }}"></script>

<!-- Footer Scripts
============================================= -->
<script type="text/javascript" src="{{ asset('js/pohondana/functions.js') }}"></script>

<!-- <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js'></script> -->

<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>

<script type="text/javascript" src="{{ asset('js/jquery.maskMoney.min.js')}}"></script>

@yield('javascript')

<script type="text/javascript">
    var imagePohonDana = "{{ asset('images/pohondana/logo_pd-ojk.png') }}";
    var imageLoading = "{{ asset('images/pohondana/loading-bar.gif') }}";
    var preloader = '<div id="form-after-submit" style="text-align: center; background-color: #ffffff; padding: 20px" class="col-md-12">' +
                        '<div class="col-md-12 center">' +
                            '<div class="content-popup">' +
                                '<h4 class="center infomasi-popup">Infomasi</h4>' +
                                '<br>' +
                                 '<img style="width: 150px; margin-bottom: 30px;" src="' + imagePohonDana + '">' +
                                '<p>' +
                                'Mohon tidak memuat kembali halaman ini' +
                                '<br> ' +
                                'Silahkan tunggu beberapa saat' +
                                '</p>' +
                                '<img style="width: 70px;" src="' + imageLoading + '">' +
                            '</div>' +
                        '</div>' +
                    '</div>';

    $('form').submit(function(e){
        $(this).hide();
        $(this).parent().append(preloader);
    });

    $(".amount_money_mask").maskMoney({thousands:".", precision:0});
    $(".amount_money_mask").keyup(function() {
        var value  = $('.amount_money_mask').val();
        value = replaceString(value);
    });  

    $(".create-form-pd").submit(function(){
        var value1 = $('.amount_money_mask').val();
        $('.amount_money_mask').val( parseInt(replaceString(value1)) );
    });

    replaceString = (value) => {
        while(value.indexOf('.') > 0){
            value = value.replace(".", "");
        }
        return value;
    } 
</script>

<script type="text/javascript">
    $(document).ready( function () {
        $('.data-tables-pohondana').DataTable( {
            // scrollY: 400,
            // paging: false,
            responsive: true
        } );
    } );
</script>

    <div class="loader-full-screen" style="
        position: absolute;
        width: 100%;
        auto;
        height:  100%;
        background-color: white;
        z-index:  10000;
        display: none">

        <div class="preloader-message"
             style='
                position:absolute; 
                width:200px; 
                auto; 
                top:50%; 
                left:50%; 
                margin-left:-100px; 
                margin-top:-50px;
                text-align: center'>
        
            <img src="{{ asset('images/pohondana/logo_pd-ojk.png') }}" 
                 alt='Loading Page' />
            <div class="message">
                Mohon tunggu...
            </div>
        </div>

    </div>

</body>
</html>