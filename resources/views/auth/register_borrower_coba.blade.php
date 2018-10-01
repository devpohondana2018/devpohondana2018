@extends('layouts.register-borrow-company')

@section('content')
<section id="content" class="content-form-register" style="background: url('{{ asset('/images/borrow/borrow-background.jpg') }}') no-repeat; ">
    <div class="container">
        <div class="row">
            <div id="form-register-pd" class="col-md-8 col-md-offset-2">
                <div class="card card-default" style="background-color:rgba(255, 255, 255, 0.87); border-radius: 10px;">
                    <div class="card-body">
                        <div class="text-center">
                            <h3 class="nobottommargin" id="headerForm">Pengajuan Pinjaman</h3>
                            <p>Sudah punya akun sebelumnya? <a href="{{ url('login') }}" title="Login">Masuk disini</a></p>
                            <hr>
                            @include('includes.notification')
                        </div>
                        <form id="regForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_type" value="borrower">                            
                            
                            <div class="form-group row">
                                <label for="pic_name" class="col-md-4 col-form-label label-form text-md-right">Nama Perusahaan *</label>
                                <div class="col-md-6">
                                    <input id="pic_name" type="text" class="form-control{{ $errors->has('pic_name') ? ' is-invalid' : '' }}" name="pic_name" value="{{ old('pic_name') }}"  autofocus>
                                    <small class="form-text text-muted">Mohon mengisi nama perusahaan dengan lengkap</small>
                                    @if ($errors->has('pic_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('pic_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="mobile_phone" class="col-md-4 col-form-label label-form text-md-right">No. Handphone *</label>
                                <div class="col-md-6">
                                    <input id="mobile_phone" type="text" class="form-control{{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}" name="mobile_phone" value="{{ old('mobile_phone') }}" placeholder="08xxxxxx"  autofocus>
                                    <small class="form-text text-muted">Contoh format penulisan: 08xxxxxx</small><br>
                                    <span id="message_handphone" class="invalid-feedback"></span>
                                    @if ($errors->has('mobile_phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('mobile_phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label label-form text-md-right">Alamat Email *</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"  >
                                    <small class="form-text text-muted">Gunakan alamat email yang valid untuk verifikasi</small><br>
                                    <span id="emailUsed" class="invalid-feedback"></span>
                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label label-form text-md-right">Password *</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" >
                                    <small class="form-text text-muted">Minimal 6 karakter</small><br>
                                    <span id='message_password'></span>
                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label label-form text-md-right">Konfirmasi Password *</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  name="password-confirm" onChange="checkPasswordMatch();">
                                    <small class="form-text text-muted">Isi dengan password yang sama</small><br>
                                    <span id='message_confirm_password'></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label label-form text-md-right">Nama Penanggung Jawab *</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}"  autofocus>
                                    <small class="form-text text-muted">Mohon mengisi nama penanggung jawab sesuai KTP</small>
                                    @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            
                            <div class="form-group row">
                                <label for="home_address" class="col-md-4 col-form-label label-form text-md-right">Alamat Perusahaan *</label>
                                <div class="col-md-6">
                                    <input id="home_address" type="text" class="form-control{{ $errors->has('home_address') ? ' is-invalid' : '' }}" name="home_address" value="{{ old('home_address') }}"  autofocus>
                                    <span id="message_address" class="invalid-feedback"></span>
                                    @if ($errors->has('home_address'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="home_state" class="col-md-4 col-form-label label-form text-md-right">Provinsi *</label>
                                <div class="col-md-6">
                                    <select id="home_state" name="home_state" class="form-control{{ $errors->has('home_state') ? ' is-invalid' : '' }}"   autofocus>
                                        <option value="" disable="true" selected="true">-- Pilih Provinsi --</option>
                                        @foreach ($provinces as $key => $value)
                                        <option value="{{$value->name}}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>

                                    <span id="message_province" class="invalid-feedback"></span>
                                    @if ($errors->has('home_state'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_state') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="home_city" class="col-md-4 col-form-label label-form text-md-right">Kota *</label>
                                <div class="col-md-6">
                                    <select id="home_city" name="home_city" class="form-control{{ $errors->has('home_city') ? ' is-invalid' : '' }}" value="{{ old('home_city') }}"  autofocus>
                                        <option value="" disable="true" selected="true">-- Pilih Kota/Kabupaten --</option>
                                    </select>
                                    <span id="message_district" class="invalid-feedback"></span>
                                    @if ($errors->has('home_city'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_city') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="home_postal_code" class="col-md-4 col-form-label label-form text-md-right">Kode Pos *</label>
                                <div class="col-md-6">
                                    <input id="home_postal_code" type="text" class="form-control{{ $errors->has('home_postal_code') ? ' is-invalid' : '' }}" name="home_postal_code" value="{{ old('home_postal_code') }}"  pattern="[0-9]{5}" title="Kode Pos harus 5 digit dan Isian harus berupa angka"  autofocus>

                                    <span id="kodePosOut" class="invalid-feedback"></span>
                                    @if ($errors->has('home_postal_code'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_postal_code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="home_phone" class="col-md-4 col-form-label label-form text-md-right">No. Telepon</label>
                                <div class="col-md-6">
                                    <input id="home_phone" type="text" class="form-control{{ $errors->has('home_phone') ? ' is-invalid' : '' }}" name="home_phone" value="{{ old('home_phone') }}">
                                    <small class="form-text text-muted">Contoh format penulisan: 021xxxxxx</small><br>
                                    <span id="message_phone" class="invalid-feedback"></span>
                                    @if ($errors->has('home_phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="id_no" class="col-md-4 col-form-label label-form text-md-right">No. KTP Penanggung Jawab *</label>
                                <div class="col-md-6">
                                    <input id="id_no" type="text" class="form-control{{ $errors->has('id_no') ? ' is-invalid' : '' }}" name="id_no" value="{{ old('id_no') }}" pattern="[0-9]{16}" title="No. KTP harus 16 DIgit dan isian harus berupa angka"  autofocus>
                                    <small class="form-text text-muted">Isian minimal 16 karakter tanpa menggunakan simbol</small><br>
                                    <span id="KTPout" class="invalid-feedback"></span>
                                    @if ($errors->has('id_no'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('id_no') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="id_doc" class="col-md-4 col-form-label label-form text-md-right">Upload KTP Penanggung Jawab *</label>
                                <div class="col-md-6">
                                    <input id="id_doc" type="file" class="form-control{{ $errors->has('id_doc') ? ' is-invalid' : '' }}" name="id_doc" onchange="validateImage('id_doc')"  accept=".jpeg, .png, .jpg">
                                    <small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small>
                                    @if ($errors->has('id_doc'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('id_doc') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <hr>
                            
                            <div class="form-group row">
                                <label for="npwp_no" class="col-md-4 col-form-label label-form text-md-right">No. NPWP *</label>
                                <div class="col-md-6">
                                    <input id="npwp_no" type="text" class="form-control{{ $errors->has('npwp_no') ? ' is-invalid' : '' }}" name="npwp_no" value="{{ old('npwp_no') }}"  pattern="[0-9]{15}" title="No. NPWP harus 15 DIgit dan Isian harus berupa angka" >
                                    <small class="form-text text-muted">Isian minimal 15 karakter tanpa menggunakan simbol</small><br>
                                    <span id="NPWPout" class="invalid-feedback"></span>
                                    @if ($errors->has('npwp_no'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('npwp_no') }}</strong>
                                    </span>
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
                                    <span id="message_status_tinggal" class="invalid-feedback"></span>
                                    @if ($errors->has('home_ownership'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_ownership') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row" id="SIUPKosong">
                                <label for="home_doc" class="col-md-4 col-form-label label-form text-md-right">Upload SIUP</label>
                                <div class="col-md-6">
                                    <input id="home_doc" type="file" class="form-control{{ $errors->has('home_doc') ? '' : '' }}" name="home_doc" accept=".jpeg, .png, .jpg">
                                    <small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small>
                                    @if ($errors->has('home_doc'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_doc') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

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
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('company_type') }}</strong>
                                    </span>
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
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('company_industry') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="financial_doc" class="col-md-4 col-form-label label-form text-md-right">Upload Aset Perusahaan</label>
                                <div class="col-md-6">
                                    <input id="financial_doc" type="file" class="form-control{{ $errors->has('financial_doc') ? '' : '' }}" name="financial_doc"  accept=".jpeg, .png, .jpg">
                                    <small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small>
                                    @if ($errors->has('financial_doc'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('financial_doc') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <hr>

                            <div class="form-group row">
                                <label for="amount_requested" class="col-md-4 col-form-label label-form text-md-right">Jumlah Pinjaman *</label>
                                <div class="col-md-6">
                                    <input id="amount_requested" type="text" class="form-control{{ $errors->has('amount_requested') ? ' is-invalid' : '' }}" name="amount_requested" minlength="9" value="{{ old('amount_requested') }}" >
                                    <small class="form-text text-muted">Minimum peminjaman Rp. 1.000.000</small><br>
                                    @if ($errors->has('amount_requested'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('amount_requested') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tenor_id" class="col-md-4 col-form-label label-form text-md-right">Tenor Pinjaman (bulan) *</label>
                                <div class="col-md-6">
                                    <select name="tenor_id" class="form-control{{ $errors->has('tenor_id') ? ' is-invalid' : '' }}">
                                        @foreach($tenors as $id => $month)
                                        <option value="{{$id}}">{{$month}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('tenor_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('tenor_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label label-form text-md-right">Tujuan Pinjaman</label>
                                <div class="col-md-6">
                                    <select id="description" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">
                                        <option value="Berlibur">Berlibur</option>
                                        <option value="Biaya Kesehatan">Biaya Kesehatan</option>
                                        <option value="Kendaraan Bermotor">Kendaraan Bermotor</option>
                                        <option value="Pendidikan">Pendidikan</option>
                                        <option value="Pernikahan">Pernikahan</option>
                                        <option value="Renovasi Rumah">Renovasi Rumah</option>
                                    </select>
                                    @if ($errors->has('description'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" id="checkbox-form" type="checkbox" name="agreed" value="1" >
                                        <label class="form-check-label label-form" for="agreed">
                                            Saya menyetujui <a href="{{ asset('syarat-dan-ketentuan') }}" title="Syarat dan Ketentuan" target="_blank">Syarat dan Ketentuan</a> dari Pohon Dana
                                        </label>
                                        <p><span id="message_checkbox-form"></span></p>
                                        @if ($errors->has('agreed'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('agreed') }}</strong>
                                        </span>
                                        @endif    
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 col-md-offset-3 text-center transform-captcha">
                                    <div class="g-recaptcha" data-sitekey="6Lfe2E4UAAAAAHG3Io3mEDtsEvFbrUF3ZeW6TgBU"></div>
                                </div>
                            </div>

                            <hr>
                            <p class="text-center">Email verifikasi akun akan dikirimkan setelah pendaftaran</p>


                            <div class="text-center mb15">
                                <p class="text-center small">* Informasi wajib diisi</p>
                                <button class="btn btn-success" type="submit" id="nextBtn">Selanjutnya</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
