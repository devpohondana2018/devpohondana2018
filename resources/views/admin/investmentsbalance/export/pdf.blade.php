

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
<h1 class="center">Pendanaan Balances</h1>
<table class="export-pdf-pd">
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Total Pendanaan</th>
            <th>Total Loan Paid</th>
            <th>Balance</th>
        </tr>
@foreach($users as $user)
        <tr>
            <td>{{ $user->userid }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ number_format($user->amount_invested,2,',','.') }}</td>
            <td>{{ number_format($user->totalpaid,2,',','.') }}</td>
            <td>{{ number_format($user->balance,2,',','.') }}</td>
        </tr>
@endforeach
        <tr>
            <td>Total</td>
            <td></td>
            <td>{{ number_format($users->sum('amount_invested'),2,',','.') }}</td>
            <td>{{ number_format($users->sum('totalpaid'),2,',','.') }}</td>
            <td>{{ number_format($users->sum('balance'),2,',','.') }}</td>
        </tr>
        
</table>