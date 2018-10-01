@extends('layouts.header-dashboard')

@section('content')
<div class="container center">
    @include('includes.notification') 
    <h1 class="mb-4 center">{{ $investCount }} Pendanaan</h1>
    <!-- <a href="{{ url('member/investments/download') }}" target="_blank" class="btn btn-sm btn-success text-center btn-download align-right"><i class="fa fa-download"></i> Download Portfolio</a> -->
    @include('includes.notification') 
    <div class="col-md-12 col-xs-12">
        <table class="data-tables-pohondana display responsive nowrap" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal Pendanaan:</th>
                    <th>Jumlah Pendanaan</th>
                    <th>Tenor</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
            @foreach($investments as $investment)
                <tr>
                    <td>{{$investment->code}}</td>
                    <td>{{$investment->created_at->format('d/m/Y')}}</td>
                    <td>Rp {{number_format($investment->amount_invested)}}</td>
                    <td>{{@$investment->loan->tenor->month}} bulan</td>
                    <td>
                        @if($investment->paid == 0)
                            @php $investment->status->name = 'Unpaid' @endphp
                        @endif
                        @if($investment->paid == 1 && $investment->status_id == 3)
                            @php $investment->status->name = 'In Repayment' @endphp
                        @endif
                        {{$investment->status->name}}
                    </td>
                    <td><a href="{{action('InvestmentController@show', $investment['id'])}}" class="btn btn-success btn-success-pohondana btn-sm">Lihat Detail</a></td>
                </tr>
            @endforeach
          </tbody>
        </table>
    </div>
</div>
@endsection