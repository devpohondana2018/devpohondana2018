

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
<h1 class="center">Installment Details</h1>
<table class="export-pdf-pd">
        <tr>
            <th>Installment ID</th>
            <th>Amount</th>
            <th>Balance</th>
            <th>Tenor</th>
            <th>Status</th>
            <th>Due date</th>
        </tr>
@foreach($installments as $installment)
        <tr>
            <td>installment {{ $installment->id }}</td>
            <td>{{ number_format($installment->amount,2,',','.') }}</td>
            <td>{{ number_format($installment->balance,2,',','.') }}</td>
            <td>{{ $installment->tenor }} / {{ $installment->month }}</td>
            <td>@if($installment->paid == 1) Paid @else Unpaid @endif</td>
            <td>{{ $installment->due_date }}</td>
        </tr>
@endforeach
        <tr>
            <td>Total</td>
            <td>{{ number_format($installments->sum('amount'),2,',','.') }}</td>
        </tr>
        
</table>