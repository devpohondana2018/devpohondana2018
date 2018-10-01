

<style type="text/css">
    .center {
        text-align: center;
    }

    table {
        width: 100%;
    }
    table.export-pdf-pd th {
        font-weight: bold;
        margin-right: 15px;
        border-bottom: 1px solid;
        padding: 3px;
        font-size: 12px;
    }
    table.export-pdf-pd td {
        font-size: 10px;
        padding: 3px; 
    }

    table.export-pdf-pd tr:nth-child(even) { 
        background: #eee;
    }

    table.export-pdf-pd tr {
        text-align: center;
    }
</style>
<h1 class="center">Loan Profits</h1>
<table class="export-pdf-pd">
        <tr>
            <th>Loan ID</th>
            <th>User</th>
            <th>Grade</th>
            <th>Basic Loan</th>
            <th>Interest Rate</th>
            <th>Interest Amount</th>
            <th>Platform Rate</th>
            <th>Platform Amount</th>
            <th>Pendanaan Rate</th>
            <th>Pendanaan Amount</th>
            <!-- <th>Profit Rate</th> -->
            <th>Profit Amount</th>
        </tr>
@foreach($loans as $loan)
        <tr>
            <td>{{ $loan->id }}</td>
            <td>{{ $loan->username }}</td>
            <td>{{ $loan->grade }}</td>
            <td>{{ number_format($loan->amount_requested,2,',','.') }}</td>
            <td>{{ $loan->interest_rate }}</td>
            <td>{{ number_format($loan->interest_fee,2,',','.') }}</td>
            <td>{{ $loan->provision_rate }}</td>
            <td>{{ number_format($loan->provision_fee,2,',','.') }}</td>
            <td>{{ $loan->invest_rate }}</td>
            <td>{{ number_format($loan->invest_fee,2,',','.') }}</td>
            <td>{{ number_format(($loan->interest_fee + $loan->provision_fee - $loan->invest_fee),2,',','.') }}</td>
        </tr>
@endforeach
        <tr>
            <td></td>
            <td>Total</td>
            <td></td>
            <td>{{ number_format($loans->sum('amount_requested'),2,',','.') }}</td>
            <td></td>
            <td>{{ number_format($loans->sum('interest_fee'),2,',','.') }}</td>
            <td></td>
            <td>{{ number_format($loans->sum('provision_fee'),2,',','.') }}</td>
            <td></td>
            <td>{{ number_format($loans->sum('invest_fee'),2,',','.') }}</td>
            <!-- <td></td> -->
            <td>{{ number_format($loans->sum('interest_fee') + $loans->sum('provision_fee') - $loans->sum('invest_fee'),2,',','.') }}</td>
        </tr>
</table>