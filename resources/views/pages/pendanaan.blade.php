@extends ('layouts.web')

@section ('content')
<!-- Page Title
	============================================= -->
	<section id="page-title" class="page-title-invest" style="background: url(/images/invest/invest-01.png); background-size: cover">

		<div class="container clearfix center">
			<h1>Melakukan Pendanaan Dengan Pohon Dana</h1>
			<span>Saya menyetujui Syarat dan Ketentuan Pohon Dana</span>

			<div class="col-xs-8 col-xs-offset-2 center topmargin-sm
			"><a class="btn-primary-mayapada" href="{{ ('register_lender') }}">Lakukan Pendanaan Sekarang</a></div>
		</div>

	</section><!-- #page-title end -->

<!-- Content
	============================================= -->
	<section id="content">
		<div class="content-wrap nopadding background-grey">
			<div class="container clearfix">
				<div class="col-xs-12 col-sm-6 col-md-4 col-md-offset-1 online-originations-text">
					<div class="heading-block topmargin">
						<h3 class="border-heading-80">Mengapa pemodal harus memilih Pohon Dana?</h3>
					</div>

					<ol>
						<li><p>Return yang lebih besar dari deposito</p></li>
						<li><p>Diversifikasi portfolio keuangan anda diluar deposito, saham dan obligasi</p></li>
						<li><p>Risiko yang terkendali melalui proses seleksi peminjam yang ketat oleh Pohon Dana</p></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-md-offset-1 topmargin-sm" style="max-width: 750px; min-height: 350px;">
					<img src="images/invest/invest-04.png">
				</div>
			</div>
		</div>
		
		<div class="content-wrap nopadding dark" style="background: url(/images/invest/invest-02.png); background-size: cover">
			<div class="container clearfix">
				<div class="col-xs-12 col-sm-6 col-md-4 col-md-offset-1">
					<div class="heading-block topmargin-sm">
						<h3 class="border-heading-90">Bagaimana Caranya?</h3>
					</div>
					<!-- <p class="w90"> Lihat daftar pinjaman yang tersedia menurut nilai peringkat kredit masing-masing (credit score) <br><br>
						Pilih pinjaman yang ingin anda danai berdasarkan kriteria anda <br><br>
						Cicilan pokok dan bunga anda akan disetorkan langsung ke rekening anda <br></br>
					</p> -->
				</div>
			</div>
		</div>

		<div class="content-wrap nopadding dark" style="background: url(/images/invest/invest-03.png); background-size: cover">
			<div class="container clearfix">
				<div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-1 online-originations-text">
					<div class="heading-block topmargin">
						<h3 class="border-heading-70">Apa yang perlu anda ketahui?</h3>
					</div>
					<p class="w70">P2P yang telah terbukti menghasilkan return yang menarik</p>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-7 topmargin-lg invest-img" style="max-width: 750px; min-height: 350px;">
					<div class="col-md-3 col-sm-12">
						<img src="images/invest/invest-07.png">	
						<p class="credit-percentage">12-15%</p>
						<p class="credit-duration">per tahun</p>
						<p class="credit-estimation">(estimasi keuntungan)</p>
					</div>
					<div class="col-md-3 col-sm-12">
						<img src="images/invest/invest-08.png">	
						<p class="credit-percentage">15-18%</p>
						<p class="credit-duration">per tahun</p>
						<p class="credit-estimation">(estimasi keuntungan)</p>
					</div>
					<div class="col-md-3 col-sm-12">
						<img src="images/invest/invest-09.png">	
						<p class="credit-percentage">18-22%</p>
						<p class="credit-duration">per tahun</p>
						<p class="credit-estimation">(estimasi keuntungan)</p>
					</div>
					<div class="col-md-3 col-sm-12">
						<img src="images/invest/invest-10.png">	
						<p class="credit-percentage">18-24%</p>
						<p class="credit-duration">per tahun</p>
						<p class="credit-estimation">(estimasi keuntungan)</p>
					</div>
				</div>
			</div>
		</div>

	</section><!-- #content end -->
	@endsection
