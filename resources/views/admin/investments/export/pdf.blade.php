

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
        padding: 5px;
    }
    table.export-pdf-pd td {
        font-size: 12px;
        padding: 5px; 
    }

    table.export-pdf-pd tr:nth-child(even) { 
        background: #eee;
    }

    table.export-pdf-pd tr {
        text-align: center;
    }
</style>
<h1 class="center">Pendanaan</h1>
<table class="export-pdf-pd">
        <tr>
            <th>Invest ID</th>
            <th>User</th>
            <th>Loan ID</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Tanggal Pendanaan</th>
        </tr>
@foreach($investments as $investment)
        <tr>
            <td>{{ $investment->code }}</td>
            <td>{{ $investment->name }}</td>
            <td>{{ $investment->loan_id }}</td>
            <td>{{ number_format($investment->amount_invested, 2) }}</td>
            <td>{{ ucfirst($investment->status_name) }}</td>
            <td>{{ $investment->created_at }}</td>
        </tr>
@endforeach
        <tr>
            <td colspan="6" style="font-size: 16px; font-weight: bold;">Total Rp. {{ number_format($investments->sum('amount_invested'),2 )}}</td>
        </tr>
</table>