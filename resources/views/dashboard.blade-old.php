@extends('layouts.header-dashboard')

@section('content')

@include('includes.notification')

@role('borrower')
<h1 class="mb-4">Status Pinjaman Saya</h1>
<div class="row">
	<div class="col-md-8">

		@if(count($pending_loans) > 0)
		<div class="card card-notice mb-4">
			<div class="card-body">
				<p>Anda mempunyai {{count($pending_loans)}} pinjaman yang sedang dalam proses persetujuan:</p>
				<ul>
				@foreach($pending_loans as $loan)
					<li><p style="font-size:14px;font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
							Pinjaman B-000{{$loan->id}}, diajukan pada tanggal {{$loan->created_at->format('d F Y')}}
						</a></p>
					</li>
				@endforeach
				</ul>
				<p>Anda akan menerima email notifikasi ketika pengajuan pinjaman sudah selesai diproses.</p>
			</div>
		</div>
		@endif

		@if(count($approved_loans) > 0)
		<div class="card card-notice mb-4">
			<div class="card-body">
				<p>Selamat! Anda mempunyai {{count($approved_loans)}} pinjaman yang sudah mendapat persetujuan:</p>
				<ul>
				@foreach($approved_loans as $loan)
					<li><p style="font-size:14px;font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
							Pinjaman B-000{{$loan->id}}, diajukan pada tanggal {{$loan->created_at->format('d F Y')}}
						</a></p>
					</li>
				@endforeach
				</ul>
				<p>Anda harus menyetujui pinjaman yang sudah disetujui sebelum dana ditransfer ke rekening Anda.</p>
			</div>
		</div>
		@endif

		@if(count($accepted_loans) > 0)
		<div class="card card-notice mb-4">
			<div class="card-body">
				<p>Anda mempunyai {{count($accepted_loans)}} pinjaman yang aktif:</p>
				<ul>
				@foreach($accepted_loans as $loan)
					<li><p style="font-size:14px;font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
							Pinjaman B-000{{$loan->id}}, diajukan pada tanggal {{$loan->created_at->format('d F Y')}}
						</a></p>
					</li>
				@endforeach
				</ul>
				<p>Rincian cicilan yang harus dibayarkan dapat dilihat secara lebih detil di informasi pinjaman Anda.</p>
			</div>
		</div>
		@endif

		@if(count($rejected_loan) > 0)
		<div class="card card-notice mb-4">
			<div class="card-body">
				<p>Anda mempunyai {{count($rejected_loan)}} pinjaman yang ditolak:</p>
				<ul>
				@foreach($rejected_loan as $loan)
					<li><p style="font-size:14px;font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
							Pinjaman B-000{{$loan->id}}
						</a></p>
					</li>
				@endforeach
				</ul>
			</div>
		</div>
		@endif

		@if(count($declined_loan) > 0)
		<div class="card card-notice mb-4">
			<div class="card-body">
				<p>Anda telah menolaj {{count($declined_loan)}} pinjaman:</p>
				<ul>
				@foreach($declined_loan as $loan)
					<li><p style="font-size:14px;font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
							Pinjaman B-000{{$loan->id}}
						</a></p>
					</li>
				@endforeach
				</ul>
			</div>
		</div>
		@endif

		@if(count($completed_loan) > 0)
		<div class="card card-notice mb-4">
			<div class="card-body">
				<p>Anda mempunyai {{count($completed_loan)}} pinjaman yang sudah selesai:</p>
				<ul>
				@foreach($completed_loan as $loan)
					<li><p style="font-size:14px;font-weight:500;margin-bottom:0;"><a href="{{url('member/loans/'.$loan->id)}}">
							Pinjaman B-000{{$loan->id}}
						</a></p>
					</li>
				@endforeach
				</ul>
			</div>
		</div>
		@endif
			
	</div>
</div>
@endrole

@role('lender')
<div class="container dashboard-pd">

	<div class="col-md-6">
		<div class="row marbot-50">
			<div class="col-md-12">
				<div class="box-dashboard-pd">
					<div class="col-md-12 nopadding center" style="border-bottom: 1px solid #d9d9d9; margin-bottom: 15px;">
						<h1>Laporan Pendanaan</h1>
					</div>
					<div class="col-md-12 nopadding box-dashboard-pd-desc">
						<div class="col-md-3">
							<p>{{ $paidInvestment }}</p>
							<p><a href="https://pohondana.id/member/investments">In repayment</a></p>
						</div>
						<div class="col-md-3">
							<p>{{ $canceledInvestment }}</p>
							<p><a href="https://pohondana.id/member/investments">Matured</a></p>
						</div>
						<div class="col-md-3">
							<p>{{ $unpaidInvestment }}</p>
							<p><a href="https://pohondana.id/member/investments">Delinquent</a></p>
						</div>
						<div class="col-md-3">
							<p style="margin-bottom: 0px;">{{ $completedInvestment }}</p>
							<p><a href="https://pohondana.id/member/investments">Charged off</a></p>
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
						<h1>ACCOUNT OVERVIEW</h1>
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

@endsection