@extends('layouts.header-dashboard')

@section('content')

@include('includes.notification')
<div class="container dashboard-pd">
@role('borrower')

<div class="dashboard-pd-borrower">

	<div class="col-md-6 nopadding">

		<div class="col-md-12">
			<h1 class="mb-4">Status Pinjaman Saya</h1>
		</div>
		@if(count($pending_loans) > 0)
		<div class="col-md-12 col-xs-12">
			<div class="card card-notice mb-4">
				<div class="card-body">
					<p>Anda mempunyai {{count($pending_loans)}} pinjaman yang sedang dalam proses persetujuan:</p>
					<ul>
					@foreach($pending_loans as $loan)
						<li><p style="font-weight:500;margin-bottom:0;"><a onclick="actionLoan(this)" href="{{url('member/loans/'.$loan->id)}}">
								Pinjaman B-000{{$loan->id}}, diajukan pada tanggal {{$loan->created_at->format('d F Y')}}
							</a></p>
						</li>
					@endforeach
					</ul>
					<p>Anda akan menerima email notifikasi ketika pengajuan pinjaman sudah selesai diproses.</p>
				</div>
			</div>
		</div>
		@endif

		@if(count($approved_loans) > 0)
		<div class="col-md-12 col-xs-12">
			<div class="card card-notice mb-4">
				<div class="card-body">
					<p>Selamat! Anda mempunyai {{count($approved_loans)}} pinjaman yang sudah mendapat persetujuan:</p>
					<ul>
					@foreach($approved_loans as $loan)
						<li><p style="font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
								Pinjaman B-000{{$loan->id}}, diajukan pada tanggal {{$loan->created_at->format('d F Y')}}
							</a></p>
						</li>
					@endforeach
					</ul>
					<p>Anda harus menyetujui pinjaman yang sudah disetujui sebelum dana ditransfer ke rekening Anda.</p>
				</div>
			</div>
		</div>
		@endif

		@if(count($accepted_loans) > 0)
		<div class="col-md-12 col-xs-12">
			<div class="card card-notice mb-4">
				<div class="card-body">
					<p>Anda mempunyai {{count($accepted_loans)}} pinjaman yang aktif:</p>
					<ul>
					@foreach($accepted_loans as $loan)
						<li><p style="font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
								Pinjaman B-000{{$loan->id}}, diajukan pada tanggal {{$loan->created_at->format('d F Y')}}
							</a></p>
						</li>
					@endforeach
					</ul>
					<p>Rincian cicilan yang harus dibayarkan dapat dilihat secara lebih detil di informasi pinjaman Anda.</p>
				</div>
			</div>
		</div>
		@endif

		@if(count($rejected_loan) > 0)
		<div class="col-md-12 col-xs-12">
			<div class="card card-notice mb-4">
				<div class="card-body">
					<p>Anda mempunyai {{count($rejected_loan)}} pinjaman yang ditolak:</p>
					<ul>
					@foreach($rejected_loan as $loan)
						<li><p style="font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
								Pinjaman B-000{{$loan->id}}
							</a></p>
						</li>
					@endforeach
					</ul>
				</div>
			</div>
		</div>
		@endif

		@if(count($declined_loan) > 0)
		<div class="col-md-12 col-xs-12">
			<div class="card card-notice mb-4">
				<div class="card-body">
					<p>Anda telah menolak {{count($declined_loan)}} pinjaman:</p>
					<ul>
					@foreach($declined_loan as $loan)
						<li><p style="font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
								Pinjaman B-000{{$loan->id}}
							</a></p>
						</li>
					@endforeach
					</ul>
				</div>
			</div>
		</div>
		@endif

		@if(count($completed_loan) > 0)
		<div class="col-md-12 col-xs-12">
			<div class="card card-notice mb-4">
				<div class="card-body">
					<p>Anda mempunyai {{count($completed_loan)}} pinjaman yang sudah selesai:</p>
					<ul>
					@foreach($completed_loan as $loan)
						<li><p style="font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
								Pinjaman B-000{{$loan->id}}
							</a></p>
						</li>
					@endforeach
					</ul>
				</div>
			</div>
		</div>
		@endif
	</div>	

	<div class="col-md-6">
		@if(count($accepted_loans) > 0)
		<div class="col-md-12">
			<h1 class="mb-4">Informasi Pinjaman</h1>
		</div>
		@endif
        
        @foreach($accepted_loans as $accepted_loan)
        <div class="card col-md-12 mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <strong>Pinjaman #{{$accepted_loan->id}}</strong>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>{{$accepted_loan->description}}</p>
                    </div>
                </div>
                <div class="card-body-detail">
                    <div class="row nopadding">

                        <div class="col-md-6">
                            <dt>Pokok Pinjaman:</dt>
                        </div>

                        <div class="col-md-6">
                            <dd>Rp {{number_format($accepted_loan->amount_requested,2)}}</dd>
                        </div>
                    </div>

                    <div class="row nopadding">

                        <div class="col-md-6">
                            <dt>Bunga Pinjaman:</dt>
                        </div>

                        <div class="col-md-6">
                            <dd>Rp {{number_format($accepted_loan->interest_fee,2)}}</dd>
                        </div>
                    </div>

                    <div class="row nopadding">

                        <div class="col-md-6">
                            <dt>Pokok + Bunga:</dt>
                        </div>

                        <div class="col-md-6">
                            <dd>Rp {{number_format($accepted_loan->amount_total,2)}}</dd>
                        </div>
                    </div>

                    <div class="row nopadding">

                        <div class="col-md-6">
                            <dt>Platform Fee:</dt>
                        </div>

                        <div class="col-md-6">
                            <dd>Rp {{number_format($accepted_loan->provision_fee,2)}}</dd>
                        </div>
                    </div>

                    <div class="row nopadding">

                        <div class="col-md-6">
                            <dt>Balance Pinjaman:</dt>
                        </div>

                        <div class="col-md-6">
                            <dd>Rp {{number_format( $accepted_loan->balance ,2)}}</dd>
                        </div>
                    </div>

                    <hr>

                    <div class="row nopadding">
                        <div class="col-md-6">
                            <dt>Tanggal Pengajuan:</dt>
                        </div>

                        <div class="col-md-6">
                            <dt>{{$accepted_loan->created_at->format('d/m/Y')}}</dt>
                        </div>
                    </div>

                    <div class="row nopadding">
                        <div class="col-md-6">
                            <dt>Tenor Pinjaman:</dt>
                        </div>

                        <div class="col-md-6">
                            <dt>{{$accepted_loan->tenor->month}} bulan</dt>
                        </div>
                    </div>

                    <div class="row nopadding">
                        <div class="col-md-6">
                            <dt>Rate Bunga Pinjaman:</dt>
                        </div>

                        <div class="col-md-6">
                            <dt>{{@$accepted_loan->interest_rate}}%</dt>
                        </div>
                    </div>

                    <div class="row nopadding">
                        <div class="col-md-6">
                            <dt>Rate Pendana:</dt>
                        </div>

                        <div class="col-md-6">
                            <dt>{{@$accepted_loan->invest_rate}}%</dt>
                        </div>
                    </div>

                    <div class="row nopadding">

                        <div class="col-md-6">
                            <dt>Total Pendanaan:</dt>
                        </div>

                        <div class="col-md-6">
                            <dd>Rp {{number_format( $accepted_loan->amount_funded,2)}}</dd>
                        </div>
                    </div>

                    <hr>

                </div>
            </div>

            <div class="card-footer">
                @if($accepted_loan->status_id == '2')
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6 center">
                        <!--  -->
                        <a href="{{action('LoanController@acceptLoan', $accepted_loan['id'])}}" class="btn-approve btn-action-approve bold">Setujui Pinjaman</a>
                        <!-- <a href="#" class="btn-approve btn-action-approve bold">Setujui Pinjaman</a> -->
                    </div>

                    <div class="col-md-6 center">
                        <!--  -->
                        <a href="{{action('LoanController@declineLoan', $accepted_loan['id'])}}" class="btn-decline btn-action-decline bold">Tolak Pinjaman</a>
                        <!-- <a href="#" class="btn-decline btn-action-decline bold">Tolak Pinjaman</a> -->
                    </div>
                </div>

                @endif 
                @if($loan->status_id == '3')
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{url('member/loans/doc/'.$accepted_loan['id'])}}" class="btn-approve bold" target="_blank">Lihat Dokumen Pinjaman</a>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <p style="margin-bottom: 0px;">Keterangan status pinjaman:</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p><strong>{{$accepted_loan->status->description}}</strong></p>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </div>

