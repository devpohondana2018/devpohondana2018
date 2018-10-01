<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Pohon Dana">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Pohon Dana | {{ ucfirst(Route::current()->getName())}}</title>

	<!--begin::Web font -->
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Montserrat:300,400,500,600,700"]},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
	<!--end::Web font -->
	<!--begin::Base Styles -->
	<link href="{{ asset('dashboard-member/assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('dashboard-member/assets/demo/demo3/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('dashboard-member/assets/custom-member.css') }}" rel="stylesheet" type="text/css" />
	<!--end::Base Styles -->
	<link rel="shortcut icon" href="{{ asset('images/pohondana/favicon.png') }}" />
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css"/>

	@yield('style')

</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
	<!-- begin:: Page -->
	<div class="m-grid m-grid--hor m-grid--root m-page">
		<!-- BEGIN: Header -->
		<!-- test -->
		<header class="m-grid__item    m-header "  data-minimize-offset="200" data-minimize-mobile-offset="200" >
			<div class="m-container m-container--fluid m-container--full-height">
				<div class="m-stack m-stack--ver m-stack--desktop">
					<!-- BEGIN: Brand -->
					<div class="m-stack__item m-brand  m-brand--skin-dark ">
						<div class="m-stack m-stack--ver m-stack--general">
							<div class="m-stack__item m-stack__item--middle m-stack__item--center m-brand__logo">
								<a href="{{ url('/') }}" class="m-brand__logo-wrapper">
									<img style="width: 10em;" alt="" src="{{ asset('images/pohondana/logo_pd-ojk.png') }}"/>
								</a>
							</div>
							<div class="m-stack__item m-stack__item--middle m-brand__tools">
								<!-- BEGIN: Responsive Aside Left Menu Toggler -->
								<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
									<span></span>
								</a>
								<!-- END -->

								<!-- END -->
								<!-- BEGIN: Topbar Toggler -->
								<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
									<i class="flaticon-more"></i>
								</a>
								<!-- BEGIN: Topbar Toggler -->
							</div>
						</div>
					</div>
					<!-- END: Brand -->

					<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
						<!-- BEGIN: Horizontal Menu -->
						<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
							<i class="la la-close"></i>
						</button>

						<!-- END: Horizontal Menu -->								<!-- BEGIN: Topbar -->
						<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
							<div class="m-stack__item m-topbar__nav-wrapper">
								<ul class="m-topbar__nav m-nav m-nav--inline">
									<li class="
									m-nav__item m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width m-dropdown--skin-light	m-list-search m-list-search--skin-light" 
									data-dropdown-toggle="click" data-dropdown-persistent="true" id="m_quicksearch" data-search-type="dropdown">

									<div class="m-dropdown__wrapper">
										<span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
										<div class="m-dropdown__inner ">
											<div class="m-dropdown__header">
												<form  class="m-list-search__form">
													<div class="m-list-search__form-wrapper">
														<span class="m-list-search__form-input-wrapper">
															<input id="m_quicksearch_input" autocomplete="off" type="text" name="q" class="m-list-search__form-input" value="" placeholder="Search...">
														</span>
														<span class="m-list-search__form-icon-close" id="m_quicksearch_close">
															<i class="la la-remove"></i>
														</span>
													</div>
												</form>
											</div>
											<div class="m-dropdown__body">
												<div class="m-dropdown__scrollable m-scrollable" data-max-height="300" data-mobile-max-height="200">
													<div class="m-dropdown__content"></div>
												</div>
											</div>
										</div>
									</div>
								</li>


								<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
									<a href="#" class="m-nav__link m-dropdown__toggle">
										<span class="m-topbar__userpic">
											<img src="{{ Auth::user()->avatar ? Storage::disk('public')->url(Auth::user()->avatar) : asset('images/avatars/default.jpg') }}" alt=""/>
										</span>
									</a>
									<div class="m-dropdown__wrapper">
										<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
										<div class="m-dropdown__inner">
											<div class="m-dropdown__header m--align-center" style="background: url({{Storage::disk('public')->url(Auth::user()->avatar)}}); background-size: cover;">
												<div class="m-card-user m-card-user--skin-dark">
													<div class="m-card-user__pic">
														<img src="{{ Auth::user()->avatar ? Storage::disk('public')->url(Auth::user()->avatar) : asset('images/avatars/default.jpg') }}" alt=""/>
													</div>
													<div class="m-card-user__details">
														<span class="m-card-user__name m--font-weight-500">
															{{ ucfirst(Auth::user()->name) }}
														</span>
													</div>
												</div>
											</div>
											<div class="m-dropdown__body">
												<div class="m-dropdown__content">
													<ul class="m-nav m-nav--skin-light">
														<li class="m-nav__section m--hide">
															<span class="m-nav__section-text">
																Section
															</span>
														</li>
														<li class="m-nav__item">
															<a href="{{ url('member/profile') }}" class="m-nav__link">
																<i class="m-nav__link-icon flaticon-profile-1"></i>
																<span class="m-nav__link-title">
																	<span class="m-nav__link-wrap">
																		<span class="m-nav__link-text">
																			My Profile
																		</span>
																	</span>
																</span>
															</a>
														</li>

														<li class="m-nav__item">
															<a href="{{ url('member/account') }}" class="m-nav__link">
																<i class="m-nav__link-icon flaticon-profile-1"></i>
																<span class="m-nav__link-title">
																	<span class="m-nav__link-wrap">
																		<span class="m-nav__link-text">
																			My Account
																		</span>
																	</span>
																</a>
															</li>
															<li class="m-nav__separator m-nav__separator--fit"></li>
															<li class="m-nav__item">
																<a href="{{ route('logout') }}" onclick="event.preventDefault();
																document.getElementById('logout-form').submit();" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
																Logout
															</a>
														</li>
														<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
															{{ csrf_field() }}
														</form>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</li>

						</ul>
					</div>
				</div>
				<!-- END: Topbar -->
			</div>

		</div>
	</div>
