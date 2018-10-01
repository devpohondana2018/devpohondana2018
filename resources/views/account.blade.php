@extends('layouts.header-dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2 col-xs-12">
            <form method="POST" action="{{ url('member/account') }}" enctype="multipart/form-data">
            <div class="card card-default card-padding">
                <div class="card-body">
                    <div class="text-center">
                        <h3>Akun Anda</h3>
                        <hr>
                        @include('includes.notification')
                    </div>
                        
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        <!-- <div class="form-group row">
                            <label for="avatar" class="col-md-4 col-form-label text-md-right">Foto Anda</label>
                            <div class="col-md-8">
                                <input id="avatar" type="file" class="form-control{{ $errors->has('avatar') ? ' is-invalid' : '' }}" name="avatar">
                                <img src="{{ Auth::user()->avatar ? Storage::disk('public')->url(Auth::user()->avatar) : asset('images/avatars/default.jpg') }}" alt="" height="100" class="mt-2"/>
                                @if ($errors->has('avatar'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('avatar') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Alamat Email</label>
                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin merubah</small>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Konfirmasi Password</label>
                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                <small class="form-text text-muted">Konfirmasi password untuk merubah</small>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Simpan
                                </button>
                            </div>
                        </div>

                    
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection