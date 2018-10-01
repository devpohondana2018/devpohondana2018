@extends ('layouts.transparent-header')

@section('content')

<!-- Content
	============================================= -->
	<section id="content">

		<section style="background-color: #f5f5f5; padding-top: 30px;" class="marbot-50">
			<div class="container proses-cepat clearfix">
				<div class="heading-block marbot-50 center">
					<h2>Proses Cepat & Mudah - Bunga Yang Kompetitif -<br> Keamanan & Kerahasiaan Terjamin</h2>
				</div>

				<div class="col-md-12 nopadding common-height">

					<div class="col-md-4 dark col-padding ohidden" style="background-color: #009344;">
						<div>
							<h3 class="uppercase" style="font-weight: 600;">3</h3>
							<p style="line-height: 1.8;">Proses cepat 3 hari</p>
							<i class="icon-dashboard bgicon"></i>
						</div>
					</div>

					<div class="col-md-4 dark col-padding ohidden" style="background-color: #34495e;">
						<div>
							<h3 class="uppercase" style="font-weight: 600;">2</h3>
							<p style="line-height: 1.8;">2 jam cair</p>
							<i class="icon-cog bgicon"></i>
						</div>
					</div>

					<div class="col-md-4 dark col-padding ohidden" style="background-color: #e74c3c;">
						<div>
							<h3 class="uppercase" style="font-weight: 600;">1</h3>
							<p style="line-height: 1.8;">1 Aplikasi</p>
							<i class="icon-screen bgicon"></i>
						</div>
					</div>
				</div>
			</div>

		</section>

		<section id="section-pinjaman-cerdas" style="background-image: url('{{ asset('images/home/front-02.png') }}');">

			<div class="container clearfix">
				<div class="row topmargin-lg bottommargin-sm">
					<div class="center heading-block marbot-50">
						<h2>Pinjaman cepat hanya dalam 3 langkah mudah</h2>
					</div>

					<div class="col-md-6 bottommargin center">
						<img src="{{ asset('images/home/home-form.png') }}">
					</div>

					<div class="col-md-6 bottommargin">

						<div class="feature-box topmargin" data-animate="fadeIn">
							<div class="fbox-icon">
								<i>1</i>
							</div>
							<h3>Daftarkan diri anda</h3>
							<p>Lengkapi semua data diri anda pada formulir yang disediakan.</p>
						</div>

						<div class="feature-box topmargin" data-animate="fadeIn" data-delay="200">
							<div class="fbox-icon">
								<i>2</i>
							</div>
							<h3>Ajukan pinjaman</h3>
							<p>Pilih jumlah pinjaman dan jangka waktu pengembalian yang anda kehendaki.</p>
						</div>

						<div class="feature-box topmargin" data-animate="fadeIn" data-delay="400">
							<div class="fbox-icon">
								<i>3</i>
							</div>
							<h3>Tarik dana di rekening anda</h3>
							<p>Setelah pengajuan anda disetujui, dana akan ditransfer ke rekening anda dan siap  untuk digunakan</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="container simulasi">
			<div class="col-md-6 col-md-offset-3 nopadding martop-50">
				<div class="col-md-12 center heading-block marbot-50">
					<h2>Simulasikan Pinjamanmu</h2>
				</div>


				<div class="col-md-12 nopadding marbot-50">
					<div class="col-md-12 nopadding marbot-15">
						<div class="col-md-12 form-group nopadding">
							Jumlah Pinjaman : Rp.
							<input readonly="" class="width-money" type="text" id="amount" value="1000000"/>
						</div>
					</div>

					<div id="slider1" class="col-md-12 nopadding" style>
						<div id="myhandle"></div>
					</div>
				</div>

				<div class="col-md-12 nopadding marbot-15">
					Untuk Jangka Waktu :&nbsp&nbsp
					<input readonly="" class="width-bulan" type="text" id="days" value="5"/>&nbsp Bulan   
				</div>

				<div id="slider2" class="col-md-12 nopadding">
					<div id="myhandle"></div>
				</div>

			</div>

			<div class="col-md-6 col-md-offset-3 marbot-25">
				<div class="form-group col-md-6 nopadding center marbot-50">
					<div class="col-md-12 col-sm-6 col-xs-12 center nopadding">Tanggal Jatuh Tempo</div>
					<div class="col-md-12 col-sm-6 col-xs-12 input-simulasi center nopadding" id="date"></div>
				</div>

				<div class="form-group col-md-6 nopadding center marbot-50">
					<div class="col-md-12 col-sm-6 col-xs-12 center nopadding">Pembayaran Per Bulan</div>
					<div class="col-md-12 col-sm-6 col-xs-12 center nopadding">
						<input readonly="" class="input-simulasi" id="amount5" type="text" />
					</div>
				</div>

				<div class="form-group col-md-6 nopadding center marbot-50">
					<div class="col-md-12 col-sm-6 col-xs-12 center nopadding">Jumlah Pembayaran</div>
					<div class="col-md-12 col-sm-6 col-xs-12 center nopadding">
						<input readonly="" class="input-simulasi" id="amount2" type="text" />
					</div>
				</div>

				<div class="form-group col-md-6 nopadding center marbot-50">
					<div class="col-md-12 col-sm-6 col-xs-12 center nopadding">Bunga</div>
					<div class="col-md-12 col-sm-6 col-xs-12 center nopadding">
						<input readonly="" class="input-simulasi" id="amount3" type="text" />
					</div>
				</div>

			</div>

			<div class="col-md-8 col-md-offset-2 marbot-50">
				<h4 class="center">Perhatian</h4>
				<ol type="1">
					<li class="number" style="font-size: 13px; margin-bottom: 15px;">Perhitungan di atas hanya untuk tujuan indikatif dan bukan merupakan penawaran fasilitas pinjaman. Pemberian fasilitas pinjaman tergantung pada proses dan kebijaksanaan Pohon Dana.</li>
					<li class="number" style="font-size: 13px; margin-bottom: 15px;">Pohon Dana tidak bertanggung jawab atas kesalahan atau kelalaian, dan juga kerugian yang timbul dari penggunaan atau ketergantungan pada perhitungan simulasi ini.</li>
					<!-- <li class="number">Untuk membantu perencanaan Anda, kami senang untuk memberikan permintaan melalui layanan pelanggan kami melalui situs Pohon Dana maupun menghubungi kami melalui email atau telpon.</li> -->
					<li class="number" style="font-size: 13px; margin-bottom: 15px;">Jumlah pinjaman maksimum dan jangka waktu pinjaman tergantung pada persyaratan dan peraturan yang berlaku. Pohon Dana berhak untuk meninjau peraturan dan persyaratan fasilitas pinjaman tersebut dari waktu ke waktu.</li>
				</ol>
				<p class="text-justify" style="font-size: 13px; ">Catatan: Pohon Dana telah menerbitkan panduan fasilitas pinjaman dan pendanaan. Anda dianjurkan untuk mempelajari panduan tersebut sebelum melakukan pinjaman maupun pendanaan. Panduan ini tersedia di situs web Pohon Dana dan Otoritas Jasa Keuangan.</p>
			</div>
		</section>

		<div class="section parallax dark nobottommargin" style="margin-top: 0px;background: url('images/home/team_background.jpg'); background-repeat: no-repeat; background-size: cover; background-position: top center; padding-bottom: 0px;">
			<div class="container">
				<div class="heading-block center" style="">
					<h2>TEAM KAMI SIAP MEMBANTU ANDA</h2>
				</div>

				<div class="home-ops-panel">
					<div class="col-xs-12 col-sm-4">
						<div class="home-ops-panel-item">
							<div class="home-ops-panel-content">
								<p class="height-testimonial">Berkat <strong>Pohon Dana</strong>, akhirnya anak kami dapat melanjutkan sekolahnya</p>
								<div class="quote-attribution smaller"><b>Melati Sucipto</b>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-sm-4">
						<div class="home-ops-panel-item">
							<div class="home-ops-panel-content">
								<p class="height-testimonial"><strong>Pohon Dana</strong> membantu saya ketika harus membayar biaya rumah sakit, proses pinjamannya cepat dan tidak rumit</p>
								<div class="quote-attribution smaller"><b>Moh. Ariefin S</b>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-sm-4">
						<div class="home-ops-panel-item">
							<div class="home-ops-panel-content">
								<p class="height-testimonial">Pendanaan di <strong>Pohon Dana</strong>, hasilnya nyata dan sangat memuaskan</p>
								<div class="quote-attribution smaller"><b>Debbie Saputra</b>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12 col-xs-12">
						<p style="margin-top: 25px; margin-bottom: 25px; text-align: center;">Testimonial berasal dari pelanggan Pohon Dana yang sebenarnya.</p>
					</div>
				</div>
			</div>

<!-- Closing Notification Windows
	============================================= -->


	<!-- link rel="stylesheet" href="{{ asset('css/CSSPopup.css') }}" type="text/css" / -->

	<!--button id="myBtn">Open Modal</button -- DON'T USE THIS LINE -->
	<!-- div id="myModal" class="modal">
		<div class="modal-content">
    			<span class="close">&times;</span>
    			<p></p>
  		</div>
	</div -->

	<script type="text/javascript">
	var modal = document.getElementById('myModal');
	var btn = document.getElementById("myBtn");
	var span = document.getElementsByClassName("close")[0];
	// btn.onclick = function() {
    		modal.style.display = "block";
	// }
	span.onclick = function() {
    		modal.style.display = "none";
	}
	window.onclick = function(event) {
    		if (event.target == modal) {
        		modal.style.display = "none";
    		}
	}
	</script>

<!-- Closing Notification Windows END
	============================================= -->


	@endsection