@endrole

@role('lender')

	<div class="col-md-6">
		<div class="row marbot-50">
			<div class="col-md-12">
				<div class="box-dashboard-pd">
					<div class="col-md-12 nopadding center" style="border-bottom: 1px solid #d9d9d9; margin-bottom: 15px;">
						<h1>Laporan Pendanaan</h1>
					</div>
					<div class="col-md-12 nopadding box-dashboard-pd-desc">
						<div class="col-md-4">
							<p style="margin-bottom: 0px;">{{ $unpaidInvestment }}</p>
							<p>Unpaid</p>
						</div>
						<div class="col-md-4">
							<p style="margin-bottom: 0px;">{{ $paidInvestment }}</p>
							<p>In repayment</p>
						</div>
						<div class="col-md-4">
							<p style="margin-bottom: 0px;">{{ $completedInvestment }}</p>
							<p>Completed</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		
	</div>

	<div class="col-md-6 sidebar-pd">
		<div class="row">
			<div class="col-md-12">
				<div class="sidebar-desc-pd">
					<div class="col-md-12 center" style="border-bottom: 1px solid #d9d9d9; margin-bottom: 15px;">
						<h1>Account Overview</h1>
					</div>
					<div class="col-md-12">
						<p>{{ $investmentTotal }}</p>
						<p>Total account value</p>
					</div>
					<div class="col-md-12">
						<p>{{ $pendingRefunded }}</p>
						<p>Outstanding Principal</p>
					</div>
					<div class="col-md-12">
						<p>{{ $investmentTotalRefund }}</p>
						<p>Total repayment</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endrole
</div>
@endsection

<script>
	
</script>