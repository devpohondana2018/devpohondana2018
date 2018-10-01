@extends('layouts.header-dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2 col-xs-12">
            <form class="form-horizontal create-form-pd" method="POST" action="{{ url('member/loans') }}" enctype="multipart/form-data">
            <div class="card card-default">
                <div class="card-body">
                    <div class="text-center">
                        <h3>Pengajuan Pinjaman</h3>
                        <hr>
                        @include('includes.notification')
                    </div>
                    
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="amount_requested">Jumlah Pinjaman</label>
                            <input id="amount_requested" type="text" class="amount_money_mask form-control{{ $errors->has('amount_requested') ? ' is-invalid' : '' }}" name="amount_requested" value="{{ old('amount_requested') }}" required>
                            @if ($errors->has('amount_requested'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('amount_requested') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="tenor_id">Tenor Pinjaman (bulan)</label>
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

                        <div class="form-group">
                            <label for="description">Tujuan Pinjaman</label>
                            <select id="description" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">
                                <option value="Renovasi Rumah">Renovasi Rumah</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Berlibur">Berlibur</option>
                                <option value="Pernikahan">Pernikahan</option>
                                <option value="Biaya Kesehatan">Biaya Kesehatan</option>
                                <option value="Kendaraan Bermotor">Kendaraan Bermotor</option>
                            </select>
                            @if ($errors->has('description'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                            @endif
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary btn-md btn-block">Ajukan</button>

                    
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection