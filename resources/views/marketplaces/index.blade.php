@extends('layouts.header-dashboard')

@section('content')
<div class="container">

    @if(Auth::user()->verified == 0)
        <div class="alert alert-info" style="text-align: center;">
            <p>
                <strong>Informasi</strong><br>
                Akun Anda belum di verifikasi oleh Admin. Untuk menggunakan fitur ini akun Anda harus diverifikasi terlebih dahulu oleh Admin. Silahkan tunggu konfirmasi dari Admin. 
            </p>
        </div>
    @else

	<h1 class="text-left">Marketplace</h1>
	<p class="text-left">Cari pinjaman yang sesuai untuk pilihan pendanaan</p>

    @include('includes.notification')

    <div class="row">
    
        <div class="timeline-centered">

            <article class="timeline-entry timeline-entry-investment-amount">
                <div class="timeline-entry-inner">
                    <div class="timeline-icon bg-success">
                        <i class="entypo-suitcase"></i>
                    </div>
                    <div class="timeline-content">
                        <h2 class="timeline-header">Kriteria Pendanaan</h2>
                        <div class="timeline-body">
                            <p>Berapa jumlah maksimum pinjaman yang ingin Anda danai</p>
                            <div class="input-group">
                                <input type="text" name="amount_fundeds" class="form-control" placeholder="Jumlah">
                            </div>
                            <div class="input-group">
                                <p>Maksimal tenor pinjaman (bulan)</p>
                                <select name="filter_grade" class="form-control">
                                    @foreach($tenors as $id => $month)
                                    <option value="{{$id}}">{{$month}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="timeline-footer">
                            <button class="btn btn-sm btn-success btn-success-pohondana btn-next-investment-amount">SELANJUTNYA</button>
                        </div>
                    </div>
                </div>

            </article>

            <article class="timeline-entry timeline-entry-investment-criteria" style="display: none">
                <div class="timeline-entry-inner">
                    <div class="timeline-icon bg-success">
                        <i class="entypo-suitcase"></i>
                    </div>
                    <div class="timeline-content">
                        <h2 class="timeline-header">Kriteria Pendanaan</h2>
                        <div class="timeline-body">
                            
                        </div>
                        <div class="timeline-footer">
                            <button class="btn btn-sm btn-success btn-success-pohondana btn-next-investment-criteria">SELANJUTNYA</button>
                        </div>
                    </div>
                </div>

            </article>

            <article class="timeline-entry timeline-entry-investment-select-loan" style="display: none">
                <div class="timeline-entry-inner">
                    <div class="timeline-icon bg-success">
                        <i class="entypo-suitcase"></i>
                    </div>
                    <div class="timeline-content table-loan-content">
                        <h2 class="timeline-header">Pilih Pendanaan</h2>
                        <div class="timeline-body">
                            <p>
                                Pilih pendanaan yang sesuai dengan kriteria Anda
                            </p>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="data-tables-pohondana-marketplace display nowrap" width="100%">
                                    
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Total Pinjaman</th>
                                                <th>Sisa Pendanaan</th>
                                                <th>Tenor</th>
                                                <th>Grade</th>
                                                <th>Bunga</th>
                                                <th>Tanggal Batas Pendanaan</th>
                                                <th>Status Pendanaan</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody><!-- 
                                        @foreach($loans as $loan)
                                            <tr>
                                                <td>B-000{{$loan->id}}</td>
                                                <td>Rp {{number_format($loan->amount_total_calculated,2)}}</td>
                                                <td>Rp {{number_format($loan->amount_total_calculated - $loan->amount_funded,2)}}</td>
                                                <td>{{$loan->tenor->month}} bulan</td>
                                                <td>{{@$loan->grade->rank}}</td>
                                                <td>
                                                    @if($loan->interest_rate) {{$loan->interest_rate}}%
                                                    @else 0%
                                                    @endif
                                                </td>
                                                <td>{{round(($loan->amount_funded/$loan->amount_total)*100,2)}}%</td>
                                                <td><a href="{{route('marketplace.loan.view', $loan['id'])}}" class="btn btn-primary btn-sm">Lihat Detail</a></td>
                                            </tr>
                                        @endforeach -->
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-footer">
                            <!-- <button class="btn btn-sm btn-success">Selanjutnya</button> -->
                        </div>
                    </div>
                </div>

            </article>

            <article class="timeline-entry timeline-entry-investment-amount-value" style="display: none">
                <div class="timeline-entry-inner">
                    <div class="timeline-icon bg-success">
                        <i class="entypo-suitcase"></i>
                    </div>
                    <div class="timeline-content">
                        <h2 class="timeline-header">Jumlah Pendanaan</h2>
                        <div class="timeline-body">
                            <p>
                                Berapa jumlah pendanaan yang ingin Anda danai
                            </p>
                            <div class="form-group">
                                <input id="amount_invesment_value" type="text" class="form-control{{ $errors->has('amount_invesment_value') ? ' is-invalid' : '' }}" name="amount_invesment_value" value="{{ old('amount_invesment_value') }}" minlength="7" required autofocus>
                                <small>Minimum pendanaan Rp. 1.000.000</small>

                                @if ($errors->has('amount_invesment_value'))
                                <label id="amount_invesment_value-error" class="error help-block" for="amount_invesment_value">
                                    {{ $errors->first('amount_invesment_value') }}
                                </label>
                                @endif
                            </div>
                        </div>
                        <div class="timeline-footer">
                            <div class="btn btn-sm btn-success btn-success-pohondana btn-next-investment-amount-value">SELANJUTNYA</div>
                        </div>
                    </div>
                </div>

            </article>

            <article class="timeline-entry timeline-entry-finish" style="display: none">
                <div class="timeline-entry-inner">
                    <div class="timeline-icon bg-success">
                        <i class="entypo-suitcase"></i>
                    </div>
                    <div class="timeline-content invesment-finish">
                        <h2 class="timeline-header">Pembayaran</h2>
                        <div class="timeline-body">
                            <div class="payment">
                                <div class="payment-description">
                                    Mohon lakukan pembayaran ke Nomor Rekening dibawah ini
                                </div>
                                <div class="payment-image">
                                    <img src="https://upload.wikimedia.org/wikipedia/id/thumb/e/e0/BCA_logo.svg/1280px-BCA_logo.svg.png" height="30px">
                                </div>
                                <span></span>
                                <div class="payment-amount"></div>
                                <div class="va-number">No. Rek. 5455.650.272</div>
                                <div class="bank-owner">An: PT pohon dana indonesia</div>
                            </div>
                        </div>
                        <div class="timeline-footer">
                            <form method="POST" class="create-form-pd form-submit-loan" action="{{ route('marketplace.funding') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="uid">
                                <div class="input-group mb-2 mr-sm-2" style="display: none">
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
                                    <button type="submit" class="btn btn-sm btn-success btn-success-pohondana btn-investment-finish">
                                        FINISH
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </article>

            <article class="timeline-entry begin">
                <div class="timeline-entry-inner">
                    <div class="timeline-icon">
                        <i class="entypo-flight"></i> <i class="fa fa-check"></i>
                    </div>
                </div>
            </article>
        </div>
    </div>

    <div class="modal fade" id="modal-loan-detail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>Pinjaman <span id="load-id"></span></strong> <span class="badge badge-secondary"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                                Keperluan:<br>
                                                <span id="description"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card-body-detail">
                                        <div class="row nopadding">

                                            <div class="col-md-6">
                                                <dt>Total Pinjaman:</dt>
                                            </div>

                                            <div class="col-md-6">
                                                <dd id="amount_total">Rp</dd>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <dt>Sisa Pendanaan:</dt>
                                            </div>

                                            <div class="col-md-6">
                                                <dd id="amount_pending">Rp sisa pendanaan</dd>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <dt>Tanggal Pengajuan:</dt>
                                            </div>

                                            <div class="col-md-6">
                                                <dt id="created_at">tanggal pengajuan</dt>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <dt>Tenor:</dt> 
                                            </div>

                                            <div class="col-md-6">
                                                <dt id="tenor">tenor bulan</dt>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <dt>Bunga:</dt>
                                            </div>

                                            <div class="col-md-6">
                                                <dt id="interest_rate">
                                                    interest rate
                                                </dt>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <dt>Status Pinjaman:</dt>
                                            </div>

                                            <div class="col-md-6">
                                                <dd id="loan_status">status name</dd>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <dt>Status Pendanaan:</dt>
                                            </div>

                                            <div class="col-md-6 nopadding">
                                                <dd id="invest_status">status pendanaan%</dd>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                   <form method="POST" class="create-form-pd form-submit-loan" action="{{ route('marketplace.funding') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="uid">
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
            </div>
        </div>
    </div>
	
    @endif
</div>
@endsection

@section('javascript')
<script type="text/javascript" src="{{ asset('js/pohondana/customScriptMarketplace.js')}}"></script>
@endsection