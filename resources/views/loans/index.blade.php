@extends('layouts.header-dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Riwayat Pinjaman</h1>
    @include('includes.notification')
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <table class="data-tables-pohondana display responsive nowrap" width="100%">
            
                <thead>
                    <tr>
                        <th>ID:</th>
                        <th>Total Pinjaman:</th>
                        <th>Status Pinjaman:</th>
                        <th>Tenor:</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                
                <tbody>
                @foreach($loans as $loan)
                    <tr>
                        <td>B-000{{$loan->id}}</td>
                        <td>Rp {{number_format($loan->amount_total_calculated,2)}}</td>
                        <td>{{$loan->status->name}}</td>
                        <td>{{$loan->tenor->month}} bulan</td>
                        <td>{{$loan->created_at->format('d/m/Y')}}</td>
                        <td><a href="{{action('LoanController@show', $loan['id'])}}" class="btn btn-primary btn-sm">Lihat Detail</a></td>
                    </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>

    {{ $loans->links("pagination::bootstrap-4") }}

</div>
@endsection