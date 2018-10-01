

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
<h1 class="center">Transactions</h1>
<table class="export-pdf-pd">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Transaction</th>
            <th>Status</th>
            <th>Created at</th>
        </tr>
@foreach($transactions as $transaction)
        <tr>
            <td>{{ $transaction->id }}</td>
            <td>{{ $transaction->username }}</td>
            <td>{{ number_format($transaction->amount, 2) }}</td>
            <td>{{ $transaction->type }}</td>
            <td>{{ str_replace('App\\', '', $transaction->transactionable_type) }}</td>
            <td>{{ $transaction->status }}</td>
            <td>{{ $transaction->created_at }}</td>
        </tr>
@endforeach
</table>