@extends('layouts.header-dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            <div class="card card-default card-padding">
                <div class="card-body">
                    <div class="text-center">
                        <h3>Data Diri</h3>
                        <hr>
                        @include('includes.notification')
                    </div>
                    @if(Auth::check() && Auth::user()->type == 'badan')
                    <form method="POST" action="{{ url('member/profile') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        <div class="form-group row">
                            <label for="company" class="col-md-4 col-form-label text-md-right">Nama Perusahaan</label>
                            <div class="col-md-8">
                                <input id="company" type="text" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="company" value="{{ @$user->company->name }}">
                                @if ($errors->has('company'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nama Penanggung Jawab</label>
                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"  value="{{ $user->name }}" required autofocus>

                                @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        @role('borrower')

                        <div class="form-group row">
                            <label for="mobile_phone" class="col-md-4 col-form-label text-md-right">No. Handphone</label>
                            <div class="col-md-8">
                                <input id="mobile_phone" type="text" class="form-control{{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}" name="mobile_phone" value="{{ $user->mobile_phone }}" required autofocus>

                                @if ($errors->has('mobile_phone'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('mobile_phone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_address" class="col-md-4 col-form-label text-md-right">Alamat Perusahaan</label>
                            <div class="col-md-8">
                                <input id="home_address" type="text" class="form-control{{ $errors->has('home_address') ? ' is-invalid' : '' }}" name="home_address" value="{{ $user->home_address }}" required autofocus>

                                @if ($errors->has('home_address'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_state" class="col-md-4 col-form-label text-md-right">Provinsi</label>
                            <div class="col-md-8">
                                <input id="home_state" type="text" class="form-control{{ $errors->has('home_state') ? ' is-invalid' : '' }}" name="home_state" value="{{ $user->home_state }}" required autofocus>

                                @if ($errors->has('home_state'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_state') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_city" class="col-md-4 col-form-label text-md-right">Kota</label>
                            <div class="col-md-8">
                                <input id="home_city" type="text" class="form-control{{ $errors->has('home_city') ? ' is-invalid' : '' }}" name="home_city" value="{{ $user->home_city }}" required autofocus>

                                @if ($errors->has('home_city'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_city') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_postal_code" class="col-md-4 col-form-label text-md-right">Kode Pos</label>
                            <div class="col-md-8">
                                <input id="home_postal_code" type="text" class="form-control{{ $errors->has('home_postal_code') ? ' is-invalid' : '' }}" name="home_postal_code" value="{{ $user->home_postal_code }}" required autofocus>

                                @if ($errors->has('home_postal_code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_postal_code') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_phone" class="col-md-4 col-form-label text-md-right">No. Telepon</label>
                            <div class="col-md-8">
                                <input id="home_phone" type="text" class="form-control{{ $errors->has('home_phone') ? ' is-invalid' : '' }}" name="home_phone" value="{{ $user->home_phone }}" required autofocus>

                                @if ($errors->has('home_phone'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_phone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_no" class="col-md-4 col-form-label text-md-right">No. KTP Penanggung Jawab</label>
                            <div class="col-md-8">
                                <input id="id_no" type="text" class="form-control{{ $errors->has('id_no') ? ' is-invalid' : '' }}" name="id_no" value="{{ $user->id_no }}" required autofocus>

                                @if ($errors->has('id_no'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('id_no') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_doc" class="col-md-4 col-form-label text-md-right">Upload KTP Penanggung Jawab</label>
                            <div class="col-md-8">
                                <input id="id_doc" type="file" class="form-control{{ $errors->has('id_doc') ? ' is-invalid' : '' }}" name="id_doc">
                                @if(Auth::user()->id_doc)<img src="{{Storage::disk('public')->url(Auth::user()->id_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('id_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('id_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="npwp_no" class="col-md-4 col-form-label text-md-right">No. NPWP Penanggung Jawab</label>
                            <div class="col-md-8">
                                <input id="npwp_no" type="text" class="form-control{{ $errors->has('npwp_no') ? ' is-invalid' : '' }}" name="npwp_no" value="{{ $user->npwp_no }}" required autofocus>

                                @if ($errors->has('npwp_no'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('npwp_no') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_ownership" class="col-md-4 col-form-label text-md-right">Status Domisili Perusahaan</label>
                            <div class="col-md-8">
                                <select name="home_ownership" class="form-control{{ $errors->has('home_ownership') ? ' is-invalid' : '' }}">
                                    <option value="sendiri" {{ (Auth::user()->home_ownership == 'sendiri') ? 'selected' : '' }}>Milik Sendiri</option>
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
                            <label for="home_doc" class="col-md-4 col-form-label text-md-right">Upload SIUP</label>
                            <div class="col-md-8">
                                <input id="home_doc" type="file" class="form-control{{ $errors->has('home_doc') ? ' is-invalid' : '' }}" name="home_doc">
                                @if(Auth::user()->home_doc)<img src="{{Storage::disk('public')->url(Auth::user()->home_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('home_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_type" class="col-md-4 col-form-label text-md-right">Jenis Perusahaan</label>
                            <div class="col-md-8">
                                <select name="company_type" class="form-control{{ $errors->has('company_type') ? ' is-invalid' : '' }}">
                                    <option value="PT" {{ (Auth::user()->company_type == 'PT') ? 'selected' : '' }}>PT</option>
                                    <option value="Koperasi" {{ (Auth::user()->company_type == 'Koperasi') ? 'selected' : '' }}>Koperasi</option>
                                    <option value="Pemerintah Pusat" {{ (Auth::user()->company_type == 'Pemerintah Pusat') ? 'selected' : '' }}>Pemerintah Pusat</option>
                                    <option value="Pemerintah Daerah" {{ (Auth::user()->company_type == 'Pemerintah Daerah') ? 'selected' : '' }}>Pemerintah Daerah</option>
                                    <option value="lainnya" {{ (Auth::user()->company_type == 'lainnya') ? 'selected' : '' }}>Lain - lain</option>
                                </select>
                                @if ($errors->has('company_type'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_industry" class="col-md-4 col-form-label text-md-right">Industri</label>
                            <div class="col-md-8">
                                <select name="company_industry" class="form-control{{ $errors->has('company_industry') ? ' is-invalid' : '' }}">
                                    <option value="Industri Pengolahan Pangan" {{ (Auth::user()->company_industry == 'Industri Pengolahan Pangan') ? 'selected' : '' }}>Industri Pengolahan Pangan</option>
                                    <option value="Industri Tekstil" {{ (Auth::user()->company_industry == 'Industri Tekstil') ? 'selected' : '' }}>Industri Tekstil</option>
                                    <option value="Industri Barang Kulit" {{ (Auth::user()->company_industry == 'Industri Barang Kulit') ? 'selected' : '' }}>Industri Barang Kulit</option>
                                    <option value="Industri Pengolahan Kayu" {{ (Auth::user()->company_industry == 'Industri Pengolahan Kayu') ? 'selected' : '' }}>Industri Pengolahan Kayu</option>
                                    <option value="Industri Pengolahan Kertas" {{ (Auth::user()->company_industry == 'Industri Pengolahan Kertas') ? 'selected' : '' }}>Industri Pengolahan Kertas</option>
                                    <option value="Industri Kimia Farmasi" {{ (Auth::user()->company_industry == 'Industri Kimia Farmasi') ? 'selected' : '' }}>Industri Kimia Farmasi</option>
                                    <option value="Industri Pengolahan Karet" {{ (Auth::user()->company_industry == 'Industri Pengolahan Karet') ? 'selected' : '' }}>Industri Pengolahan Karet</option>
                                    <option value="Industri Barang Galian bukan Logam" {{ (Auth::user()->company_industry == 'Industri Barang Galian bukan Logam') ? 'selected' : '' }}>Industri Barang Galian bukan Logam</option>
                                    <option value="Industri Baja / Pengolahan Logam" {{ (Auth::user()->company_industry == 'Industri Baja / Pengolahan Logam') ? 'selected' : '' }}>Industri Baja / Pengolahan Logam</option>
                                    <option value="Industri Peralatan" {{ (Auth::user()->company_industry == 'Industri Peralatan') ? 'selected' : '' }}>Industri Peralatan</option>
                                    <option value="Industri Pertambangan" {{ (Auth::user()->company_industry == 'Industri Pertambangan') ? 'selected' : '' }}>Industri Pertambangan</option>
                                    <option value="Industri Pariwisata" {{ (Auth::user()->company_industry == 'Industri Pariwisata') ? 'selected' : '' }}>Industri Pariwisata</option>
                                    <option value="lainnya" {{ (Auth::user()->company_industry == 'lainnya') ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @if ($errors->has('company_industry'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company_industry') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <label for="financial_doc" class="col-md-4 col-form-label text-md-right">Upload Aset Perusahaan</label>
                            <div class="col-md-8">
                                <input id="financial_doc" type="file" class="form-control{{ $errors->has('financial_doc') ? ' is-invalid' : '' }}" name="financial_doc">
                                @if(Auth::user()->financial_doc)<img src="{{Storage::disk('public')->url(Auth::user()->financial_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('financial_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('financial_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div> -->

                        <hr>

                        @endrole

                        @role('lender')

                        <div class="form-group row">
                            <label for="employment_type" class="col-md-4 col-form-label text-md-right">Jenis Perusahaan</label>
                            <div class="col-md-8">
                                <input id="employment_type" type="text" class="form-control{{ $errors->has('employment_type') ? ' is-invalid' : '' }}" name="employment_type" value="{{ $user->employment_type }}" required autofocus>

                                @if ($errors->has('employment_type'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('employment_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mobile_phone" class="col-md-4 col-form-label text-md-right">No. Handphone</label>
                            <div class="col-md-8">
                                <input id="mobile_phone" type="text" class="form-control{{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}" name="mobile_phone" value="{{ $user->mobile_phone }}" required autofocus>

                                @if ($errors->has('mobile_phone'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('mobile_phone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_no" class="col-md-4 col-form-label text-md-right">No. KTP Penanggung Jawab</label>
                            <div class="col-md-8">
                                <input id="id_no" type="text" class="form-control{{ $errors->has('id_no') ? ' is-invalid' : '' }}" name="id_no" value="{{ $user->id_no }}" required autofocus>

                                @if ($errors->has('id_no'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('id_no') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_doc" class="col-md-4 col-form-label text-md-right">Upload KTP Penanggung Jawab</label>
                            <div class="col-md-8">
                                <input id="id_doc" type="file" class="form-control{{ $errors->has('id_doc') ? ' is-invalid' : '' }}" name="id_doc">
                                @if(Auth::user()->id_doc)<img src="{{Storage::disk('public')->url(Auth::user()->id_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('id_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('id_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_address" class="col-md-4 col-form-label text-md-right">Alamat Perusahaan</label>
                            <div class="col-md-8">
                                <input id="home_address" type="text" class="form-control{{ $errors->has('home_address') ? ' is-invalid' : '' }}" name="home_address" value="{{ $user->home_address }}" required autofocus>

                                @if ($errors->has('home_address'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_state" class="col-md-4 col-form-label text-md-right">Provinsi</label>
                            <div class="col-md-8">
                                <input id="home_state" type="text" class="form-control{{ $errors->has('home_state') ? ' is-invalid' : '' }}" name="home_state" value="{{ $user->home_state }}" required autofocus>

                                @if ($errors->has('home_state'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_state') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_city" class="col-md-4 col-form-label text-md-right">Kota</label>
                            <div class="col-md-8">
                                <input id="home_city" type="text" class="form-control{{ $errors->has('home_city') ? ' is-invalid' : '' }}" name="home_city" value="{{ $user->home_city }}" required autofocus>

                                @if ($errors->has('home_city'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_city') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_postal_code" class="col-md-4 col-form-label text-md-right">Kode Pos</label>
                            <div class="col-md-8">
                                <input id="home_postal_code" type="text" class="form-control{{ $errors->has('home_postal_code') ? ' is-invalid' : '' }}" name="home_postal_code" value="{{ $user->home_postal_code }}" required autofocus>

                                @if ($errors->has('home_postal_code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_postal_code') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_phone" class="col-md-4 col-form-label text-md-right">No. Telepon</label>
                            <div class="col-md-8">
                                <input id="home_phone" type="text" class="form-control{{ $errors->has('home_phone') ? ' is-invalid' : '' }}" name="home_phone" value="{{ $user->home_phone }}" required autofocus>

                                @if ($errors->has('home_phone'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_phone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="website_url" class="col-md-4 col-form-label text-md-right">Alamat Website</label>
                            <div class="col-md-8">
                                <input id="website_url" type="text" class="form-control{{ $errors->has('website_url') ? ' is-invalid' : '' }}" name="website_url" value="{{ $user->website_url }}" required autofocus>

                                @if ($errors->has('website_url'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('website_url') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_type" class="col-md-4 col-form-label text-md-right">Jenis Perusahaan</label>
                            <div class="col-md-8">
                                <select name="company_type" class="form-control{{ $errors->has('company_type') ? ' is-invalid' : '' }}">
                                    <option value="PT" {{ (Auth::user()->company_type == 'PT') ? 'selected' : '' }}>PT</option>
                                    <option value="Koperasi" {{ (Auth::user()->company_type == 'Koperasi') ? 'selected' : '' }}>Koperasi</option>
                                    <option value="Pemerintah Pusat" {{ (Auth::user()->company_type == 'Pemerintah Pusat') ? 'selected' : '' }}>Pemerintah Pusat</option>
                                    <option value="Pemerintah Daerah" {{ (Auth::user()->company_type == 'Pemerintah Daerah') ? 'selected' : '' }}>Pemerintah Daerah</option>
                                    <option value="lainnya" {{ (Auth::user()->company_type == 'lainnya') ? 'selected' : '' }}>Lain - lain</option>
                                </select>
                                @if ($errors->has('company_type'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_industry" class="col-md-4 col-form-label text-md-right">Industri</label>
                            <div class="col-md-8">
                                <select name="company_industry" class="form-control{{ $errors->has('company_industry') ? ' is-invalid' : '' }}">
                                    <option value="Industri Pengolahan Pangan" {{ (Auth::user()->company_industry == 'Industri Pengolahan Pangan') ? 'selected' : '' }}>Industri Pengolahan Pangan</option>
                                    <option value="Industri Tekstil" {{ (Auth::user()->company_industry == 'Industri Tekstil') ? 'selected' : '' }}>Industri Tekstil</option>
                                    <option value="Industri Barang Kulit" {{ (Auth::user()->company_industry == 'Industri Barang Kulit') ? 'selected' : '' }}>Industri Barang Kulit</option>
                                    <option value="Industri Pengolahan Kayu" {{ (Auth::user()->company_industry == 'Industri Pengolahan Kayu') ? 'selected' : '' }}>Industri Pengolahan Kayu</option>
                                    <option value="Industri Pengolahan Kertas" {{ (Auth::user()->company_industry == 'Industri Pengolahan Kertas') ? 'selected' : '' }}>Industri Pengolahan Kertas</option>
                                    <option value="Industri Kimia Farmasi" {{ (Auth::user()->company_industry == 'Industri Kimia Farmasi') ? 'selected' : '' }}>Industri Kimia Farmasi</option>
                                    <option value="Industri Pengolahan Karet" {{ (Auth::user()->company_industry == 'Industri Pengolahan Karet') ? 'selected' : '' }}>Industri Pengolahan Karet</option>
                                    <option value="Industri Barang Galian bukan Logam" {{ (Auth::user()->company_industry == 'Industri Barang Galian bukan Logam') ? 'selected' : '' }}>Industri Barang Galian bukan Logam</option>
                                    <option value="Industri Baja / Pengolahan Logam" {{ (Auth::user()->company_industry == 'Industri Baja / Pengolahan Logam') ? 'selected' : '' }}>Industri Baja / Pengolahan Logam</option>
                                    <option value="Industri Peralatan" {{ (Auth::user()->company_industry == 'Industri Peralatan') ? 'selected' : '' }}>Industri Peralatan</option>
                                    <option value="Industri Pertambangan" {{ (Auth::user()->company_industry == 'Industri Pertambangan') ? 'selected' : '' }}>Industri Pertambangan</option>
                                    <option value="Industri Pariwisata" {{ (Auth::user()->company_industry == 'Industri Pariwisata') ? 'selected' : '' }}>Industri Pariwisata</option>
                                    <option value="lainnya" {{ (Auth::user()->company_industry == 'lainnya') ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @if ($errors->has('company_industry'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company_industry') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_ownership" class="col-md-4 col-form-label text-md-right">Status Domisili Perusahaan</label>
                            <div class="col-md-8">
                                <select name="home_ownership" class="form-control{{ $errors->has('home_ownership') ? ' is-invalid' : '' }}">
                                    <option value="sendiri" {{ (Auth::user()->home_ownership == 'sendiri') ? 'selected' : '' }}>Milik Sendiri</option>
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
                            <label for="npwp_no" class="col-md-4 col-form-label text-md-right">No. NPWP Perusahaan</label>
                            <div class="col-md-8">
                                <input id="npwp_no" type="text" class="form-control{{ $errors->has('npwp_no') ? ' is-invalid' : '' }}" name="npwp_no" value="{{ $user->npwp_no }}" required autofocus>

                                @if ($errors->has('npwp_no'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('npwp_no') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="npwp_doc" class="col-md-4 col-form-label text-md-right">Upload NPWP Perusahaan</label>
                            <div class="col-md-8">
                                <input id="npwp_doc" type="file" class="form-control{{ $errors->has('npwp_doc') ? ' is-invalid' : '' }}" name="npwp_doc">
                                @if(Auth::user()->npwp_doc)<img src="{{Storage::disk('public')->url(Auth::user()->npwp_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('npwp_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('npwp_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="akta_doc" class="col-md-4 col-form-label text-md-right">Upload Akta Perusahaan</label>
                            <div class="col-md-8">
                                <input id="akta_doc" type="file" class="form-control{{ $errors->has('akta_doc') ? ' is-invalid' : '' }}" name="akta_doc">
                                @if(Auth::user()->akta_doc)<img src="{{Storage::disk('public')->url(Auth::user()->akta_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('akta_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('akta_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_doc" class="col-md-4 col-form-label text-md-right">Upload SIUP</label>
                            <div class="col-md-8">
                                <input id="home_doc" type="file" class="form-control{{ $errors->has('home_doc') ? ' is-invalid' : '' }}" name="home_doc">
                                @if(Auth::user()->home_doc)<img src="{{Storage::disk('public')->url(Auth::user()->home_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('home_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tdp_doc" class="col-md-4 col-form-label text-md-right">Upload TDP</label>
                            <div class="col-md-8">
                                <input id="tdp_doc" type="file" class="form-control{{ $errors->has('tdp_doc') ? ' is-invalid' : '' }}" name="tdp_doc">
                                @if(Auth::user()->tdp_doc)<img src="{{Storage::disk('public')->url(Auth::user()->tdp_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('tdp_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('tdp_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <hr>


                        @endrole

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Simpan
                                </button>
                            </div>
                        </div>

                    </form>

                    @elseif((Auth::check() && Auth::user()->type == 'orang'))

                    <form method="POST" action="{{ url('member/profile') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nama</label>
                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"  value="{{ $user->name }}" required autofocus>

                                @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mobile_phone" class="col-md-4 col-form-label text-md-right">No. Handphone</label>
                            <div class="col-md-8">
                                <input id="mobile_phone" type="text" class="form-control{{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}" name="mobile_phone" value="{{ $user->mobile_phone }}" required autofocus>

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
                            <div class="col-md-8">
                                <input id="home_address" type="text" class="form-control{{ $errors->has('home_address') ? ' is-invalid' : '' }}" name="home_address" value="{{ $user->home_address }}" required autofocus>

                                @if ($errors->has('home_address'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_state" class="col-md-4 col-form-label text-md-right">Provinsi</label>
                            <div class="col-md-8">
                                <input id="home_state" type="text" class="form-control{{ $errors->has('home_state') ? ' is-invalid' : '' }}" name="home_state" value="{{ $user->home_state }}" required autofocus>

                                @if ($errors->has('home_state'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_state') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_city" class="col-md-4 col-form-label text-md-right">Kota</label>
                            <div class="col-md-8">
                                <input id="home_city" type="text" class="form-control{{ $errors->has('home_city') ? ' is-invalid' : '' }}" name="home_city" value="{{ $user->home_city }}" required autofocus>

                                @if ($errors->has('home_city'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_city') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_postal_code" class="col-md-4 col-form-label text-md-right">Kode Pos</label>
                            <div class="col-md-8">
                                <input id="home_postal_code" type="text" class="form-control{{ $errors->has('home_postal_code') ? ' is-invalid' : '' }}" name="home_postal_code" value="{{ $user->home_postal_code }}" required autofocus>

                                @if ($errors->has('home_postal_code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_postal_code') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_phone" class="col-md-4 col-form-label text-md-right">No. Telepon</label>
                            <div class="col-md-8">
                                <input id="home_phone" type="text" class="form-control{{ $errors->has('home_phone') ? ' is-invalid' : '' }}" name="home_phone" value="{{ $user->home_phone }}" required autofocus>

                                @if ($errors->has('home_phone'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_phone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_no" class="col-md-4 col-form-label text-md-right">No. KTP</label>
                            <div class="col-md-8">
                                <input id="id_no" type="text" class="form-control{{ $errors->has('id_no') ? ' is-invalid' : '' }}" name="id_no" value="{{ $user->id_no }}" required autofocus>

                                @if ($errors->has('id_no'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('id_no') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_doc" class="col-md-4 col-form-label text-md-right">Upload KTP</label>
                            <div class="col-md-8">
                                <input id="id_doc" type="file" class="form-control{{ $errors->has('id_doc') ? ' is-invalid' : '' }}" name="id_doc">
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
                            <div class="col-md-8">
                                <input id="npwp_no" type="text" class="form-control{{ $errors->has('npwp_no') ? ' is-invalid' : '' }}" name="npwp_no" value="{{ $user->npwp_no }}" required autofocus>

                                @if ($errors->has('npwp_no'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('npwp_no') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="npwp_doc" class="col-md-4 col-form-label text-md-right">Upload NPWP</label>
                            <div class="col-md-8">
                                <input id="npwp_doc" type="file" class="form-control{{ $errors->has('npwp_doc') ? ' is-invalid' : '' }}" name="npwp_doc">
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
                            <div class="col-md-8">
                                <input id="pob" type="text" class="form-control{{ $errors->has('pob') ? ' is-invalid' : '' }}" name="pob" value="{{ $user->pob }}" required autofocus>

                                @if ($errors->has('pob'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('pob') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dob" class="col-md-4 col-form-label text-md-right">Tgl Lahir</label>
                            <div class="col-md-8">
                                <input id="dob" type="date" class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{ $user->dob }}" required autofocus>

                                @if ($errors->has('dob'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('dob') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_ownership" class="col-md-4 col-form-label text-md-right">Status Tempat Tinggal</label>
                            <div class="col-md-8">
                                <select name="home_ownership" class="form-control{{ $errors->has('home_ownership') ? ' is-invalid' : '' }}">
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
                            <label for="home_doc" class="col-md-4 col-form-label text-md-right">Upload PBB</label>
                            <div class="col-md-8">
                                <input id="home_doc" type="file" class="form-control{{ $errors->has('home_doc') ? ' is-invalid' : '' }}" name="home_doc">
                                @if(Auth::user()->home_doc)<img src="{{Storage::disk('public')->url(Auth::user()->home_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('home_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">Jenis Kelamin</label>
                            <div class="col-md-8">
                                <select name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                    <option value="laki" {{ (Auth::user()->gender == 'laki') ? 'selected' : '' }}>Laki - laki</option>
                                    <option value="perempuan" {{ (Auth::user()->gender == 'perempuan') ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @if ($errors->has('gender'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="education" class="col-md-4 col-form-label text-md-right">Pendidikan Terakhir</label>
                            <div class="col-md-8">
                                <select name="education" class="form-control{{ $errors->has('education') ? ' is-invalid' : '' }}">
                                    <option value="sd" {{ (Auth::user()->education == 'sd') ? 'selected' : '' }}>SD</option>
                                    <option value="smp" {{ (Auth::user()->education == 'smp') ? 'selected' : '' }}>SMP</option>
                                    <option value="sma" {{ (Auth::user()->education == 'sma') ? 'selected' : '' }}>SMA</option>
                                    <option value="diploma" {{ (Auth::user()->education == 'diploma') ? 'selected' : '' }}>Diploma</option>
                                    <option value="sarjana" {{ (Auth::user()->education == 'sarjana') ? 'selected' : '' }}>Sarjana</option>
                                </select>
                                @if ($errors->has('education'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('education') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="religion" class="col-md-4 col-form-label text-md-right">Agama</label>
                            <div class="col-md-8">
                                <select name="religion" class="form-control{{ $errors->has('religion') ? ' is-invalid' : '' }}">
                                    <option value="islam" {{ (Auth::user()->religion == 'islam') ? 'selected' : '' }}>Islam</option>
                                    <option value="kristen_protestan" {{ (Auth::user()->religion == 'kristen_protestan') ? 'selected' : '' }}>Kristen Protestan</option>
                                    <option value="kristen_katolik" {{ (Auth::user()->religion == 'kristen_katolik') ? 'selected' : '' }}>Kristen Katolik</option>
                                    <option value="hindu" {{ (Auth::user()->religion == 'hindu') ? 'selected' : '' }}>Hindu</option>
                                    <option value="buddha" {{ (Auth::user()->religion == 'buddha') ? 'selected' : '' }}>Buddha</option>
                                    <option value="konghucu" {{ (Auth::user()->religion == 'konghucu') ? 'selected' : '' }}>Konghucu</option>
                                    <option value="lainnya" {{ (Auth::user()->religion == 'lainnya') ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @if ($errors->has('religion'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('religion') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="company" class="col-md-4 col-form-label text-md-right">Perusahaan</label>
                            <div class="col-md-8">
                                <input id="company" type="text" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="company" value="{{ @$user->company->name }}">
                                @if ($errors->has('company'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_type" class="col-md-4 col-form-label text-md-right">Pekerjaan</label>
                            <div class="col-md-8">
                                <select name="employment_type" class="form-control{{ $errors->has('employment_type') ? ' is-invalid' : '' }}">
                                    <option value="pns" {{ (Auth::user()->employment_type == 'pns') ? 'selected' : '' }}>PNS</option>
                                    <option value="bumn" {{ (Auth::user()->employment_type == 'bumn') ? 'selected' : '' }}>BUMN</option>
                                    <option value="swasta" {{ (Auth::user()->employment_type == 'swasta') ? 'selected' : '' }}>Swasta</option>
                                    <option value="wiraswasta" {{ (Auth::user()->employment_type == 'wiraswasta') ? 'selected' : '' }}>Wiraswasta</option>
                                    <option value="lainnya" {{ (Auth::user()->employment_type == 'lainnya') ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @if ($errors->has('employment_type'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('employment_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_salary" class="col-md-4 col-form-label text-md-right">Gaji / Bulan</label>
                            <div class="col-md-8">
                                <input id="employment_salary" type="text" class="form-control{{ $errors->has('employment_salary') ? ' is-invalid' : '' }}" name="employment_salary" value="{{ $user->employment_salary }}" required autofocus>

                                @if ($errors->has('employment_salary'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('employment_salary') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_salary_slip" class="col-md-4 col-form-label text-md-right">Upload Slip Gaji</label>
                            <div class="col-md-8">
                                <input id="employment_salary_slip" type="file" class="form-control{{ $errors->has('employment_salary_slip') ? ' is-invalid' : '' }}" name="employment_salary_slip">
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
                            <div class="col-md-8">
                                <input id="employment_position" type="text" class="form-control{{ $errors->has('employment_position') ? ' is-invalid' : '' }}" name="employment_position" value="{{ $user->employment_position }}">

                                @if ($errors->has('employment_position'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('employment_position') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_duration" class="col-md-4 col-form-label text-md-right">Lama Bekerja (bulan)</label>
                            <div class="col-md-8">
                                <input id="employment_duration" type="text" class="form-control{{ $errors->has('employment_duration') ? ' is-invalid' : '' }}" name="employment_duration" value="{{ $user->employment_duration }}">

                                @if ($errors->has('employment_duration'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('employment_duration') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_status" class="col-md-4 col-form-label text-md-right">Status Pegawai</label>
                            <div class="col-md-8">
                                <select name="employment_status" class="form-control{{ $errors->has('employment_status') ? ' is-invalid' : '' }}">
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

                        @endrole

                        @role('lender')

                        <div class="form-group row">
                            <label for="id_no" class="col-md-4 col-form-label text-md-right">No. KTP</label>
                            <div class="col-md-8">
                                <input id="id_no" type="text" class="form-control{{ $errors->has('id_no') ? ' is-invalid' : '' }}" name="id_no" value="{{ $user->id_no }}" required autofocus>

                                @if ($errors->has('id_no'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('id_no') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_doc" class="col-md-4 col-form-label text-md-right">Upload KTP</label>
                            <div class="col-md-8">
                                <input id="id_doc" type="file" class="form-control{{ $errors->has('id_doc') ? ' is-invalid' : '' }}" name="id_doc">
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
                            <div class="col-md-8">
                                <input id="npwp_no" type="text" class="form-control{{ $errors->has('npwp_no') ? ' is-invalid' : '' }}" name="npwp_no" value="{{ $user->npwp_no }}" required autofocus>

                                @if ($errors->has('npwp_no'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('npwp_no') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="npwp_doc" class="col-md-4 col-form-label text-md-right">Upload NPWP</label>
                            <div class="col-md-8">
                                <input id="npwp_doc" type="file" class="form-control{{ $errors->has('npwp_doc') ? ' is-invalid' : '' }}" name="npwp_doc">
                                @if(Auth::user()->npwp_doc)<img src="{{Storage::disk('public')->url(Auth::user()->npwp_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('npwp_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('npwp_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_address" class="col-md-4 col-form-label text-md-right">Alamat</label>
                            <div class="col-md-8">
                                <input id="home_address" type="text" class="form-control{{ $errors->has('home_address') ? ' is-invalid' : '' }}" name="home_address" value="{{ $user->home_address }}" required autofocus>

                                @if ($errors->has('home_address'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_state" class="col-md-4 col-form-label text-md-right">Provinsi</label>
                            <div class="col-md-8">
                                <input id="home_state" type="text" class="form-control{{ $errors->has('home_state') ? ' is-invalid' : '' }}" name="home_state" value="{{ $user->home_state }}" required autofocus>

                                @if ($errors->has('home_state'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_state') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_city" class="col-md-4 col-form-label text-md-right">Kota</label>
                            <div class="col-md-8">
                                <input id="home_city" type="text" class="form-control{{ $errors->has('home_city') ? ' is-invalid' : '' }}" name="home_city" value="{{ $user->home_city }}" required autofocus>

                                @if ($errors->has('home_city'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_city') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_postal_code" class="col-md-4 col-form-label text-md-right">Kode Pos</label>
                            <div class="col-md-8">
                                <input id="home_postal_code" type="text" class="form-control{{ $errors->has('home_postal_code') ? ' is-invalid' : '' }}" name="home_postal_code" value="{{ $user->home_postal_code }}" required autofocus>

                                @if ($errors->has('home_postal_code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_postal_code') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home_phone" class="col-md-4 col-form-label text-md-right">No. Telepon</label>
                            <div class="col-md-8">
                                <input id="home_phone" type="text" class="form-control{{ $errors->has('home_phone') ? ' is-invalid' : '' }}" name="home_phone" value="{{ $user->home_phone }}" required autofocus>

                                @if ($errors->has('home_phone'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_phone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pob" class="col-md-4 col-form-label text-md-right">Tempat Lahir</label>
                            <div class="col-md-8">
                                <input id="pob" type="text" class="form-control{{ $errors->has('pob') ? ' is-invalid' : '' }}" name="pob" value="{{ $user->pob }}" required autofocus>

                                @if ($errors->has('pob'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('pob') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dob" class="col-md-4 col-form-label text-md-right">Tgl Lahir</label>
                            <div class="col-md-8">
                                <input id="dob" type="date" class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{ $user->dob }}" required autofocus>

                                @if ($errors->has('dob'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('dob') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <label for="home_ownership" class="col-md-4 col-form-label text-md-right">Status Tempat Tinggal</label>
                            <div class="col-md-8">
                                <select name="home_ownership" class="form-control{{ $errors->has('home_ownership') ? ' is-invalid' : '' }}">
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
                            <label for="home_doc" class="col-md-4 col-form-label text-md-right">Upload PBB</label>
                            <div class="col-md-8">
                                <input id="home_doc" type="file" class="form-control{{ $errors->has('home_doc') ? ' is-invalid' : '' }}" name="home_doc">
                                @if(Auth::user()->home_doc)<img src="{{Storage::disk('public')->url(Auth::user()->home_doc)}}" class="img-fluid mt-2">@endif
                                @if ($errors->has('home_doc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('home_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">Jenis Kelamin</label>
                            <div class="col-md-8">
                                <select name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                    <option value="laki" {{ (Auth::user()->gender == 'laki') ? 'selected' : '' }}>Laki - laki</option>
                                    <option value="perempuan" {{ (Auth::user()->gender == 'perempuan') ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @if ($errors->has('gender'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="education" class="col-md-4 col-form-label text-md-right">Pendidikan Terakhir</label>
                            <div class="col-md-8">
                                <select name="education" class="form-control{{ $errors->has('education') ? ' is-invalid' : '' }}">
                                    <option value="sd" {{ (Auth::user()->education == 'sd') ? 'selected' : '' }}>SD</option>
                                    <option value="smp" {{ (Auth::user()->education == 'smp') ? 'selected' : '' }}>SMP</option>
                                    <option value="sma" {{ (Auth::user()->education == 'sma') ? 'selected' : '' }}>SMA</option>
                                    <option value="diploma" {{ (Auth::user()->education == 'diploma') ? 'selected' : '' }}>Diploma</option>
                                    <option value="sarjana" {{ (Auth::user()->education == 'sarjana') ? 'selected' : '' }}>Sarjana</option>
                                </select>
                                @if ($errors->has('education'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('education') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="religion" class="col-md-4 col-form-label text-md-right">Agama</label>
                            <div class="col-md-8">
                                <select name="religion" class="form-control{{ $errors->has('religion') ? ' is-invalid' : '' }}">
                                    <option value="islam" {{ (Auth::user()->religion == 'islam') ? 'selected' : '' }}>Islam</option>
                                    <option value="kristen_protestan" {{ (Auth::user()->religion == 'kristen_protestan') ? 'selected' : '' }}>Kristen Protestan</option>
                                    <option value="kristen_katolik" {{ (Auth::user()->religion == 'kristen_katolik') ? 'selected' : '' }}>Kristen Katolik</option>
                                    <option value="hindu" {{ (Auth::user()->religion == 'hindu') ? 'selected' : '' }}>Hindu</option>
                                    <option value="buddha" {{ (Auth::user()->religion == 'buddha') ? 'selected' : '' }}>Buddha</option>
                                    <option value="konghucu" {{ (Auth::user()->religion == 'konghucu') ? 'selected' : '' }}>Konghucu</option>
                                    <option value="lainnya" {{ (Auth::user()->religion == 'lainnya') ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @if ($errors->has('religion'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('religion') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="company" class="col-md-4 col-form-label text-md-right">Perusahaan</label>
                            <div class="col-md-8">
                                <input id="company" type="text" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="company" value="{{ @$user->company->name }}">
                                @if ($errors->has('company'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_type" class="col-md-4 col-form-label text-md-right">Pekerjaan</label>
                            <div class="col-md-8">
                                <select name="employment_type" class="form-control{{ $errors->has('employment_type') ? ' is-invalid' : '' }}">
                                    <option value="pns" {{ (Auth::user()->employment_type == 'pns') ? 'selected' : '' }}>PNS</option>
                                    <option value="bumn" {{ (Auth::user()->employment_type == 'bumn') ? 'selected' : '' }}>BUMN</option>
                                    <option value="swasta" {{ (Auth::user()->employment_type == 'swasta') ? 'selected' : '' }}>Swasta</option>
                                    <option value="wiraswasta" {{ (Auth::user()->employment_type == 'wiraswasta') ? 'selected' : '' }}>Wiraswasta</option>
                                    <option value="lainnya" {{ (Auth::user()->employment_type == 'lainnya') ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @if ($errors->has('employment_type'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('employment_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_salary" class="col-md-4 col-form-label text-md-right">Gaji / Bulan</label>
                            <div class="col-md-8">
                                <input id="employment_salary" type="text" class="form-control{{ $errors->has('employment_salary') ? ' is-invalid' : '' }}" name="employment_salary" value="{{ $user->employment_salary }}" required autofocus>

                                @if ($errors->has('employment_salary'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('employment_salary') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_salary_slip" class="col-md-4 col-form-label text-md-right">Upload Slip Gaji</label>
                            <div class="col-md-8">
                                <input id="employment_salary_slip" type="file" class="form-control{{ $errors->has('employment_salary_slip') ? ' is-invalid' : '' }}" name="employment_salary_slip">
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
                            <div class="col-md-8">
                                <input id="employment_position" type="text" class="form-control{{ $errors->has('employment_position') ? ' is-invalid' : '' }}" name="employment_position" value="{{ $user->employment_position }}">

                                @if ($errors->has('employment_position'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('employment_position') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_duration" class="col-md-4 col-form-label text-md-right">Lama Bekerja (bulan)</label>
                            <div class="col-md-8">
                                <input id="employment_duration" type="text" class="form-control{{ $errors->has('employment_duration') ? ' is-invalid' : '' }}" name="employment_duration" value="{{ $user->employment_duration }}">

                                @if ($errors->has('employment_duration'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('employment_duration') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="employment_status" class="col-md-4 col-form-label text-md-right">Status Pegawai</label>
                            <div class="col-md-8">
                                <select name="employment_status" class="form-control{{ $errors->has('employment_status') ? ' is-invalid' : '' }}">
                                    <option value="kontrak" {{ (Auth::user()->employment_status == 'kontrak') ? 'selected' : '' }}>Kontrak</option>
                                    <option value="permanen" {{ (Auth::user()->employment_status == 'permanen') ? 'selected' : '' }}>Permanen</option>
                                </select>
                                @if ($errors->has('employment_status'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('employment_status') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div> -->

                        @endrole

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Simpan
                                </button>
                            </div>
                        </div>

                    </form>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection