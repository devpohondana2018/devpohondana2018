@extends ('layouts.transparent-header-about')

@section ('content')

<section id="slider" class="slider-parallax swiper_wrapper full-screen force-full-screen nobottommargin" data-direction="vertical">
	<div class="div-nav-swiper">
		<ul>
			<li>
				<span class="nav-swiper" data-id="0">POHONDANA</span>
			</li>
			<li>
				<span class="nav-swiper" data-id="1">TENTANG POHONDANA</span>
			</li>
			<li>
				<span class="nav-swiper" data-id="2">JENIS LAYANAN</span>
			</li>
			<li>
				<span class="nav-swiper" data-id="3">BATAS PINJAMAN</span>
			</li>
			<li>
				<span class="nav-swiper" data-id="4">SISTEM KEAMANAN</span>
			</li>
			<li>
				<span class="nav-swiper" data-id="5">PARTNER</span>
			</li>
			<li>
				<span class="nav-swiper" data-id="6">KONTAK KAMI</span>
			</li>
		</ul>
	</div>

	<div class="slider-parallax-inner">
		<div class="swiper-container swiper-parent">
			<div class="swiper-wrapper swiper-about">
				<div class="swiper-slide swiper-slider-about dark" style="background-image: url('{{ asset('images/about/about-03.png') }}');">
					<div class="container">

						<div class="col-md-4 hidden-laptop col-md-offset-2">
							<div class="slider-caption tleft" style="top:auto; bottom:0; left:auto; right:0;">
								<img src="{{ asset('images/pohondana/logo-bottom-white.png') }}">
							</div>	
						</div>

						<div class="col-md-12 hidden-mobile">
								<img style="width: 25%;position: relative; top: 250px;" src="{{ asset('images/pohondana/logo-bottom-white.png') }}">
						</div>
					</div>
				</div>
				<div class="swiper-slide dark" style="background-image: url('{{ asset('images/about/about-02.png') }}');">
					<div class="container">

						<div class="col-md-4 col-md-offset-2 col-xs-12">
							<div class="slider-caption tleft">
								<h2>Tentang Pohondana</h2>
								<hr class="hr-pd">
								<p class="caption-about">Pohon Dana didirikan dengan satu tujuan yang jelas: memberi layanan finansial yang lebih baik dan lebih efisien kepada setiap konsumen dengan memanfaatkan teknologi online terbaru kami.</p>

								<p class="caption-about">Dengan Teknologi Online akan memudahkan akses kepada seluruh Konsumen, sehinggga pengajuan Layanan Financial dapat mereka lakukan dimana saja dan kapan saja</p>
							
							</div>	
						</div>

					</div>
				</div>
				<div class="swiper-slide dark" style="background-image: url('{{ asset('images/about/about-03.png') }}');">
					<div class="container">
						<div class="col-md-4 col-md-offset-2 col-xs-12">
							<div class="slider-caption tleft">
								<h2>Jenis Layanan</h2>
								<hr class="hr-pd">

								<p>Pengajuan Pinjaman (Kredit)</p>
								<p>Penempatan Dana<!--  (Investasi) --></p>

								<h2>Keunggulan Layanan</h2>
								<hr class="hr-pd">
								<p>1. Proses Cepat</p>
								<p>2. Bisa diajukan kapan saja dan dimana saja</p>
								<p>3. Biaya jelas dan transparant</p>
							</div>	
						</div>
					</div>
				</div>
				<div class="swiper-slide dark" style="background-image: url('{{ asset('images/about/about-04.png') }}');">
					<div class="container">
						<div class="col-md-10 col-md-offset-2 col-xs-12">
							<div class="slider-caption tleft img-batas-pinjaman">
								<h2>Batas Pinjaman</h2>
								<hr class="hr-pd">
								<img id="tentang-batas-1" src="{{ asset('images/about/about-08.png') }}">
								<img id="tentang-batas-2" src="{{ asset('images/about/about-09.png') }}">
								<img id="tentang-batas-3" src="{{ asset('images/about/about-10.png') }}">
								<p>Pelunasan dipercepat tidak dikenakan biaya penalty</p>
								<p>Penentuan tingkat suku bunga dapat direview dan berubah mengikuti perkembangan kondisi Moneter</p>
							</div>	
						</div>
					</div>
				</div>
				<div class="swiper-slide dark" style="background-image: url('{{ asset('images/about/about-05.png') }}');">
					<div class="container">
						<div class="col-md-4 col-md-offset-2 col-xs-12">
							<div class="slider-caption tleft">
								<h2>Sistem Keamanan</h2>
								<hr class="hr-pd">
								<p>Timeout Otomatis Hal ini mencegah orang yang ingin tahu untuk melanjutkan sesi online Anda jika Anda meninggalkan PC Anda tanpa dijaga tanpa log out</p>

								<p>Online Statement adalah faksimili dari laporan keuangan tradisional dikemas dan dikirimkan kepada Anda dengan aman di Internet. Dengan menghilangkan kertas statement, Anda membantu menghentikan pencuri mencuri informasi Anda dari kotak surat.</p>
							</div>	
						</div>
					</div>
				</div>
				<div class="swiper-slide dark" style="background-image: url('{{ asset('images/about/about-06.png') }}');">
					<div class="container">
						<div class="col-md-4 col-md-offset-2 col-xs-12">
							<div class="slider-caption tleft">
								<h2>Partner</h2>
								<hr class="hr-pd">
								<p>PT. Pohon Dana Indonesia (Perusahaan) merupakan badan hukum yang didirikan berdasarkan Hukum Republik Indonesia. <!-- Berdiri sebagai perusahaan yang telah terdaftar dan dalam pengawasan Otoritas Jasa Keuangan (OJK) di Indonesia. --></p>

								<p>PT. Pohon Dana Indonesia adalah pemilik tunggal atau pemegang sah semua hak atas merek dagang, merek jasa, nama dagang, logo dan ikon Pohon Dana. Logo dan/atau merek atas nama Pohon Dana mencakup hak milik intelektual yang dilindungi oleh undang-undang atas merek dan undang-undang yang melindungi kekayaan intelektual lainnya yang berlaku diseluruh dunia.</p>
								<!-- <img src="{{ asset('images/about/about-08.png') }}">
								<img src="{{ asset('images/about/about-08.png') }}"> -->
							</div>	
						</div>
					</div>
				</div>
				<div class="swiper-slide dark" style="background-image: url('{{ asset('images/about/about-07.png') }}');">
					<div class="container">
						<div class="col-md-4 col-md-offset-2 col-xs-12">
							<div class="slider-caption tleft">
								<h2>Kontak Kami</h2>
								<hr class="hr-pd">
								<p>PT. POHON DANA INDONESIA</p>
								<p>Mayapada Tower 1 LT. 8</p>
								<p>Jl. Jendral Sudirman Kav. 28</p>
								<p>Jakarta 12920</p>
								<p>Telepon : (021) 5229660</p>
								<p>Whatsapp : (081) 11829660</p>
								<p>E-mail : customerservice@pohondana.id</p>


							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>