</header>
<!-- END: Header -->		
<!-- begin::Body -->
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
	<!-- BEGIN: Left Aside -->
	<button class="m-aside-left-close m-aside-left-close--skin-dark" id="m_aside_left_close_btn">
		<i class="la la-close"></i>
	</button>
	<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
		@auth
		<!-- BEGIN: Aside Menu -->
		<div 
		id="m_ver_menu" 
		class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark m-aside-menu--dropdown " 
		data-menu-vertical="true"
		data-menu-dropdown="true" data-menu-scrollable="true" data-menu-dropdown-timeout="500"  
		>
		<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">

			<li class="m-menu__item " aria-haspopup="true" >
				<a  href="{{ url('member/dashboard') }}" class="m-menu__link ">
					<span class="m-menu__item-here"></span>
					<i class="m-menu__link-icon flaticon-lifebuoy"></i>
					<span class="m-menu__link-text">
						Dasbor
					</span>
				</a>
			</li>

			@role('borrower')
			<li class="m-menu__item " aria-haspopup="true" >
				<a  href="{{ url('member/loans') }}" class="m-menu__link ">
					<span class="m-menu__item-here"></span>
					<i class="m-menu__link-icon flaticon-line-graph"></i>
					<span class="m-menu__link-text">
						Pinjaman Saya
					</span>
				</a>
			</li>

			<li class="m-menu__item " aria-haspopup="true" >
				<a  href="{{ url('member/loans/create') }}" class="m-menu__link ">
					<span class="m-menu__item-here"></span>
					<i class="m-menu__link-icon flaticon-network"></i>
					<span class="m-menu__link-text">
						Pengajuan Pinjaman
					</span>
				</a>
			</li>
			
			@endrole
			@role('lender')
			<li class="m-menu__item " aria-haspopup="true" >
				<a  href="{{ url('member/investments') }}" class="m-menu__link ">
					<span class="m-menu__item-here"></span>
					<i class="m-menu__link-icon flaticon-line-graph"></i>
					<span class="m-menu__link-text">
						Pendanaan Saya
					</span>
				</a>
			</li>

			<li class="m-menu__item " aria-haspopup="true" >
				<a  href="{{ url('marketplace') }}" class="m-menu__link ">
					<span class="m-menu__item-here"></span>
					<i class="m-menu__link-icon flaticon-network"></i>
					<span class="m-menu__link-text">
						Pengajuan Pendanaan
					</span>
				</a>
			</li>
			@endrole

		</ul>
	</div>
	@endauth
	<!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->
<div class="m-grid__item m-grid__item--fluid m-wrapper">

	<!-- <div class="m-subheader "> -->
		<!-- <div class="mr-auto">
			<h3 class="m-subheader__title m-subheader__title--separator center">
				Hello, {{ ucfirst(Auth::user()->name) }}
			</h3>

		</div> -->
		<div class="row">
	        <div class="dashboard-banner-pd" style="background-image: url('{{ asset('images/pohondana/slider-home.jpg') }}');">
	            <h1>Hello, {{ ucfirst(Auth::user()->name) }}</h1>
	        </div>
    </div>
	<!-- </div> -->

	<!-- END: Subheader -->
	<div class="m-content">
		@yield('content')
	</div>

	<div class="container martop-50">
		<div class="row">
	        <div class="col-xs-12 col-md-12 center">Anda punya pertanyaan?
	        <a target="_blank" href="/kontak-kami">Silahkan Kontak Kami</a>
	        atau bisa email kami di: <a href="mailto:accountofficer@pohondana.id">accountofficer@pohondana.id</a>.</div>
	    </div>
	</div>
</div>
</div>
<!-- end:: Body -->
<!-- begin::Footer -->

<!-- end::Footer -->
</div>
<!-- end:: Page -->
<!-- begin::Quick Sidebar -->

<!-- end::Quick Sidebar -->		    
<!-- begin::Scroll Top -->
<div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
	<i class="la la-arrow-up"></i>
</div>

<!-- begin::Quick Nav -->	
<!--begin::Base Scripts -->
<script src="{{ asset('dashboard-member/assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard-member/assets/demo/demo3/base/scripts.bundle.js') }}" type="text/javascript"></script>
<!--end::Base Scripts -->   
<!--begin::Page Snippets -->
<script src="{{ asset('dashboard-member/assets/app/js/dashboard.js') }}" type="text/javascript"></script>
<!--end::Page Snippets -->

<!-- table begin -->
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script> -->
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
		//e.preventDefault();
		/*$('.loader-full-screen').show();
		$('.m-grid--root').hide();*/
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

	var table = $('.data-tables-pohondana').DataTable( {
		scrollY: 400,
		paging: false
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
<!-- end::Body -->
</html>
