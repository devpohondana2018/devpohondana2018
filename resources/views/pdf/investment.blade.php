<!DOCTYPE html>
<html>
	<head>
		<title>Invesment</title>

		<style>
			table {
			    border-collapse: collapse;
			    width: 100%;
			}

			th, td {
			    text-align: left;
			    padding: 8px;
			}

			tr:nth-child(even){background-color: #f2f2f2}

			th {
			    background-color: #4CAF50;
			    color: white;
			}
		</style>
	</head>
	<body>
		<h3 align="center">Laporan Pendanaan</h1>
		<table width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal Pendanaan:</th>
                    <th>Jumlah Pendanaan</th>
                    <th>Tenor</th>
                    <th>Bunga</th>
                    <th>Pengembalian Pendanaan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($investments as $investment)
                    <tr>
                        <td>I-000{{$investment->id}}</td>
                        <td>{{$investment->created_at->format('d/m/Y')}}</td>
                        <td>Rp {{number_format($investment->amount_invested)}}</td>
                        <td>{{$investment->loan->tenor->month}} bulan</td>
                        <td>{{$investment->loan->invest_rate == null ? '0' : $investment->loan->invest_rate}}%</td>
                        <td>Rp {{number_format( $investment->amount_invested + ( ($investment->amount_invested/$investment->loan->amount_requested)*$investment->loan->interest_fee ) ) }}</td>
                        <td>
                            @if($investment->status_id == 3)
                              @php 
                                $investment->status->name = $investment->paid == 1 ? 'In repayment' : 'Delinquent'
                              @endphp
                            @endif
                            @switch($investment->status->name)
                                @case('In repayment')
                                    @php $labelType = 'success' @endphp
                                    @break
                                @case('Delinquent')
                                    @php $labelType = 'danger' @endphp
                                    @break
                                @case('Cancelled')
                                    @php $labelType = 'warning' @endphp
                                    @break
                                @case('Completed')
                                    @php $labelType = 'primary' @endphp
                                    @break
                                @default
                                    @php $labelType = 'default' @endphp
                                    @break
                            @endswitch
                            <span class="label label-{{ $labelType }}">{{$investment->status->name}}</span>
                        </td>
                    </tr>
                @endforeach
              </tbody>
		</table>
	</body>
</html>