@extends('layouts.register-borrow')
@section('title') Verifikasi Akun @endsection
@section('content')
<section id="content" class="content-form-register" style="background: url('{{ asset('/images/borrow/borrow-background.jpg') }}') no-repeat;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2">
                <div class="card card-default" style="background-color:rgba(255, 255, 255, 0.87); border-radius: 10px;">
                    <div class="card-body">
                        <div class="text-center">
                            <h3 class="nobottommargin">Kirim ulang email verifikasi</h3>
                            <p>Sudah punya akun sebelumnya? <a href="{{ url('login') }}" title="Login">Masuk disini</a></p>
                            <hr>
                            @include('includes.notification')
                        </div>
                        <form method="POST" action="{{ route('auth.verify.email') }}">
                            {{ csrf_field() }}

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label label-form text-md-right">Alamat Email</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email or old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-sm-4 col-form-label col-md-offset-4">
                                    <button type="submit" class="btn btn-success">
                                        Kirim
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
