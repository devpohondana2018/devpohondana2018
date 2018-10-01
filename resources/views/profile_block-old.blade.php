@extends('layouts.header-dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-body">
                    <div class="text-center">
                        <h3>Data Diri <span class="badge badge-success"><i class="fa fa-check"></i> Verified</span></h3>
                        <p>Silahkan menghubungi <a href="{{route('kontak-kami')}}" title="Kontak Kami">kami</a> jika ingin merubah data diri Anda.</p>
                        <hr>
                        @include('includes.notification')
                    </div>
                    <form method="POST" action="{{ url('member/profile') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nama</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"  value="{{ $user->name }}" readonly="">

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mobile_phone" class="col-md-4 col-form-label text-md-right">No. Handphone</label>
                            <div class="col-md-6">
                                <input id="mobile_phone" type="text" class="form-control{{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}" name="mobile_phone" value="{{ $user->mobile_phone }}" readonly="">

                                @if ($errors->has('mobile_phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('mobile_phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @role('borrower')

                        <div class="form-group row">
                            <label for="home_address" class="col-md-4 col-form-label text-md-right">Alamat</label>
                            <div class="col-md-6">
                                <input id="home_address" type="text" class="form-control{{ $errors->has('home_address') ? ' is-invalid' : '' }}" name="home_address" value="{{ $user->home_address }}" readonly="">

                                @if ($errors->has('home_address'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_city" class="col-md-4 col-form-label text-md-right">Kota</label>
                            <div class="col-md-6">
                                <input id="home_city" type="text" class="form-control{{ $errors->has('home_city') ? ' is-invalid' : '' }}" name="home_city" value="{{ $user->home_city }}" readonly="">

                                @if ($errors->has('home_city'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_state" class="col-md-4 col-form-label text-md-right">Provinsi</label>
                            <div class="col-md-6">
                                <input id="home_state" type="text" class="form-control{{ $errors->has('home_state') ? ' is-invalid' : '' }}" name="home_state" value="{{ $user->home_state }}" readonly="">

                                @if ($errors->has('home_state'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_state') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_postal_code" class="col-md-4 col-form-label text-md-right">Kode Pos</label>
                            <div class="col-md-6">
                                <input id="home_postal_code" type="text" class="form-control{{ $errors->has('home_postal_code') ? ' is-invalid' : '' }}" name="home_postal_code" value="{{ $user->home_postal_code }}" readonly="">

                                @if ($errors->has('home_postal_code'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_postal_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_phone" class="col-md-4 col-form-label text-md-right">No. Telepon</label>
                            <div class="col-md-6">
                                <input id="home_phone" type="text" class="form-control{{ $errors->has('home_phone') ? ' is-invalid' : '' }}" name="home_phone" value="{{ $user->home_phone }}" readonly="">

                                @if ($errors->has('home_phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_no" class="col-md-4 col-form-label text-md-right">No. KTP</label>
                            <div class="col-md-6">
                                <input id="id_no" type="text" class="form-control{{ $errors->has('id_no') ? ' is-invalid' : '' }}" name="id_no" value="{{ $user->id_no }}" readonly="">

                                @if ($errors->has('id_no'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('id_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_doc" class="col-md-4 col-form-label text-md-right">KTP</label>
                            <div class="col-md-6">
                                @if(Auth::user()->id_doc)<img src="{{Storage::disk('public')->url(Auth::user()->id_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('id_doc'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('id_doc') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="npwp_no" class="col-md-4 col-form-label text-md-right">No. NPWP</label>
                            <div class="col-md-6">
                                <input id="npwp_no" type="text" class="form-control{{ $errors->has('npwp_no') ? ' is-invalid' : '' }}" name="npwp_no" value="{{ $user->npwp_no }}" readonly="">

                                @if ($errors->has('npwp_no'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('npwp_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="npwp_doc" class="col-md-4 col-form-label text-md-right">NPWP</label>
                            <div class="col-md-6">
                                @if(Auth::user()->npwp_doc)<img src="{{Storage::disk('public')->url(Auth::user()->npwp_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('npwp_doc'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('npwp_doc') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pob" class="col-md-4 col-form-label text-md-right">Tempat Lahir</label>
                            <div class="col-md-6">
                                <input id="pob" type="text" class="form-control{{ $errors->has('pob') ? ' is-invalid' : '' }}" name="pob" value="{{ $user->pob }}" readonly="">

                                @if ($errors->has('pob'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('pob') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dob" class="col-md-4 col-form-label text-md-right">Tgl Lahir</label>
                            <div class="col-md-6">
                                <input id="dob" type="text" class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{ $user->dob }}" readonly="">

                                @if ($errors->has('dob'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_ownership" class="col-md-4 col-form-label text-md-right">Status Tempat Tinggal</label>
                            <div class="col-md-6">
                                <select name="home_ownership" class="form-control{{ $errors->has('home_ownership') ? ' is-invalid' : '' }}" disabled="">
                                    <option value="sendiri" {{ (Auth::user()->home_ownership == 'sendiri') ? 'selected' : '' }}>Milik Sendiri</option>
                                    <option value="keluarga" {{ (Auth::user()->home_ownership == 'keluarga') ? 'selected' : '' }}>Keluarga</option>
                                    <option value="sewa" {{ (Auth::user()->home_ownership == 'sewa') ? 'selected' : '' }}>Sewa</option>
                                    <option value="lainnya" {{ (Auth::user()->home_ownership == 'lainnya') ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @if ($errors->has('home_ownership'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_ownership') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_doc" class="col-md-4 col-form-label text-md-right">PBB</label>
                            <div class="col-md-6">
                                @if(Auth::user()->home_doc)<img src="{{Storage::disk('public')->url(Auth::user()->home_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('home_doc'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('home_doc') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="company" class="col-md-4 col-form-label text-md-right">Perusahaan</label>
                            <div class="col-md-6">
                                <input id="company" type="text" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="company" value="{{ @$user->company->name }}" readonly="">
                                @if ($errors->has('company'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('company') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_salary" class="col-md-4 col-form-label text-md-right">Gaji / Bulan</label>
                            <div class="col-md-6">
                                <input id="employment_salary" type="text" class="form-control{{ $errors->has('employment_salary') ? ' is-invalid' : '' }}" name="employment_salary" value="{{ $user->employment_salary }}" readonly="">

                                @if ($errors->has('employment_salary'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('employment_salary') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_salary_slip" class="col-md-4 col-form-label text-md-right">Slip Gaji</label>
                            <div class="col-md-6">
                                @if(Auth::user()->employment_salary_slip)<img src="{{Storage::disk('public')->url(Auth::user()->employment_salary_slip)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('employment_salary_slip'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('employment_salary_slip') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_position" class="col-md-4 col-form-label text-md-right">Jabatan</label>
                            <div class="col-md-6">
                                <input id="employment_position" type="text" class="form-control{{ $errors->has('employment_position') ? ' is-invalid' : '' }}" name="employment_position" value="{{ $user->employment_position }}" readonly="">

                                @if ($errors->has('employment_position'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('employment_position') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_duration" class="col-md-4 col-form-label text-md-right">Lama Bekerja (bulan)</label>
                            <div class="col-md-6">
                                <input id="employment_duration" type="text" class="form-control{{ $errors->has('employment_duration') ? ' is-invalid' : '' }}" name="employment_duration" value="{{ $user->employment_duration }}" readonly="">

                                @if ($errors->has('employment_duration'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('employment_duration') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_status" class="col-md-4 col-form-label text-md-right">Status Pegawai</label>
                            <div class="col-md-6">
                                <select name="employment_status" class="form-control{{ $errors->has('employment_status') ? ' is-invalid' : '' }}" disabled="">
                                    <option value="kontrak" {{ (Auth::user()->employment_status == 'kontrak') ? 'selected' : '' }}>Kontrak</option>
                                    <option value="permanen" {{ (Auth::user()->employment_status == 'permanen') ? 'selected' : '' }}>Permanen</option>
                                </select>
                                @if ($errors->has('employment_status'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('employment_status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="bank_name" class="col-md-4 col-form-label text-md-right">Bank Name</label>
                            <div class="col-md-6">
                                <input id="bank_name" type="text" class="form-control{{ $errors->has('bank_name') ? ' is-invalid' : '' }}" name="bank_name" value="{{ @$user->bankAccount->bank->name }}" readonly="">
                                @if ($errors->has('bank_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('bank_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="account_number" class="col-md-4 col-form-label text-md-right">Nomor Rekening</label>
                            <div class="col-md-6">
                                <input id="account_number" type="text" class="form-control{{ $errors->has('account_number') ? ' is-invalid' : '' }}" name="account_number" value="{{ @$user->bankAccount->account_number }}" readonly="">
                                @if ($errors->has('account_number'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('account_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="account_name" class="col-md-4 col-form-label text-md-right">Pemilik</label>
                            <div class="col-md-6">
                                <input id="account_name" type="text" class="form-control{{ $errors->has('account_name') ? ' is-invalid' : '' }}" name="account_name" value="{{ @$user->bankAccount->account_name }}" readonly="">
                                @if ($errors->has('account_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('account_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @endrole

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection