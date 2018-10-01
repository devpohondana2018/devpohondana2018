

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
<h1 class="center">Loan Details</h1>
<table class="export-pdf-pd">
        <tr>
            <th>Name</th>
            <th>Loan ID</th>
            <th>Amount borrowed</th>
            <th>Total Paid</th>
            <th>Balance</th>
        </tr>
@foreach($loans as $loan)
        <tr>
            <td>{{ $loan->name }}</td>
            <td>Loan {{ $loan->loansid }}</td>
            <td>{{ number_format($loan->amount_borrowed,2,',','.') }}</td>
            <td>{{ number_format($loan->totalpaid,2,',','.') }}</td>
            <td>{{ number_format($loan->amount_borrowed - $loan->totalpaid,2,',','.') }}</td>
        </tr>
@endforeach
        <tr>
            <td>Total</td>
            <td></td>
            <td>{{ number_format($loans->sum('amount_borrowed'),2,',','.') }}</td>
            <td>{{ number_format($loans->sum('totalpaid'),2,',','.') }}</td>
            <td>{{ number_format($loans->sum('amount_borrowed') - $loans->sum('totalpaid'),2,',','.') }}</td>
        </tr>
        
</table>