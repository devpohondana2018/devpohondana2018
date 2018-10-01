@extends('layouts.header-dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-body">
                    <div class="text-center">
                        <h3>Pengajuan Pendanaan</h3>
                        <hr>
                        @include('includes.notification')
                    </div>
                    <form class="form-horizontal create-form-pd" method="post" action="{{ url('member/investments') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="amount_invested">Jumlah Pendanaan</label>
                        <input id="amount_invested" type="text" class="amount_money_mask form-control{{ $errors->has('amount_invested') ? ' is-invalid' : '' }}" name="amount_invested" value="{{ old('amount_invested') }}" required>
                        @if ($errors->has('amount_invested'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('amount_invested') }}</strong>
                            </span>
                        @endif
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary btn-md btn-block">Ajukan</button>
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection