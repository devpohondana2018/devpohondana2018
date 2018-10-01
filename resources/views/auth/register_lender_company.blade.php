@extends('layouts.register-lender-company')

@section('title') Pendaftaran Akun Pendana @endsection

@section('style')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/pohondana/register_lender.css') }}">
@endsection

@section('content')
<section id="content" class="content-form-register" style="background: url('{{ asset('/images/borrow/borrow-background.jpg') }}') no-repeat;">
	<div class="container">
		<div class="row justify-content-center">
			<div id="form-register-pd" class="col-md-12">
				<div class="card card-default" style="background-color: #ffffffde">
					<div class="card-body">
						<div class="text-center">
							@include('includes.notification')
						</div>
						@if($isPayment == 1)
						<div class="payment">
							<div class="payment-description">
								Mohon lakukan pembayaran ke Nomor Rekening dibawah ini
							</div>
							<div class="payment-image">
								<img src="https://upload.wikimedia.org/wikipedia/id/thumb/e/e0/BCA_logo.svg/1280px-BCA_logo.svg.png" height="30px">
							</div>
							<div class="va-number">No. Rek. 5455.650.272</div>
							<div class="bank-owner">An: PT Pohon Dana Indonesia</div>
							<a class="btn btn-sm btn-success" href='{{ url("login") }}'>Finish</a>
						</div>
						@else
						<div class="text-center">
							<h3 class="nobottommargin">Pendaftaran Akun Pendana</h3>
							<p>Sudah punya akun sebelumnya? <a href="{{ url('login') }}" title="Login">Masuk disini</a></p>
							<hr>
						</div>
						<form method="POST" id="form-register" action="{{ route('register') }}" enctype="multipart/form-data">

							<div>
								<h3>Step 1</h3>
								<section>

									{{ csrf_field() }}
									<input type="hidden" name="user_type" value="lender_company">
									<div class="form-group row">
										<label for="company_id" class="col-md-4 col-form-label label-form text-md-right">Nama Perusahaan *</label>
										<div class="col-md-6">
											<input id="company_id" type="text" class="form-control{{ $errors->has('company_id') ? ' is-invalid' : '' }}" name="company_id" value="{{ old('company_id') }}">
											<small class="form-text text-muted">Mohon mengisi nama perusahaan dengan lengkap</small>
											@if ($errors->has('company_id'))
											<label id="company_id-error" class="error help-block" for="company_id">
												{{ $errors->first('company_id') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="name" class="col-md-4 col-form-label label-form text-md-right">Nama Penanggung Jawab *</label>
										<div class="col-md-6">
											<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}">
											<small class="form-text text-muted">Mohon mengisi nama lengkap sesuai KTP</small>
											@if ($errors->has('name'))
											<label id="name-error" class="error help-block" for="name">
												{{ $errors->first('name') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="employment_type" class="col-md-4 col-form-label label-form text-md-right">Jenis Pekerjaan *</label>
										<div class="col-md-6">
											<select id="employment_type" name="employment_type" class="form-control{{ $errors->has('employment_type') ? ' is-invalid' : '' }}">
												<option value="">-- Silahkan Pilih Jenis Pekerjaan --</option>
												<option value="pns">PNS</option>
												<option value="bumn">BUMN</option>
												<option value="swasta">Swasta</option>
												<option value="wiraswasta">Wiraswasta</option>
												<option value="lainnya">Lain-lain</option>
											</select>

											@if ($errors->has('employment_type'))
											<label id="employment_type-error" class="error help-block" for="employment_type">
												{{ $errors->first('employment_type') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="mobile_phone" class="col-md-4 col-form-label label-form text-md-right">No. HP Penanggung Jawab *</label>
										<div class="col-md-6">
											<input id="mobile_phone" type="text" class="form-control{{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}" name="mobile_phone" value="{{ old('mobile_phone') }}" required autofocus placeholder="08xxxxxx">
											<small class="form-text text-muted">Contoh format penulisan: 08xxxxxx</small><br>
											@if ($errors->has('mobile_phone'))
											<label id="mobile_phone-error" class="error help-block" for="mobile_phone">
												{{ $errors->first('mobile_phone') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="id_no" class="col-md-4 col-form-label label-form text-md-right">No. KTP Penanggung Jawab *</label>
										<div class="col-md-6">
											<input id="id_no" type="text" class="form-control{{ $errors->has('id_no') ? ' is-invalid' : '' }}" name="id_no" value="{{ old('id_no') }}" required autofocus>
											<small class="form-text text-muted">Isian minimal 16 karakter tanpa menggunakan simbol</small><br>
											@if ($errors->has('id_no'))
											<label id="id_no-error" class="error help-block" for="id_no">
												{{ $errors->first('id_no') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="id_doc" class="col-md-4 col-form-label label-form text-md-right">Upload KTP Penanggung Jawab *</label>
										<div class="col-md-6">
											<input id="id_doc" type="file" class="form-control{{ $errors->has('id_doc') ? ' is-invalid' : '' }}" name="id_doc" value="{{ old('id_doc') }}" required autofocus accept=".jpeg, .png, .jpg">
											<small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small><br>
											@if ($errors->has('id_doc'))
											<label id="id_doc-error" class="error help-block" for="id_doc">
												{{ $errors->first('id_doc') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row hidden">
										<label for="id_doc_base_64" class="col-md-4 col-form-label label-form text-md-right">Upload KTP Penanggung Jawab *</label>
										<div class="col-md-6">
											<textarea id="id_doc_base_64" type="file" class="form-control{{ $errors->has('id_doc_base_64') ? ' is-invalid' : '' }}" name="id_doc_base_64" value="{{ old('id_doc_base_64') }}" required autofocus accept=".jpeg, .png, .jpg">
											</textarea><br>
											@if ($errors->has('id_doc_base_64'))
											<label id="id_doc_base_64-error" class="error help-block" for="id_doc_base_64">
												{{ $errors->first('id_doc_base_64') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="email" class="col-md-4 col-form-label label-form text-md-right">Alamat Email *</label>
										<div class="col-md-6">
											<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required >
											<small class="form-text text-muted">Gunakan alamat email yang valid untuk verifikasi</small><br>
											<span id="emailUsed" class="invalid-feedback"></span>
											@if ($errors->has('email'))
											<label id="email-error" class="error help-block" for="email">
												{{ $errors->first('email') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="password" class="col-md-4 col-form-label label-form text-md-right">Password *</label>
										<div class="col-md-6">
											<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
											<small class="form-text text-muted">Minimal 6 karakter</small><br>
											<span id='message_password'></span>
											@if ($errors->has('password'))
											<label id="password-error" class="error help-block" for="password">
												{{ $errors->first('password') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="password-confirm" class="col-md-4 col-form-label label-form text-md-right">Konfirmasi Password *</label>
										<div class="col-md-6">
											<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
											<small class="form-text text-muted">Isi dengan password yang sama</small><br>
											@if ($errors->has('password_confirmation'))
											<label id="password_confirmation-error" class="error help-block" for="password_confirmation">
												{{ $errors->first('password_confirmation') }}
											</label>
											@endif
										</div>
									</div>

								</section>
								<h3>Step 2</h3>
								<section>

									<div class="form-group row">
										<label for="home_address" class="col-md-4 col-form-label label-form text-md-right">Alamat Perusahaan *</label>
										<div class="col-md-6">
											<input id="home_address" type="text" class="form-control{{ $errors->has('home_address') ? ' is-invalid' : '' }}" name="home_address" value="{{ old('home_address') }}" required autofocus>

											@if ($errors->has('home_address'))
											<label id="home_address-error" class="error help-block" for="home_address">
												{{ $errors->first('home_address') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="home_state" class="col-md-4 col-form-label label-form text-md-right">Provinsi Perusahaan *</label>
										<div class="col-md-6">
											<select id="home_state" type="text" class="form-control{{ $errors->has('home_state') ? ' is-invalid' : '' }}" name="home_state">
												<option value="" disable="true" selected="true">-- Pilih Provinsi --</option>
												@foreach ($provinces as $key => $value)
												<option value="{{$value->name}}">{{ $value->name }}</option>
												@endforeach
											</select>

											@if ($errors->has('home_state'))
											<label id="home_state-error" class="error help-block" for="home_state">
												{{ $errors->first('home_state') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="home_city" class="col-md-4 col-form-label label-form text-md-right">Kota/Kabupaten Perusahaan *</label>
										<div class="col-md-6">
											<select id="home_city" type="text" class="form-control{{ $errors->has('home_city') ? ' is-invalid' : '' }}" name="home_city">
												<option value="" disable="true" selected="true">-- Kota/Kabupaten --</option>
											</select>

											@if ($errors->has('home_city'))
											<label id="home_city-error" class="error help-block" for="home_city">
												{{ $errors->first('home_city') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="home_postal_code" class="col-md-4 col-form-label label-form text-md-right">Kode Pos Perusahaan *</label>
										<div class="col-md-6">
											<input id="home_postal_code" type="text" class="form-control{{ $errors->has('home_postal_code') ? ' is-invalid' : '' }}" name="home_postal_code" value="{{ old('home_postal_code') }}" required autofocus>

											@if ($errors->has('home_postal_code'))
											<label id="home_postal_code-error" class="error help-block" for="home_postal_code">
												{{ $errors->first('home_postal_code') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="home_phone" class="col-md-4 col-form-label label-form text-md-right">No. Telepon Perusahaan *</label>
										<div class="col-md-6">
											<input id="home_phone" type="text" class="form-control{{ $errors->has('home_phone') ? ' is-invalid' : '' }}" name="home_phone" value="{{ old('home_phone') }}" required autofocus>
											<small class="form-text text-muted">Contoh format penulisan: 021xxxxxx</small><br>
											@if ($errors->has('home_phone'))
											<label id="home_phone-error" class="error help-block" for="home_phone">
												{{ $errors->first('home_phone') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="website_url" class="col-md-4 col-form-label label-form text-md-right">Website Perusahaan *</label>
										<div class="col-md-6">
											<input id="website_url" type="text" class="form-control{{ $errors->has('website_url') ? ' is-invalid' : '' }}" name="website_url" value="{{ old('website_url') }}" required autofocus>
											<small class="form-text text-muted">Contoh format penulisan: https:// atau http://</small><br>
											@if ($errors->has('website_url'))
											<label id="website_url-error" class="error help-block" for="website_url">
												{{ $errors->first('website_url') }}
											</label>
											@endif
										</div>
									</div>
								</section> 

								<h3>Step 3</h3>
								<section>

									<div class="form-group row">
										<label for="company_type" class="col-md-4 col-form-label label-form text-md-right">Jenis Perusahaan *</label>
										<div class="col-md-6">
											<select id="company_type" name="company_type" class="form-control{{ $errors->has('company_type') ? ' is-invalid' : '' }}">
												<option value="">-- Pilih Jenis Perusahaan --</option>
												<option value="PT">PT</option>
												<option value="Koperasi">Koperasi</option>
												<option value="Pemerintah Pusat">Pemerintah Pusat</option>
												<option value="Pemerintah Daerah">Pemerintah Daerah</option>
												<option value="lainnya">Lain - lain</option>
											</select>
											@if ($errors->has('company_type'))
											<label id="company_type-error" class="error help-block" for="company_type">
												{{ $errors->first('company_type') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="company_industry" class="col-md-4 col-form-label label-form text-md-right">Industri *</label>
										<div class="col-md-6">
											<select id="company_industry" name="company_industry" class="form-control{{ $errors->has('company_industry') ? ' is-invalid' : '' }}">
												<option value="">-- Pilih Industri --</option>
												<option value="Industri Pengolahan Pangan">Industri Pengolahan Pangan</option>
												<option value="Industri Tekstil">Industri Tekstil</option>
												<option value="Industri Barang Kulit">Industri Barang Kulit</option>
												<option value="Industri Pengolahan Kayu">Industri Pengolahan Kayu</option>
												<option value="Industri Pengolahan Kertas">Industri Pengolahan Kertas</option>
												<option value="Industri Kimia Farmasi">Industri Kimia Farmasi</option>
												<option value="Industri Pengolahan Karet">Industri Pengolahan Karet</option>
												<option value="Industri Barang Galian bukan Logam">Industri Barang Galian bukan Logam</option>
												<option value="Industri Baja / Pengolahan Logam">Industri Baja / Pengolahan Logam</option>
												<option value="Industri Peralatan">Industri Peralatan</option>
												<option value="Industri Pertambangan">Industri Pertambangan</option>
												<option value="Industri Pariwisata">Industri Pariwisata</option>
												<option value="lainnya">Lain-lain</option>
											</select>
											@if ($errors->has('company_industry'))
											<label id="company_industry-error" class="error help-block" for="company_industry">
												{{ $errors->first('company_industry') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="home_ownership" class="col-md-4 col-form-label label-form text-md-right">Status Domisili Perusahaan *</label>
										<div class="col-md-6">
											<select id="home_ownership" name="home_ownership" class="form-control{{ $errors->has('home_ownership') ? ' is-invalid' : '' }}">
												<option value="">-- Pilih Status Domisili Perusahaan --</option>
												<option value="sendiri">Milik Sendiri</option>
												<option value="sewa">Sewa</option>
												<option value="lainnya">Lain-lain</option>
											</select>
											@if ($errors->has('home_ownership'))
											<label id="home_ownership-error" class="error help-block" for="home_ownership">
												{{ $errors->first('home_ownership') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="npwp_no" class="col-md-4 col-form-label label-form text-md-right">No. NPWP Perusahaan *</label>
										<div class="col-md-6">
											<input id="npwp_no" type="number" class="form-control{{ $errors->has('npwp_no') ? ' is-invalid' : '' }}" name="npwp_no" value="{{ old('npwp_no') }}" required>
											<small class="form-text text-muted">Isian minimal 15 karakter tanpa menggunakan simbol</small><br>
											@if ($errors->has('npwp_no'))
											<label id="npwp_no-error" class="error help-block" for="npwp_no">
												{{ $errors->first('npwp_no') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="npwp_doc" class="col-md-4 col-form-label label-form text-md-right">Upload NPWP Perusahaan *</label>
										<div class="col-md-6">
											<input id="npwp_doc" type="file" class="form-control{{ $errors->has('npwp_doc') ? ' is-invalid' : '' }}" name="npwp_doc" value="{{ old('npwp_doc') }}" autofocus accept=".jpeg, .png, .jpg">
											<small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small><br>
											@if ($errors->has('npwp_doc'))
											<label id="npwp_doc-error" class="error help-block" for="npwp_doc">
												{{ $errors->first('npwp_doc') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row hidden">
										<label for="npwp_doc_base_64" class="col-md-4 col-form-label label-form text-md-right">Upload NPWP Perusahaan *</label>
										<div class="col-md-6">
											<textarea id="npwp_doc_base_64" type="file" class="form-control{{ $errors->has('npwp_doc_base_64') ? ' is-invalid' : '' }}" name="npwp_doc_base_64" value="{{ old('npwp_doc_base_64') }}" required autofocus accept=".jpeg, .png, .jpg">
											</textarea><br>
											@if ($errors->has('npwp_doc_base_64'))
											<label id="npwp_doc_base_64-error" class="error help-block" for="npwp_doc_base_64">
												{{ $errors->first('npwp_doc_base_64') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="akta_doc" class="col-md-4 col-form-label label-form text-md-right">Upload Akta Perusahaan *</label>
										<div class="col-md-6">
											<input id="akta_doc" type="file" class="form-control{{ $errors->has('akta_doc') ? ' is-invalid' : '' }}" name="akta_doc" value="{{ old('akta_doc') }}" autofocus accept=".jpeg, .png, .jpg">
											<small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small><br>
											@if ($errors->has('akta_doc'))
											<label id="akta_doc-error" class="error help-block" for="akta_doc">
												{{ $errors->first('akta_doc') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row hidden">
										<label for="akta_doc_base_64" class="col-md-4 col-form-label label-form text-md-right">Upload Akta Perusahaan *</label>
										<div class="col-md-6">
											<textarea id="akta_doc_base_64" type="file" class="form-control{{ $errors->has('akta_doc_base_64') ? ' is-invalid' : '' }}" name="akta_doc_base_64" value="{{ old('akta_doc_base_64') }}" required autofocus accept=".jpeg, .png, .jpg">
											</textarea><br>
											@if ($errors->has('akta_doc_base_64'))
											<label id="akta_doc_base_64-error" class="error help-block" for="akta_doc_base_64">
												{{ $errors->first('akta_doc_base_64') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="home_doc" class="col-md-4 col-form-label label-form text-md-right">Upload SIUP *</label>
										<div class="col-md-6">
											<input id="home_doc" type="file" class="form-control{{ $errors->has('home_doc') ? ' is-invalid' : '' }}" name="home_doc" value="{{ old('home_doc') }}" autofocus accept=".jpeg, .png, .jpg">
											<small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small><br>
											@if ($errors->has('home_doc'))
											<label id="akta_doc-error" class="error help-block" for="home_doc">
												{{ $errors->first('home_doc') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row hidden">
										<label for="home_doc_base_64" class="col-md-4 col-form-label label-form text-md-right">Upload SIUP *</label>
										<div class="col-md-6">
											<textarea id="home_doc_base_64" type="file" class="form-control{{ $errors->has('home_doc_base_64') ? ' is-invalid' : '' }}" name="home_doc_base_64" value="{{ old('home_doc_base_64') }}" required autofocus accept=".jpeg, .png, .jpg">
											</textarea><br>
											@if ($errors->has('home_doc_base_64'))
											<label id="home_doc_base_64-error" class="error help-block" for="home_doc_base_64">
												{{ $errors->first('home_doc_base_64') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="tdp_doc" class="col-md-4 col-form-label label-form text-md-right">Upload TDP *</label>
										<div class="col-md-6">
											<input id="tdp_doc" type="file" class="form-control{{ $errors->has('tdp_doc') ? ' is-invalid' : '' }}" name="tdp_doc" value="{{ old('tdp_doc') }}" autofocus accept=".jpeg, .png, .jpg">
											<small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small><br>
											@if ($errors->has('tdp_doc'))
											<label id="tdp_doc-error" class="error help-block" for="tdp_doc">
												{{ $errors->first('tdp_doc') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row hidden">
										<label for="tdp_doc_base_64" class="col-md-4 col-form-label label-form text-md-right">Upload TDP *</label>
										<div class="col-md-6">
											<textarea id="tdp_doc_base_64" type="file" class="form-control{{ $errors->has('tdp_doc_base_64') ? ' is-invalid' : '' }}" name="tdp_doc_base_64" value="{{ old('tdp_doc_base_64') }}" required autofocus accept=".jpeg, .png, .jpg">
											</textarea><br>
											@if ($errors->has('tdp_doc_base_64'))
											<label id="tdp_doc_base_64-error" class="error help-block" for="tdp_doc_base_64">
												{{ $errors->first('tdp_doc_base_64') }}
											</label>
											@endif
										</div>
									</div>

								</section>
								<h3>Step 4</h3>
								<section>

									<div class="loan-select-message"></div>
									<div class="row" style="padding-left: 30px; padding-right: 30px">

										<div class="timeline-centered">

											<!-- <article class="timeline-entry timeline-entry-investment-amount">
												<div class="timeline-entry-inner">
													<div class="timeline-icon bg-success">
														<i class="entypo-suitcase"></i>
													</div>
													<div class="timeline-content">
														<h2 class="timeline-header">Kriteria Pendanaan</h2>
														<div class="timeline-body">
															<p>
																Berapa jumlah maksimum pinjaman yang ingin Anda danai
															</p>
															<div class="form-group">
																<input id="amount_invesment" type="text" class="form-control{{ $errors->has('amount_invesment') ? ' is-invalid' : '' }}" name="amount_invesment" value="{{ old('amount_invesment') }}" required autofocus>

																@if ($errors->has('amount_invesment'))
																<label id="amount_invesment-error" class="error help-block" for="amount_invesment">
																	{{ $errors->first('amount_invesment') }}
																</label>
																@endif
															</div>
															<div class="input-group">
																<label>Maksimal Tenor Pinjaman (Bulan)</label>
																<select name="grade_investment" class="form-control">
																	@foreach($tenors as $id => $month)
																	<option value="{{$id}}">{{$month}}</option>
																	@endforeach
																</select>
																@if ($errors->has('grade_investment'))
																<label id="grade_investment-error" class="error help-block" for="grade_investment">
																	{{ $errors->first('grade_investment') }}
																</label>
																@endif
															</div>
														</div>
														<div class="timeline-footer">
															<div class="btn btn-sm btn-success btn-success-pohondana btn-next-investment-amount">SELANJUTNYA</div>
														</div>
													</div>
												</div>

											</article> -->

											<!-- <article class="timeline-entry timeline-entry-investment-criteria" style="display: none">
												<div class="timeline-entry-inner">
													<div class="timeline-icon bg-success">
														<i class="entypo-suitcase"></i>
													</div>
													<div class="timeline-content">
														<h2 class="timeline-header">Kriteria Pendanaan</h2>
														<div class="timeline-body">

															<div class="input-group">
															</div>
														</div>
														<div class="timeline-footer">
															<div class="btn btn-sm btn-success btn-success-pohondana btn-next-investment-criteria">SELANJUTNYA</div>
														</div>
													</div>
												</div>

											</article>
 -->
											<article class="timeline-entry timeline-entry-investment-select-loan">
												<div class="timeline-entry-inner">
													<div class="timeline-icon bg-success">
														<i class="entypo-suitcase"></i>
													</div>
													<div class="timeline-content table-loan-content">
														<h2 class="timeline-header">Pilih Pendanaan</h2>
														<div class="timeline-body">
															<p>
																Pilih pendanaan yang sesuai dengan kriteria Anda
															</p>
															<div class="row">
																<div class="col-md-12 table-responsive">
																	<table class="table data-tables-pohondana responsive display nowrap" width="100%">

																		<thead>
																			<tr>
																				<th>ID</th>
																				<th>Total Pinjaman</th>
																				<th>Sisa Pendanaan</th>
																				<th>Tenor</th>
																				<th>Grade</th>
																				<th>Bunga</th>
																				<th>Tanggal Batas Pendanaan</th>
																				<th>Status Pendanaan</th>
																				<th>Detail</th>
																			</tr>
																		</thead>
																		<tbody></tbody>

																	</table>
																</div>
															</div>
														</div>
														<div class="timeline-footer">
															<!-- <p class="after-pinjaman">Setelah pilih pinjaman silahkan klik button Berikutnya</p> -->
														</div>
													</div>
												</div>


												<div class="form-group row" style="display: none">
													<label for="loan_id" class="col-md-4 col-form-label label-form text-md-right">Loan Id *</label>
													<div class="col-md-6">
														<input id="loan_id" type="text" class="form-control{{ $errors->has('loan_id') ? ' is-invalid' : '' }}" name="loan_id" value="{{ old('loan_id') }}" required autofocus readonly hidden>

														@if ($errors->has('loan_id'))
														<label id="loan_id-error" class="error help-block" for="loan_id">
															{{ $errors->first('loan_id') }}
														</label>
														@endif
													</div>
												</div>

											</article>

											<article class="timeline-entry timeline-entry-investment-amount-value" style="display: none">
												<div class="timeline-entry-inner">
													<div class="timeline-icon bg-success">
														<i class="entypo-suitcase"></i>
													</div>
													<div class="timeline-content">
														<h2 class="timeline-header">Jumlah Pendanaan</h2>
														<div class="timeline-body">
															<p>
																Masukan jumlah pendanaan anda
															</p>
															<div class="form-group">
																<input id="amount_invesment_value" type="text" class="form-control{{ $errors->has('amount_invesment_value') ? ' is-invalid' : '' }}"  minlength="7"  name="amount_invesment_value" value="{{ old('amount_invesment_value') }}" required autofocus>
																<small class="form-text text-muted">Minimum pendanaan Rp. 1.000.000</small><br>

																@if ($errors->has('amount_invesment_value'))
																<label id="amount_invesment_value-error" class="error help-block" for="amount_invesment_value">
																	{{ $errors->first('amount_invesment_value') }}
																</label>
																@endif
															</div>
														</div>
														<div class="timeline-footer">
															<!-- <div class="btn btn-sm btn-success btn-success-pohondana btn-next-investment-criteria">SELANJUTNYA</div> -->
														</div>
													</div>
												</div>

											</article>

										</div>
									</div>



								</section>
								<h3>Step 5</h3>
								<section>

									<div class="form-group row">
										<label for="payment_method" class="col-md-4 col-form-label label-form text-md-right">Metode Pembayaran *</label>
										<div class="col-md-6">
											<select id="payment_method" type="text" class="form-control{{ $errors->has('payment_method') ? ' is-invalid' : '' }}" name="payment_method">
												<option value="" disable="true" selected="true">-- Pilih Metode Pembayaran --</option>
												<option value="bank_transfer">Bank Transfer</option>
											</select>
											@if ($errors->has('payment_method'))
											<label id="payment_method-error" class="error help-block" for="payment_method">
												{{ $errors->first('payment_method') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="bank_name" class="col-md-4 col-form-label label-form text-md-right">Bank *</label>
										<div class="col-md-6">
											<select id="bank_name" type="text" class="form-control{{ $errors->has('bank_name') ? ' is-invalid' : '' }}" name="bank_name">
												<option value="" disable="true" selected="true">-- Pilih Bank --</option>
												@foreach ($banks as $bank)
												<option value="{{$bank->id}}">{{ $bank->name }}</option>
												@endforeach
											</select>
											@if ($errors->has('bank_name'))
											<label id="bank_name-error" class="error help-block" for="bank_name">
												{{ $errors->first('bank_name') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="account_name" class="col-md-4 col-form-label label-form text-md-right">Nama Pemilik Rekening *</label>
										<div class="col-md-6">
											<input id="account_name" type="text" class="form-control{{ $errors->has('account_name') ? ' is-invalid' : '' }}" name="account_name" value="{{ old('account_name') }}" required autofocus readonly>

											@if ($errors->has('account_name'))
											<label id="account_name-error" class="error help-block" for="account_name">
												{{ $errors->first('account_name') }}
											</label>
											@endif
										</div>
									</div>

									<div class="form-group row">
										<label for="account_number" class="col-md-4 col-form-label label-form text-md-right">No. Rekening *</label>
										<div class="col-md-6">
											<input id="account_number" type="text" class="form-control{{ $errors->has('account_number') ? ' is-invalid' : '' }}" name="account_number" value="{{ old('account_number') }}" required autofocus>

											@if ($errors->has('account_number'))
											<label id="account_number-error" class="error help-block" for="account_number">
												{{ $errors->first('account_number') }}
											</label>
											@endif
										</div>
									</div>
								</section>
								<h3>Step 6</h3>
								<section>
									<div class="form-group row">
										<div class="col-md-10 col-md-offset-1">
											<div style="height:500px;width:100%;border:1px solid #ccc; overflow:auto;" disabled="">
												<div class="col-md-12 nobottommargin syarat-content">
													<h3 class="center">Syarat & Ketentuan</h3>
													<p style="text-align: justify;">Halaman ini berisi tentang syarat dan ketentuan dari penggunaan situs dan aplikasi layanan kami oleh anda <strong>(“Syarat & Ketentuan”)</strong>. Syarat dan Ketentuan ini wajib anda baca secara hati-hati sebelum menggunakan situs dan aplikasi layanan kami. Apabila anda tidak menyetujui Syarat dan Ketentuan ini, harap untuk tidak menggunakan Situs Web dan Aplikasi Layanan kami</p>
													<ol>
														<li><span class="bold-li">DEFINISI</span>
															<ol>
																<li>Untuk tujuan Syarat dan Ketentuan ini, istilah dimanapun dalam Syarat & Ketentuan ini akan memiliki arti sebagai berikut :
																	<ol>
																		<li>Peminjam adalah orang perseorangan warga negara Indonesia, atau badan hukum yang terdaftar di Indonesia dan tercatat sebagai perusahaan rekanan / karyawan perusahaan rekanan yang mengajukan pinjaman pada situs atau aplikasi POHON DANA</li>
																		<li>Pemberi Pinjaman adalah orang perseorangan, badan hukum, badan
																		usaha atau lembaga International sesuai denga ketentuan Peraturan Otoritas Jasa Keuangan No. 77/POJK. 01/2016 yang menempatkan dana pada Rekening POHON DANA untuk disalurkan kepada Peminjam POHON DANA;</li>
																		<li>Pinjaman adalah sejumlah dana yang ditempatkan oleh pemberi
																		Pinjaman pada Rekening POHON DANA untuk disalurkan sebagai Pinjaman Kepada Peminjam melalui aplikasi atau situs POHON DANA;</li>
																		<li>Jasa adalah kegiatan operasional POHON DANA pada situs maupun 
																			aplikasi POHON DANA yang akan memfasilitasi proses pinjam
																		meminjam berbasis teknologi informasi;</li>
																		<li>Hari kerja adalah senin-jumat(selain Sabtu, Minggu atau hari
																		libur nasional);</li>
																		<li>Rekening POHON DANA adalah rekening atas nama PT. Pohon Dana 
																		Indonesia.</li>
																		<li>Hukum adalah setiap peraturan, kebijakan, undang-undang,
																			ataupun norma yang berlaku dan memiliki kekuatan hukum untuk 
																		dijalankan dan ditaati bersama;</li>
																		<li>Data Pribadi adalah informasi perseorangan tertentu yang disimpan,
																			dirawat, dijaga kebenaran serta dilindungi kerahasiaannya yang dapat digunakan untuk mengidentifikasi secara langsung atau tidak
																		langsung setiap individu.</li>
																		<li>Tenaga Profesional POHON DANA adalah setiap pejabat dan/atau
																			pegawai dari POHON DANA yang telah resmi terdaftar sebagai pejabat
																		dan/ataupun karyawan POHON DANA;</li>
																		<li>Syarat dan Ketentuan ini dapat direvisi, ditambah, atau
																			diamandemen dari waktu ke waktu, daan perubahan atas Syarat dan
																			Ketentuan ini akan ditempatkan pada situs dan aplikasi POHON DANA
																			dengan mencantumkan tanggal perubahan terakhir dari Syarat dan 
																		Ketentuan POHON DANA ini;</li>
																		<li>Dalam Syarat dan Ketentuan ini, semua rujukan kepada
																		perseorangan atau badan hukum, adalah juga sebagai merujuk kepada lembaga yang sah, penerima hak pengalihan, penerus, ataupun kuasa/wakil dari perseorangan atau badan hukum selmama dianggap wajar;</li>
																		<li>Setiap rujukan Hukum, peraturan, undang-undang adalah merujuk
																		pada amandemen dan perubahan peraturan terkait, atau peraturan yang berada dibawah Hukum, peraturan, atau penetapan tersebut;</li>
																		<li>Tidak ada Hukum (atau interprestasinya) yang menyatakan ambiguitas 
																		dalam suatu dokumen terkait Syarat dan Ketentuan ini;</li>
																		<li>Tingkat suku bunga yang disajikan adalah tingkat suku bunga per
																		bulan.</li>
																	</ol>
																</li>
															</ol>
														</li>
														<li><span class="bold-li">PERSETUJUAN</span>

															<ol>
																<li>Syarat dan Ketentuan ini merupakan syarat-syarat dan ketentuan-ketentuan dimana PT. Pohon Dana Indonesia akan memberikan jasa dengan pengoperasian situs dan aplikasi POHON DANA yang akan memfasilitasi proses pemberian Pinjaman;</li>
																<li>Anda menyetujui bahwa POHON DANA mempunyai hak mutlak untuk menentukan apakah Anda dapat masuk dalam daftar calon Peminjam dan Pemberi Pinjaman. Dalam hal Anda tidak masuk dalam daftar calon Peminjam maupun Pemberi Pinjaman, POHON DANA tidak berhak untuk menyediakan Jasa ataupun memunculkan kewajiban tambahan apapun kepada Anda sebagai Peminjam atau Pemberi Pinjaman;</li>
																<li>Ketentuan penguna Jasa dari POHON DANA akan tunduk pada Hukum serta        Syarat dan Ketentuan ini, dan POHON DANA tidak bertanggung jawab atas tindakan yang diambil oleh pihak manapun dalam rangka mematuhi Hukum serta Syarat dan Ketentuan ini kecuali memperoleh persetujuan terlebih dahulu dari POHON DANA;</li>
																<li>Kecuali diatur lain, Jasa yang disediakan oleh POHON DANA dapat termasuk memfasilitasi Pinjaman, atau jasa lain yang POHON DANA akan perkenalkan dari waktu ke waktu;</li>
																<li>Apabila terdapat perbedaan dan pertentangan antara Syarat dan Ketentuan dan perjanjian yang mengikat oleh dan dengan POHON DANA, maka peraturan dalam perjanjian yang berlaku</li>
																<li>Anda memahami, mengakui, dan setuju bahwa POHON DANA tidak dan tidak akan pernah:
																	<ol>
																		<li>Menjalankan kegiatan perbankan, pasar modal, jasa keuangan non-bank, dan kegiatan lainnya sebagaimana diatur oleh Otoritas Jasa Keuangan (OJK) dan Bank Indonesia (BI);</li>
																		<li>Menyelenggarakan simpanan dalam bentuk apapun seperti tabungan, deposito, giro atau bentuk lainnya yang dipersamakan dengan itu;</li>
																		<li>Menyediakan jasa peringkat kredit atau bentuk kegiatan
																			lainnya yang dipersamakan dengan itu sebagaimana diatur oleh
																		Otoritas Jasa Keuangan dan Bank Indonesia</li>
																		<li>Menjalankan pendanaan modal;</li>
																		<li>Memihak salah satu pihak, baik Peminjam maupun Pemberi
																		Pinjaman dalam perjanjian Pinjaman atau;</li>
																		<li>Menyediakan jasa penyimpanan/penitipan;</li>
																	</ol>
																</li>
																<li>Anda memahami, mengakui, dan setuju bahwa peran POHON DANA hanya
																sebagai fasilitator dan bersifat administrative yang mengatur perjanjian antara Peminjam dan pemberi Pinjaman dalam Perjanjian Pinjaman terkait, sesuai dengan Peraturan Otoritas Jasa Keuangan Nomor 77/POJK.01/2016 Tentang Layanan Pinjam Meminjam Uang Berbasis Teknologi Informasi;</li>
															</ol>
														</li>
														<li><span class="bold-li">PENGAJUAN PINJAMAN</span>
															<ol>
																<li>Calon Peminjam akan mengajukan permohonan Pengajuan Pinjaman pada
																	situs atau aplikasi POHON DANA dengan cara mengisi aplikasi yang
																tersedia pada situs maupun aplikasi POHON DANA</li>
																<li>Setelah menerima aplikasi Pengajuan Pinjaman, penilai kredit dari POHON DANA akan mengadakan uji kelayakan calon Peminjam. Selama proses berlangsung, POHON DANA akan menghubungi lembaga, perusahaan, atau individu terkait untuk mencari informasi, melakukan verifikasi, dan konfirmasi informasi terkait Peminjam, termasuk (namun tidak terbatas pada) catatan historis hukum dan kredit. Peminjam wajib memberikan izin dan kuasa kepada POHON DANA untuk melakukan hal-hal tersebut;</li>
																<li>Setelah hasil yang memadai dari uji kelayakan oleh POHON DANA, POHON DANA akan menginformasikan kepada Peminjam apakah Pengajuan Peminjam tersebut disetujui atau tidak, dan menginformasikan mengenai Syarat dan Ketentuan yang berlaku pada situs dan aplikasi POHON DANA:</li>
																<li>Setelah Peminjam setuju pada Syarat dan Ketentuan Pinjaman yang ditawarkan, Peminjam harus melengkapi aplikasi Pengajuan Pinjaman, yaitu detail lengkap nomor rekening tujuan pencairan, di nama pemilik rekening tujuan adalah harus sama dengan nama Peminjam;</li>
																<li>Setelah Pengajuan Pinjaman disetujui oleh kedua belah pihak, maka Peminjam setuju untuk mengikatkan diri dalam perjanjian dan dokumen lain yang terkait fasilitas Pinjaman (jika ada) dalam jangka waktu yang ditentukan oleh POHON DANA.</li>
															</ol>
														</li>

														<li><span class="bold-li">PEMBAYARAN OLEH PEMINJAM</span>
															<ol>
																<li>Pembayaran kembali Pinjaman, dengan bunga, harus dilakukan secara berkala. Ketika Peminjam mengalami gagal bayar, POHON DANA akan memberikan peringatan tidak dilakukan pembayaran kepada Peminjam dalam jangka waktu 7 (tujuh) Hari Kerja. Apabila Peminjam gagal membayar kembali angsuran ketiga secara berturut-turut, Peminjam dinyatakan wanprestasi. Apabila terjadi wanprestasi, POHON DANA akan melakukan restrukturisasi skema pembayaran kembali Pinjaman dan dapat melakukan tindakan hukum dalam proses penyelesaian;</li>
																<li>Peminjam memahami dan setuju untuk mematuhi untuk membayar setiap pangaturan fasiltas Pinjaman, biaya lain-lain kepada POHON DANA dan/atau pihak terkait lainnya dalam melakukan pengaturan dan administratif fasilitas Pinjaman dan Perjanjian terkait. Sebagai tambahan, Peminjam sepakat untuk membayar semua denda, biaya keterlambatan pembayaran, biaya penagihan, dan biaya lain kepada POHON DANA sebagai hasil dari kegiatan yang dilakukan oleh POHON DANA atas bagian dari Jasa;</li>
																<li>Untuk Peminjam yang merupakan karyawan dari perusahaan rekanan yang menjalin kerjasama dengan pihak POHON DANA, pembayaran cicilan /angsuran pinjaman dapat dilakukan dengan pemotongan gaji oleh pejabat HRD atau pihak yang ditunjuk oleh perusahaan rekanan.</li>
															</ol>
														</li>

														<li><span class="bold-li">REPRESENTASI DAN JAMINAN</span>
															<ol>
																<li>Peminjam menyatakan dan menjamin kepada POHON DANA bahwa Peminjam
																	<ol>
																		<li>Adalah Warga Negara Indonesia yang sah</li>
																		<li>Perusahaan yang didirikan berdasarkan Hukum Indonesia</li>
																		<li>Mampu membayar utang dan tidak ada alasan yang
																			menyatakan bahwa Peminjam tidak mampu membayar utangnya 
																		ketika jatuh tempo dan harus dibayar;</li>
																		<li>Memiliki kapasitas hukum dan telah memperoleh perizinan dan/atau persetujuan yang diperlukan berdasarkan Hukum atau perjanjian lain yang mana Peminjam terikat untuk mengikatkan diri dalam Syarat dan Ketentuan dan melaksanakan kewajiban yang tertera dalam Syarat dan Ketentuan serta perjanjian lainnya terkait. Selanjutnya, Peminjam harus sudah mengambil langkah yang diperlukan untuk mengikatkan dirinya dalam Perjanjian dan melakukan kewajiban yang terdapat dalam Syarat dan Ketentuan serta perjanjian lainnya terkait.</li>
																		<li>Kewajiban yang diasumsikan mengikat kepada Peminjam dalam Syarat dan Ketentuan serta perjanjian lainnya terkait juga dianggap legal, sah, mengikat, dan dapat ditegakkan kepada Peminjam;</li>
																		<li>Semua informasi yang diberikan Peminjam kepada POHON DANA adalah benar dan akurat secara materi dan sesuai dengan tanggal dokumen diberikan atau tanggal yang tertera pada dokumen; dan</li>
																		<li>Tidak ada tindakan hukum, gugatan, atau proses hukum, atau didepan pengadilan, sidang, badan pemerintahan, agensi atau badan resmi atau arbitrasi (baik dalam proses atau akan diajukan) yang dapat berdampak pada legalitas, keabsahan, atau penegakan Syarat dan Ketentuan ini atau perjanjian lainnya yang terkait, atau mempengaruhi kemampuan Peminjam untuk menjalankan kewajiban;</li>
																	</ol>
																</li>
															</ol>
														</li>
														<li><span class="bold-li">KUASA</span>
															<ol>
																<li>Peminjam memberikan kuasa POHON DANA untuk :
																	<ol>
																		<li>Melaksanakan pengecekan kredit kepada Peminjam</li>
																		<li>Mendapatkan dan melakukan verifikasi informasi mengenai Peminjam, sesuai dengan hak mutlak POHON DANA jika dianggap perlu;</li>
																		<li>Menggunakan semua sumber relevan, yang dapat digunakan oleh POHON DANA untuk menyediakan informasi yang dibutuhkan oleh POHON DANA terkait dengan fasilitas Pinjaman yang diberikan;</li>
																		<li>Mengungkapkan informasi dan/atau data terkait Peminjam dan rekening-rekeningnya, dan/atau kartu kredit yang dimiliki (jika ada) kepada POHON DANA, atau informasi lainnya yang dipandang penting oleh POHON DANA di :</li>
																		<li>Kantor perwakilan dan cabang dan/atau perusahaan atau perusahaan asosiasi terkait Peminjam, pada juridiksi manapun;</li>
																		<li>Pemerintah atau badan pemerintahan atau badan otoritas;</li>
																		<li>Setiap calon pengalihan hak Peminjam atau pihak yang telah atau dapat memiliki hubungan kontraktual dengan Peminjam dalam kaitannya dengan fasilitas Pinjaman;</li>
																		<li>Biro kredit, termasuk anggota biro kredit tersebut;</li>
																		<li>Setiap pihak ketiga, penyedia jasa, agen, atau rekan bisnis (termasuk, tidak terbatas pada, referensi kredit atau agen evaluasi) dimanapun situasinya mungkin terjadi; dan</li>
																		<li>Kepada pihak yang mebuka informasi yang diperbolehkan oleh Hukum untuk membuka informasi;</li>
																	</ol>
																</li>
															</ol>
														</li>
														<li><span class="bold-li">GANTI RUGI</span></li>
														<p>Peminjam setuju untuk mengganti rugi POHON DANA dan Pegawainya terhadap atas semua kerugian, pajak, biaya, biaya hukum, dan kewajiban (saat ini, di masa yang akan datang, kontigensi, atau apapun yang berbasis ganti rugi), yang diderita oleh POHON DANA sebagai hasil atau hubungan dari pelanggaran Syarat dan Ketentuan atau perjanjian lainnya terkait yang dilakukan oleh Peminjam dan/atau tindakan yang dilakukan oleh POHON DANA ketika terjadinya pelanggaran Syarat dan Ketentuan atau perjanjian lainnya yang terkait oleh Peminjam;</p>

														<li><span class="bold-li">BATASAN TANGGUNG JAWAB POHON DANA</span></li>
														<p style="margin-bottom: 10px">POHON DANA ataupun Pegawainya tidak bertanggung jawab atas dan ketika terjadi pelanggaran Perjanjian, (termasuk kelalaian atau pelanggaran kewajiban), secara kontraktual untuk :</p>

														<p style="margin-left: 25px; margin-bottom: 10px"><span style="margin-right: 10px; color: black !important">(i)</span>terjadinya kehilangan keuntungan, bisnis atau pendapatan, </p>
														<p style="margin-left: 25px; margin-bottom: 15px"><span style="margin-right: 10px; color: black !important">(ii)</span>setiap biaya atau beban, atau secara tidak langsung atau langsung, diderita atau disebabkan oleh Peminjam sebagai hasil atau dalam hubungan dengan ketentuan penyediaan Jasa;</p>

														<li><span class="bold-li">WAKTU</span></li>
														<p>Adalah masa atau periode dalam Perjanjian bagi peminjam untuk
														melaksanakan dan memenuhi kewajibannya secara tepat waktu;</p>

														<li><span class="bold-li">PENGESAMPINGAN</span></li>
														<p>Kegagalan atau penundaan dalam pelaksanaan suatu hak, kewenangan, atau keistimewaan terkait Syarat dan Ketentuan ini tidak akan dianggap sebagai pengabaian hak dan pelaksanaan satu atau sebagian dari suatu hak, kewenangan atau keistimewaan tidak akan dianggap menghalangi segala konsekuensinya atau kelanjutan pelaksaan dari suatu hak, kewajiban atau keistimewaan;</p>

														<li><span class="bold-li">AMANDEMEN</span></li>
														<p>POHON DANA dapat sewaktu-waktu, memberikan pemberitahuan kepada Peminjam atas terjadinya amandemen, perubahan, revisi, tambahan, atau perubahan lainnya untuk Syarat dan Ketentuan melalui surat, surat elektronik, atau cara lain yang dianggap sesuai oleh POHON DANA. Perubahan berlaku sejak dan dimulai dari tanggal yang ditentukan dalam pemberitahuan atau jika tanggal tersebut tidak ditulis, adalah sejak dan dimulai dari tanggal terjadinya pemberitahuan. Tanpa mengurangi atas ketentuan sebelumnya, dimulainya atau berlanjutnya Jasa setelah terjadi perubahan tersebut akan dianggap sebagai persetujuan dari Peminjam akan berlanjutnya perubahan tersebut;</p>

														<li><span class="bold-li">HUKUM YANG BERLAKU DAN YURIDIKSI</span>
															<ol>
																<li>Syarat dan Ketentuan ini akan diatur dan ditafsirkan sesuai dengan ketentuan Hukum Republik Indonesia;</li>
																<li>Dalam rangka mematuhi gugatan atau tindakan hukum yang terkait perselisihan yang timbul akibat atau dalam kaitannya dengan Syarat dan Ketentuan ini, setiap pihak akan tunduk pada juridiksi Badan Arbitrase Nasional Indonesia (BANI);</li>
															</ol>
														</li>
													</ol>
												</div>
												<div class="col-md-12 text-center">
													<div class="form-check">
														<input class="form-check-input" id="checkbox-form" type="checkbox" name="agreed" value="1" required>
														<label class="form-check-label label-form" for="checkbox-form">
															Saya menyetujui Syarat dan Ketentuan dari Pohon Dana
														</label>
														@if ($errors->has('agreed'))
														<label id="agreed-error" class="error help-block" for="agreed">
															{{ $errors->first('agreed') }}
														</label>
														@endif
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-md-offset-3 text-center transform-captcha">
											<div class="g-recaptcha" data-sitekey="6Lfe2E4UAAAAAHG3Io3mEDtsEvFbrUF3ZeW6TgBU"></div>
										</div>
									</div>                                    
								</section>
							</div>

							<hr>
							<p class="text-center">Email verifikasi akun akan dikirimkan setelah pendaftaran</p>
						</form>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<div class="modal fade" id="modal-loading">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('javascript')
    <!-- <script src="//cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
    	<script src="//cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.min.js"></script> -->
    	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.js"></script>
    	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.js"></script>
    	<script src="{{ asset('vendor/select2/select2.full.min.js') }}"></script>
    	<script src="{{ asset('js/jquery.steps.min.js') }}"></script>
    	<script src="{{ asset('js/pohondana/register_lender_company.js') }}"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.bundle.js"></script>
    	@endsection
