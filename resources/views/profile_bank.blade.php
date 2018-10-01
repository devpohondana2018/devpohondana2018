@extends('layouts.header-dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2 col-xs-12">
            <form method="POST" action="{{ url('member/profile_bank') }}" enctype="multipart/form-data">
            <div class="card card-default">
                <div class="card-body">
                    <div class="text-center">
                        <h3>Edit Profil Bank</h3>
                        <hr>
                        @include('includes.notification')
                    </div>
                    
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        <div class="form-group row">
                            <label for="bank_id" class="col-md-4 col-form-label text-md-right">Bank</label>
                            <div class="col-md-6">
                                <select name="bank_id" class="form-control{{ $errors->has('bank_id') ? ' is-invalid' : '' }}">
                                    <option value="">Pilih Bank</option>
                                    @foreach($banks as $id => $bank)
                                        <option value="{{$id}}" {{ (@Auth::user()->bankAccount->bank_id == $id) ? 'selected' : '' }}>{{$bank}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('bank_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('bank_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="account_name" class="col-md-4 col-form-label text-md-right">Bank Account Name</label>
                            <div class="col-md-6">
                                <input id="account_name" type="text" class="form-control{{ $errors->has('account_name') ? ' is-invalid' : '' }}" name="account_name" value="{{ @$user->name }}" readonly required>
                                <small class="form-text text-muted">
                                  Nama rekening bank sesuai dengan nama KTP yang terdaftar di sistem
                                </small>
                                @if ($errors->has('account_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('account_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="account_number" class="col-md-4 col-form-label text-md-right">Bank Account Number</label>
                            <div class="col-md-6">
                                <input id="account_number" type="text" class="form-control{{ $errors->has('account_number') ? ' is-invalid' : '' }}" name="account_number" value="{{ @$user->bankAccount->account_number }}" required>
                                @if ($errors->has('account_number'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('account_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">
                                    SIMPAN
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