</div>
</section><!-- #content end -->

</section>
<!-- <div class="section parallax full-screen nomargin noborder section-about" style="background-image: url('images/about/about-background.jpg'); background-size: cover" data-stellar-background-ratio="0.4">
	<div class="vertical-middle">
		<div class="container clearfix">

			<div class="col-md-12 nobottommargin">

				<div class="emphasis-title title-about">
					<h2>Tentang Pohon Dana</h2>

					<hr class="hr-pd">

					<p class="lead">Pohon Dana didirikan dengan satu tujuan yang jelas: memberi layanan finansial yang lebih baik dan lebih efisien kepada setiap konsumen dengan memanfaatkan teknologi online terbaru kami.</p>

					<p class="lead">Perjalanan kami dimulai pada tahun 2017 dengan mengetahui adanya peluang besar di industri jasa keuangan online.Banyak kebutuhan kredit di Indonesia tidak terpenuhi karena lebih dari setengah penduduk Indonesia tidak memiliki akses ke sistem keuangan dan kredit melalui bank ataupun institute finansial lainnya, dan hal ini mengahalangi mereka untuk memulai usaha maupun mengembangkan usaha yang sudah ada untuk meningkatkan kualitas hidup mereka dan keluarga.</p>

					<p class="lead">Dengan hadirnya Pohon Dana, kami memberikan solusi kepada para konsumen yang tadinya tidak mudah untuk mendapatakan akses kredit ataupun menempatkan dana mereka dengan imbalan bunga yang kompetitif.</p>

					<p class="lead">Kami di Pohon Dana percaya bahwa setiap orang layak mendapatkan kesempatan yang sama seperti orang lain dalam hal mendaptakan kredit maupun berinvestasi, oleh karena itu sebabnya kami menawarkan jasa pendanaan dan pinjaman yang lebih mudah dan efisien. Bila Anda membutuhkan dana  dengan cepat, kami memberikan alternatif yang lebih aman dan nyaman dibandingkan dengan pinjaman bank yang memerlukan proses yang lebih panjang. Kami juga menawarkan bagi yang ingin berinvestasi dengan menempatkan dana mereka melalui Pohon Dana.</p>

					<p class="lead">Mendaftar secara online hanya membutuhkan beberapa menit dan penyetujuan kredit instan dalam maximal 3 hari kerja. Syarat dan biaya kami paparkan secara transparan, sehingga anda tidak perlu khawatir dengan adanya biaya-biaya lain.</p>
				</div>

			</div>

		</div>
	</div>
</div> -->
@endsection