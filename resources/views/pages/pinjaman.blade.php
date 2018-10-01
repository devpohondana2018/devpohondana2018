@extends('layouts.web')

@section('content')
<section id="content">
    <div class="content-wrap" style="background: url('images/borrow/borrow-background.jpg'); background-size: cover;background-attachment:fixed; padding: 40px 0px !important;">
        <div class="container clearfix" >
            <form id="regForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="col-xs-12 col-sm-6 col-md-6 col-md-offset-3 norightpadding noleftpadding" style="background-color: #fbfcfcf3;">
                    <h3 class="mt15 mb15 center" style="font-size: 28px">Form Pendaftaran</h3>
                    <p class="center">Sudah punya akun sebelumnya? <a href="{{ url('login') }}" title="Login">Masuk disini</a></p>
                    <hr>
                    @include('includes.notification')
                    <div class="tab-borrow">
                        <div class="form-borrow">
                            <h4 class="center">Rencana Peminjaman</h4>
                            <div class="col-md-12 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="amount_requested" class="float-borrow borrow-form borrow-form">Jumlah Pinjaman</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="id_input_pinjam" type="text" class="form-control{{ $errors->has('amount_requested') ? ' is-invalid' : '' }}" name="amount_requested" value="{{ old('amount_requested') }}" required>
                                    @if ($errors->has('amount_requested'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('amount_requested') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-12 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="tenor_id" class="float-borrow borrow-form">Tenor Pinjaman (bulan)</label>
                                </div>
                                <div class="col-md-8 nopadding">
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
                            <div class="col-md-12 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label or="description" class="float-borrow borrow-form borrow-form">Deskripsi Pinjaman</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <textarea name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"></textarea>
                                    @if ($errors->has('description'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                        </div>
                        <div class="line bottommargin-sm"></div>
                    </div>
                    <div class="tab-borrow">
                        <div class="form-borrow">
                            <h4 class="center">Daftar Sebagai Peminjam</h4>
                            <div class="col-md-10 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="name" class="float-borrow borrow-form borrow-form">Nama</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-10 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="mobile_phone" class="float-borrow borrow-form">No. Handphone</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="mobile_phone" type="text" class="form-control{{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}" name="mobile_phone" value="{{ old('mobile_phone') }}" required autofocus>

                                    @if ($errors->has('mobile_phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('mobile_phone') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-10 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="email" class="float-borrow borrow-form borrow-form">Email</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>  
                            <div class="col-md-10 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="password" class="float-borrow borrow-form borrow-form">Password</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>  
                            <div class="col-md-10 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="password-confirm" class="float-borrow borrow-form borrow-form">Konfirmasi Password</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div> 
                            </div>  
                            <div class="line bottommargin-sm"></div>
                            <div class="checkbox center">
                                <label class="borrow-form" for="agreed">
                                    <input type="checkbox" name="agreed" value="1" required> 
                                    Saya menyetujui <b>Syarat dan Ketentuan</b> Pohon Dana
                                    @if ($errors->has('agreed'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('agreed') }}</strong>
                                    </span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="tab-borrow">
                        <div class="form-borrow">
                            <h4 class="center">Data Personal</h4>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="home_address" class="float-borrow borrow-form borrow-form">Alamat</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="home_address" type="text" class="form-control{{ $errors->has('home_address') ? ' is-invalid' : '' }}" name="home_address" value="{{ old('home_address') }}" required autofocus>

                                    @if ($errors->has('home_address'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_address') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="home_city" class="float-borrow borrow-form">Kota</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="home_city" type="text" class="form-control{{ $errors->has('home_city') ? ' is-invalid' : '' }}" name="home_city" value="{{ old('home_city') }}" required autofocus>

                                    @if ($errors->has('home_city'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_city') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="home_state" class="float-borrow borrow-form borrow-form">Provinsi</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="home_state" type="text" class="form-control{{ $errors->has('home_state') ? ' is-invalid' : '' }}" name="home_state" value="{{ old('home_state') }}" required autofocus>

                                    @if ($errors->has('home_state'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_state') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>  
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="home_postal_code" class="float-borrow borrow-form borrow-form">Kode Pos</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="home_postal_code" type="text" class="form-control{{ $errors->has('home_postal_code') ? ' is-invalid' : '' }}" name="home_postal_code" value="{{ old('home_postal_code') }}" required autofocus>

                                    @if ($errors->has('home_postal_code'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_postal_code') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>  
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="home_phone" class="float-borrow borrow-form borrow-form">No. Telepon</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="home_phone" type="text" class="form-control{{ $errors->has('home_phone') ? ' is-invalid' : '' }}" name="home_phone" value="{{ old('home_phone') }}" required autofocus>

                                    @if ($errors->has('home_phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_phone') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="id_no" class="float-borrow borrow-form borrow-form">No. KTP</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="id_no" type="text" class="form-control{{ $errors->has('id_no') ? ' is-invalid' : '' }}" name="id_no" value="{{ old('id_no') }}" required autofocus>

                                    @if ($errors->has('id_no'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('id_no') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="id_doc" class="float-borrow borrow-form borrow-form">Upload KTP</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="id_doc" type="file" class="form-control{{ $errors->has('id_doc') ? ' is-invalid' : '' }}" name="id_doc">
                                    @if ($errors->has('id_doc'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('id_doc') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="npwp_no" class="float-borrow borrow-form borrow-form">No. NPWP</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="npwp_no" type="text" class="form-control{{ $errors->has('npwp_no') ? ' is-invalid' : '' }}" name="npwp_no" value="{{ old('npwp_no') }}" required autofocus>
                                    @if ($errors->has('npwp_no'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('npwp_no') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="pob" class="float-borrow borrow-form borrow-form">Tempat Lahir</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="pob" type="text" class="form-control{{ $errors->has('pob') ? ' is-invalid' : '' }}" name="pob" value="{{ old('pob') }}" required autofocus>

                                    @if ($errors->has('pob'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('pob') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="dob" class="float-borrow borrow-form borrow-form">Tgl Lahir</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="dob" type="text" class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{ old('dob') }}" required autofocus>

                                    @if ($errors->has('dob'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="home_ownership" class="float-borrow borrow-form borrow-form">Status Tempat Tinggal</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <select name="home_ownership" class="form-control{{ $errors->has('home_ownership') ? ' is-invalid' : '' }}">
                                        <option value="sendiri">Milik Sendiri</option>
                                        <option value="keluarga">Keluarga</option>
                                        <option value="sewa">Sewa</option>
                                        <option value="lainnya">Lain-lain</option>
                                    </select>
                                    @if ($errors->has('home_ownership'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_ownership') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="home_doc" class="float-borrow borrow-form borrow-form">Upload PBB</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="home_doc" type="file" class="form-control{{ $errors->has('home_doc') ? ' is-invalid' : '' }}" name="home_doc">
                                    @if ($errors->has('home_doc'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_doc') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="line bottommargin-sm"></div> 
                        </div>
                    </div>
                    <div class="tab-borrow">
                        <div class="form-borrow">
                            <h4 class="center">Apakah anda bekerja di salah satu perusahaan ini?</h4>
                            <div class="col-md-12 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="company_id" class="float-borrow borrow-form borrow-form">Perusahaan</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <select name="company_id" class="form-control{{ $errors->has('company_id') ? ' is-invalid' : '' }}">
                                        <option value="">Tidak Ada</option>
                                        @foreach($companies as $id => $name)
                                        <option value="{{$id}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">ika tidak ada di daftar, silahkan memilih Tidak Ada dan mengisikan nama perusahaan Anda dibawah
                                    </small>
                                    @if ($errors->has('company_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('company_id') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-12 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="company_other" class="float-borrow borrow-form borrow-form">Perusahaan Lainnya</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="company_other" type="text" class="form-control{{ $errors->has('company_other') ? ' is-invalid' : '' }}" name="company_other" value="{{ old('company_other') }}">
                                    @if ($errors->has('company_other'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('company_other') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="tab-borrow">
                        <div class="form-borrow">
                            <h4 class="center">Riwayat Pekerjaan</h4>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label class="float-borrow borrow-form borrow-form" for="employment_salary">Gaji / Bulan</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="employment_salary" type="text" class="form-control{{ $errors->has('employment_salary') ? ' is-invalid' : '' }}" name="employment_salary" value="{{ old('employment_salary') }}" required autofocus>

                                    @if ($errors->has('employment_salary'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('employment_salary') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="employment_salary_slip" class="float-borrow borrow-form" for="">Upload Slip Gaji</label>
                                </div>
                                <div class="col-md-8 nopadding">
                                    <input id="employment_salary_slip" type="file" class="form-control{{ $errors->has('employment_salary_slip') ? ' is-invalid' : '' }}" name="employment_salary_slip">
                                    @if ($errors->has('employment_salary_slip'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('employment_salary_slip') }}</strong>
                                    </span>
                                    @endif
                                </div> 
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="employment_position" class="float-borrow borrow-form borrow-form" for="">Jabatan</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="employment_position" type="text" class="form-control{{ $errors->has('employment_position') ? ' is-invalid' : '' }}" name="employment_position" value="{{ old('employment_position') }}">

                                    @if ($errors->has('employment_position'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('employment_position') }}</strong>
                                    </span>
                                    @endif
                                </div>  
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="employment_duration" class="float-borrow borrow-form borrow-form" for="">Lama Bekerja (bulan)</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="employment_duration" type="text" class="form-control{{ $errors->has('employment_duration') ? ' is-invalid' : '' }}" name="employment_duration" value="{{ old('employment_duration') }}">

                                    @if ($errors->has('employment_duration'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('employment_duration') }}</strong>
                                    </span>
                                    @endif
                                </div>  
                            </div>
                            <div class="col-md-11 form-group nopadding">
                                <div class="col-md-4 noleftpadding">
                                    <label for="employment_status" class="float-borrow borrow-form borrow-form" for="">Status Pegawai</label>
                                </div>
                                <div class="col-md-8">
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
                </div>
                <div class="center mb15">
                    <button class="btn-borrow" type="button" id="prevBtnBorrow" onclick="nextPrev(-1)">Sebelumnya</button>
                    <button class="btn-borrow" type="button" id="nextBtnBorrow" onclick="nextPrev(1)">Lanjut</button>
                </div>
                <div style="text-align:center; margin-top:20px; margin-bottom: 20px">
                    <span class="step-borrow"></span>
                    <span class="step-borrow"></span>
                    <span class="step-borrow"></span>
                    <span class="step-borrow"></span>
                    <span class="step-borrow"></span>
                </div>
            </div>
        </form>
    </div>
</div>
</section><!-- #content end -->
@endsection