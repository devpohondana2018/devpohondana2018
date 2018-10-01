@extends('layouts.register-borrow')

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
                            <div class="tab">
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label label-form text-md-right">Nama Lengkap *</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                                        <small class="form-text text-muted">Mohon mengisi nama lengkap sesuai KTP</small>
                                        @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="mobile_phone" class="col-md-4 col-form-label label-form text-md-right">No. Handphone *</label>
                                    <div class="col-md-6">
                                        <input id="mobile_phone" type="text" class="form-control{{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}" name="mobile_phone" value="{{ old('mobile_phone') }}" onblur="duplicateMobile(this)" placeholder="08xxxxxx" required>
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
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" onblur="duplicateEmail(this)" required>
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
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
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
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required name="password-confirm" onChange="checkPasswordMatch();">
                                        <small class="form-text text-muted">Isi dengan password yang sama</small><br>
                                        <span id='message_confirm_password'></span>
                                    </div>
                                </div>

                                <hr>
                            </div>

                            <div class="tab">
                                <div class="form-group row">
                                    <label for="home_address" class="col-md-4 col-form-label label-form text-md-right">Alamat *</label>
                                    <div class="col-md-6">
                                        <input id="home_address" type="text" class="form-control{{ $errors->has('home_address') ? ' is-invalid' : '' }}" name="home_address" value="{{ old('home_address') }}" required autofocus>
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
                                        <select id="home_state" name="home_state" class="form-control{{ $errors->has('home_state') ? ' is-invalid' : '' }}"  required autofocus>
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
                                        <select id="home_city" name="home_city" class="form-control{{ $errors->has('home_city') ? ' is-invalid' : '' }}" value="{{ old('home_city') }}" required autofocus>
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
                                        <input id="home_postal_code" type="text" class="form-control{{ $errors->has('home_postal_code') ? ' is-invalid' : '' }}" name="home_postal_code" value="{{ old('home_postal_code') }}"  pattern="[0-9]{5}" title="Kode Pos harus 5 digit dan Isian harus berupa angka" required autofocus>

                                        <span id="kodePosOut" class="invalid-feedback"></span>
                                        @if ($errors->has('home_postal_code'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('home_postal_code') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="home_phone" class="col-md-4 col-form-label label-form text-md-right">No. Telepon Rumah</label>
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
                                    <label for="id_no" class="col-md-4 col-form-label label-form text-md-right">No. KTP *</label>
                                    <div class="col-md-6">
                                        <input id="id_no" type="text" class="form-control{{ $errors->has('id_no') ? ' is-invalid' : '' }}" name="id_no" value="{{ old('id_no') }}" pattern="[0-9]{16}" title="No. KTP harus 16 DIgit dan isian harus berupa angka" onblur="duplicateKTP(this)" required autofocus>
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
                                    <label for="id_doc" class="col-md-4 col-form-label label-form text-md-right">Upload KTP *</label>
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

                                <div class="form-group row">
                                    <label for="npwp_no" class="col-md-4 col-form-label label-form text-md-right">No. NPWP *</label>
                                    <div class="col-md-6">
                                        <input id="npwp_no" type="text" class="form-control{{ $errors->has('npwp_no') ? ' is-invalid' : '' }}" name="npwp_no" value="{{ old('npwp_no') }}"  pattern="[0-9]{15}" title="No. NPWP harus 15 DIgit dan Isian harus berupa angka" onblur="duplicateNPWP(this)">
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
                                    <label for="npwp_doc" class="col-md-4 col-form-label label-form text-md-right">Upload NPWP</label>
                                    <div class="col-md-6">
                                        <input id="npwp_doc" type="file" class="form-control{{ $errors->has('npwp_doc') ? ' is-invalid' : '' }}" name="npwp_doc" onchange="validateImage('npwp_doc')"  accept=".jpeg, .png, .jpg">
                                        <small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small>
                                        @if ($errors->has('npwp_doc'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('npwp_doc') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="pob" class="col-md-4 col-form-label label-form text-md-right">Tempat Lahir *</label>
                                    <div class="col-md-6">
                                        <input id="pob" type="text" class="form-control{{ $errors->has('pob') ? ' is-invalid' : '' }}" name="pob" value="{{ old('pob') }}" required autofocus>

                                        @if ($errors->has('pob'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('pob') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="dob" class="col-md-4 col-form-label label-form text-md-right">Tanggal Lahir *</label>
                                    <div class="col-md-6">
                                        <input id="dob" type="text" readonly class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{ old('dob') }}" required autofocus>
                                        <span id="message_dob" class="invalid-feedback"></span>
                                        @if ($errors->has('dob'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="home_ownership" class="col-md-4 col-form-label label-form text-md-right">Status Tempat Tinggal *</label>
                                    <div class="col-md-6">
                                        <select id="home_ownership" name="home_ownership" class="form-control{{ $errors->has('home_ownership') ? ' is-invalid' : '' }}">
                                            <option value="">-- Pilih Status Tempat Tinggal --</option>
                                            <option value="sendiri">Milik Sendiri</option>
                                            <option value="keluarga">Keluarga</option>
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

                                <div class="form-group row" id="PBBKosong">
                                    <label for="home_doc" class="col-md-4 col-form-label label-form text-md-right">Upload PBB </label>
                                    <div class="col-md-6">
                                        <input id="home_doc" type="file" class="form-control{{ $errors->has('home_doc') ? '' : '' }}" name="home_doc" onchange="validateImage('home_doc')" accept=".jpeg, .png, .jpg">
                                        <small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small>
                                        @if ($errors->has('home_doc'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('home_doc') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="gender" class="col-md-4 col-form-label label-form text-md-right">Jenis Kelamin *</label>
                                    <div class="col-md-6">
                                        <select id="gender" name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                            <option value="laki">Laki-laki</option>
                                            <option value="perempuan">Perempuan</option>
                                        </select>
                                        @if ($errors->has('gender'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('gender') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="education" class="col-md-4 col-form-label label-form text-md-right">Pendidikan Terakhir *</label>
                                    <div class="col-md-6">
                                        <select id="education" name="education" class="form-control{{ $errors->has('education') ? ' is-invalid' : '' }}">
                                            <option value="sd">SD</option>
                                            <option value="smp">SMP</option>
                                            <option value="sma">SMA</option>
                                            <option value="diploma">Diploma</option>
                                            <option value="sarjana">Sarjana</option>
                                        </select>
                                        @if ($errors->has('education'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('education') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="religion" class="col-md-4 col-form-label label-form text-md-right">Agama *</label>
                                    <div class="col-md-6">
                                        <select id="religion" name="religion" class="form-control{{ $errors->has('religion') ? ' is-invalid' : '' }}">
                                            <option value="islam">Islam</option>
                                            <option value="kristen_protestan">Kristen Protestan</option>
                                            <option value="kristen_katolik">Kristen Katolik</option>
                                            <option value="hindu">Hindu</option>
                                            <option value="buddha">Buddha</option>
                                            <option value="konghucu">Konghucu</option>
                                            <option value="lainnya">Lain-lain</option>
                                        </select>
                                        @if ($errors->has('religion'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('religion') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <hr>
                            </div>

                            <div class="tab">
                                <div class="form-group row">
                                    <label for="amount_requested" class="col-md-4 col-form-label label-form text-md-right">Jumlah Pinjaman *</label>
                                    <div class="col-md-6">
                                        <input id="amount_requested" type="text" class="form-control{{ $errors->has('amount_requested') ? ' is-invalid' : '' }}" name="amount_requested" minlength="9" value="{{ old('amount_requested') }}" onChange="checkAmountRequested();" required>
                                        <small class="form-text text-muted">Minimum peminjaman Rp. 1.000.000</small><br>
                                        <span id='message_confirm_amount'></span>
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
                                <hr>
                            </div>

                            <div class="tab">
                                <div class="form-group row">
                                    <label for="company_id" class="col-md-4 col-form-label label-form text-md-right">Perusahaan *</label>
                                    <div class="col-md-6">
                                        <input id="company_id" type="text" class="form-control{{ $errors->has('company_id') ? ' is-invalid' : '' }}" name="company_id" value="{{ old('company_id') }}" data-provide="typeahead" autocomplete="off" placeholder="Silahkan ketik nama perusahaan anda ...">
                                        @if ($errors->has('company_id'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('company_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <hr>
                            </div>

                            <div class="tab">
                                <div class="form-group row">
                                    <label for="employment_type" class="col-md-4 col-form-label label-form text-md-right">Pekerjaan *</label>
                                    <div class="col-md-6">
                                        <select id="employment_type" name="employment_type" class="form-control{{ $errors->has('employment_type') ? ' is-invalid' : '' }}">
                                            <option value="pns">PNS</option>
                                            <option value="bumn">BUMN</option>
                                            <option value="swasta">Swasta</option>
                                            <option value="wiraswasta">Wiraswasta</option>
                                            <option value="lainnya">Lain-lain</option>
                                        </select>
                                        @if ($errors->has('employment_type'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('employment_type') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="employment_salary" class="col-md-4 col-form-label label-form text-md-right">Gaji / Bulan *</label>
                                    <div class="col-md-6">
                                        <input id="employment_salary" type="text" class="form-control{{ $errors->has('employment_salary') ? ' is-invalid' : '' }}" name="employment_salary" value="{{ old('employment_salary') }}" required autofocus>

                                        @if ($errors->has('employment_salary'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('employment_salary') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="employment_salary_slip" class="col-md-4 col-form-label label-form text-md-right">Upload Slip Gaji</label>
                                    <div class="col-md-6">
                                        <input id="employment_salary_slip" type="file" class="form-control{{ $errors->has('employment_salary_slip') ? ' is-invalid' : '' }}" name="employment_salary_slip" onchange="validateImage('employment_salary_slip')"  accept=".jpeg, .png, .jpg">
                                        <small class="form-text text-muted">Tipe file berupa .jpg, .jpeg, .gif, .svg, .png</small>
                                        @if ($errors->has('employment_salary_slip'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('employment_salary_slip') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="employment_position" class="col-md-4 col-form-label label-form text-md-right">Jabatan *</label>
                                    <div class="col-md-6">
                                        <input id="employment_position" type="text" class="form-control{{ $errors->has('employment_position') ? ' is-invalid' : '' }}" name="employment_position" value="{{ old('employment_position') }}">

                                        @if ($errors->has('employment_position'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('employment_position') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="employment_duration" class="col-md-4 col-form-label label-form text-md-right">Lama Bekerja *</label>
                                    <div class="col-md-2">
                                        <input id="employment_duration_year" type="text" class="form-control{{ $errors->has('employment_duration_year') ? ' is-invalid' : '' }}" name="employment_duration_year" value="{{ old('employment_duration_year') }}" pattern="[0-9]{1}" title="Isian harus berupa angka">

                                        <span id="lamaBerkerjaTahun" class="invalid-feedback"></span>
                                        @if ($errors->has('employment_duration_year'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('employment_duration_year') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <label for="employment_duration" class="col-md-1 col-form-label label-form text-md-right label-kerja">Tahun</label>
                                    <div class="col-md-2">
                                        <input id="employment_duration_month" type="text" class="form-control{{ $errors->has('employment_duration_month') ? ' is-invalid' : '' }}" name="employment_duration_month" value="{{ old('employment_duration_month') }}" pattern="[0-9]{1}" title="Isian harus berupa angka">

                                        <span id="lamaBerkerjaBulan" class="invalid-feedback"></span>
                                        @if ($errors->has('employment_duration_month'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('employment_duration_month') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <label for="employment_duration" class="col-md-1 col-form-label label-form text-md-right label-kerja">Bulan</label>
                                </div>

                                <div class="form-group row">
                                    <label for="employment_status" class="col-md-4 col-form-label label-form text-md-right">Status Pegawai *</label>
                                    <div class="col-md-6">
                                        <select name="employment_status" class="form-control{{ $errors->has('employment_status') ? ' is-invalid' : '' }}">
                                            <option value="kontrak">Kontrak</option>
                                            <option value="permanen">Permanen</option>
                                        </select>
                                        @if ($errors->has('employment_status'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('employment_status') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="tab">
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
                                                    <label class="form-check-label label-form" for="agreed">
                                                        Saya menyetujui Syarat dan Ketentuan dari Pohon Dana
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
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-12 col-md-offset-3 text-center transform-captcha">
                                        <div class="g-recaptcha" data-sitekey="6Lfe2E4UAAAAAHG3Io3mEDtsEvFbrUF3ZeW6TgBU"></div>
                                    </div>
                                </div>

                                <hr>
                                <p class="text-center">Email verifikasi akun akan dikirimkan setelah pendaftaran</p>
                            </div>

                            <div class="text-center mb15">
                                <p class="text-center small">* Informasi wajib diisi</p>
                                <button class="btn btn-default" type="button" id="prevBtn" onclick="nextPrev(-1)">Sebelumnya</button>
                                <button class="btn btn-success" type="button" id="nextBtn" onclick="nextPrev(1)">Selanjutnya</button>
                            </div>
                            <div style="text-align:center; margin-top:20px; margin-bottom: 20px">
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
