@extends('layouts.register-borrow')

@section('title') Login @endsection

@section('content')
<section id="content" class="content-form-register" style="background: url('{{ asset('/images/borrow/borrow-background.jpg') }}') no-repeat;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
           <div class="card card-default" style="background-color:rgba(255, 255, 255, 0.87); border-radius: 10px;">
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="nobottommargin">Masuk ke akun</h3>
                        <p>Belum pernah terdaftar sebelumnya?<br>Daftar sebagai <a href="{{ url('/register') }}" title="Register Borrower">Peminjam</a> atau <a href="{{ url('/register/lender') }}" title="Register Lender">Pendana</a></p>
                        <hr>
                        @include('includes.notification')
                        @if ($errors->has('active'))
                        <div class="alert alert-danger">
                            <strong>{{ $errors->first('active') }}</strong>
                        </div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label label-form text-md-right">Alamat Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 label-form col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-md-right col-md-offset-4">
                                <div class="checkbox text-left">
                                    <label class="label-form">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Ingat Saya
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-sm-4 col-form-label text-md-right col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Masuk
                                </button>
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>
                            </div>
                        </div>

                        <hr>
                        <p class="text-center">Belum menerima email verifikasi akun? <br/><a href="{{ route('auth.verify.resend') }}" title="Verify">Klik disini untuk mengirim ulang</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
