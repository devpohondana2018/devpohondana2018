@extends('layouts.register-borrow')

@section('title') Pendaftaran Akun Pendana @endsection

@section('style')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/pohondana/register_lender.css') }}">
@endsection

@section('content')
<section id="content" class="content-form-register" style="background: url('{{ asset('/images/borrow/borrow-background.jpg') }}') no-repeat; ">
    <div class="container">
        <div class="row">
            <div id="form-register-pd" class="col-md-8 col-md-offset-2">
                <div class="card card-default" style="background-color:rgba(255, 255, 255, 0.87); border-radius: 10px;">
                    <div class="card-body">
                        <div class="text-center">
                            <h3 class="nobottommargin" id="headerForm">Pendaftaran Akun Pendana</h3>
                            <p>Sudah punya akun sebelumnya? <a href="{{ url('login') }}" title="Login">Masuk disini</a></p>
                            <hr>
                            @include('includes.notification')
                        </div>

                        <div class="text-center mb15">
                            <p class="text-center small">Anda ingin mengajukan pendanaan sebagai:</p>
                            <a href="{{url ('register/lender') }}" class="btn-primary-mayapada btn-left-home">Individu</a>
                            <a href="{{url ('register/lender_company') }}" class="btn-primary-mayapada btn-left-home">Perusahaan</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
