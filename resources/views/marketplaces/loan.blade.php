@extends('layouts.header-dashboard')

@section('content')
<div class="container">
    <h1 class="text-left">Marketplace</h1>
    <p class="text-left">Informasi pinjaman</p>

    @include('includes.notification')

    <div class="row">
        <div class="col-md-6">
            
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Pinjaman #{{$loan->id}}</strong> <span class="badge badge-secondary"></span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>{{$loan->description}}</p>
                        </div>
                    </div>
                    <div class="card-body-detail">
                        <div class="row nopadding">

                            <div class="col-md-6 nopadding">
                                <dt>Total Pinjaman:</dt>
                            </div>

                            <div class="col-md-6 nopadding">
                                <dd>Rp {{number_format($loan->amount_total_calculated,2)}}</dd>
                            </div>
                        </div>
                        <div class="row nopadding">

                            <div class="col-md-6 nopadding">
                                <dt>Sisa Pendanaan:</dt>
                            </div>

                            <div class="col-md-6 nopadding">
                                <dd>Rp {{number_format($loan->amount_total_calculated - $loan->amount_funded,2)}}</dd>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6 nopadding">
                                <dt>Tanggal Pengajuan:</dt>
                            </div>

                            <div class="col-md-6 nopadding">
                                <dt>{{$loan->created_at->format('d/m/Y')}}</dt>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6 nopadding">
                                <dt>Tenor:</dt> 
                            </div>

                            <div class="col-md-6 nopadding">
                                <dt>{{$loan->tenor->month}} bulan</dt>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6 nopadding">
                                <dt>Bunga:</dt>
                            </div>

                            <div class="col-md-6 nopadding">
                                <dt>
                                    @if($loan->interest_rate) {{$loan->interest_rate}}%
                                    @else 0%
                                    @endif
                                </dt>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6 nopadding">
                                <dt>Status Pinjaman:</dt>
                            </div>

                            <div class="col-md-6 nopadding">
                                <dd>{{$loan->status->name}}</dd>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6 nopadding">
                                <dt>Status Pendanaan:</dt>
                            </div>

                            <div class="col-md-6 nopadding">
                                <dd>{{round(($loan->amount_funded/$loan->amount_total)*100,2)}}%</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                   <form method="POST" class="create-form-pd" action="{{ route('marketplace.funding') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="uid" value="{{$loan->id}}">
                    <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Rp</div>
                        </div>
                        <input id="jumlah" type="text" class="amount_money_mask form-control{{ $errors->has('jumlah') ? ' is-invalid' : '' }}" name="jumlah" placeholder="500,000" required>
                        @if ($errors->has('jumlah'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('jumlah') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            Berikan Pinjaman
                        </button>
                    </div>
                </form>
                <p><a href="#" class="text-muted font-small">Syarat & Ketentuan</a></p>

                </div>
            </div>
        </div>
    </div>

   
</div>
@endsection