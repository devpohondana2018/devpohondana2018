

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
<h1 class="center">Loans</h1>
<table class="export-pdf-pd">
        <tr>
            <th>Loan ID</th>
            <th>Peminjam</th>
            <th>Pokok Pinjaman</th>
            <th>Tenor</th>
            <th>Grade</th>
            <th>Status</th>
            <th>Accepted at</th>
            <th>Created at</th>
        </tr>
@foreach($loans as $loan)
        <tr>
            <td>{{ $loan->code }} </td>
            <td>{{ $loan->user->name }} </td>
            <td>Rp. {{ number_format(round($loan->amount_requested), 2,',','.') }}</td>
            <td>{{ $loan->tenor->month }}</td>
            <td>{{ empty($loan->grade) ? '' : $loan->grade->rank }}</td>
            <td>{{ $loan->status->name == 'Pending' ? ($loan->user->verified == 1 ? 'Verified' : 'Unverified')  : $loan->status->name }}</td>
            <td>{{ $loan->accepted_at }}</td>
            <td>{{ $loan->created_at }}</td>
        </tr>
@endforeach
        <tr>
            <td colspan="8" style="font-size: 16px; font-weight: bold;">Total Rp. {{ number_format(round($loans->sum('amount_requested')),2,',','.')}}</td>
        </tr>
</table>