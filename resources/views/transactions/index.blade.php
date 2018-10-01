@extends('layouts.header-dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Riwayat Transaksi</h1>
    @include('includes.notification')

    @if($transactions->count() > 0)
    <div class="card text-left mb-3">
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Jumlah</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
          @foreach($transactions as $transaction)
          <tr>
            <td>{{$transaction->id}}</td>
            <td>{{number_format($transaction->amount,2)}}</td>
            <td>{{$transaction->created_at->format('d/m/Y')}}</td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{ $transactions->links("pagination::bootstrap-4") }}

</div>
@endsection