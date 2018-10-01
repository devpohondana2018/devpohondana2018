

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
        padding: 2px;
        font-size: 5px;
    }
    table.export-pdf-pd td {
        font-size: 5px;
        padding: 5px; 
    }

    table.export-pdf-pd tr:nth-child(even) { 
        background: #eee;
    }

    table.export-pdf-pd tr {
        text-align: center;
    }
</style>
<h1 class="center">Otoritas Jasa Keuangan</h1>
<table class="export-pdf-pd">
        <tr>
            <th>Rekening</th>
            <th>Nama Debitur</th>
            <th>Alamat</th>
            <th>Kode POS</th>
            <th>Nomor HP</th>
            <th>Telepon Rumah</th>
            <th>Nomor KTP/SIM</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>No NPWP</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Jatuh Tempo</th>
            <th>Jangka Waktu</th>
            <th>Original Balance</th>
            <th>Current Balance</th>
            <th>Interest Rate</th>
            <th>Total Pinjaman</th>
            <th>Tenor Cicilan</th>
            <th>Tanggal Angsuran</th>
            <th>Jumlah Angsuran</th>
        </tr>
@foreach($users as $user)
        <tr>
            <td>{{ decrypt($user->bank_account_number) }}</td>
            <!-- <td>{{ $user->company_name }}</td> -->
            <td>{{ $user->user_name }}</td>
            <td>{{ $user->user_address }}</td>
            <td>{{ $user->user_poscode }}</td>
            <td>{{ $user->user_mobile_phone }}</td>
            <td>{{ $user->user_home_phone }}</td>
            <td>{{ $user->user_id_no }}</td>
            <td>{{ $user->user_pob }}</td>
            <td>{{ $user->user_dob }}</td>
            <td>{{ $user->user_npwp_no }}</td>
            <td>{{ $user->loan_start_date }}</td>
            <td>{{ $user->loan_end_date }}</td>
            <td>{{ $user->loan_tenor }}</td>
            <td>{{ $user->original_balance }}</td>
            <td>{{ $user->original_balance - $user->current_balance }}</td>
            <td>{{ $user->interest_rate }}</td>
            <td>{{ $user->loan_total }}</td>
            <td>{{ $user->tenor }}</td>
            <td>{{ $user->due_date }}</td>
            <td>{{ $user->amount }}</td>
        </tr>
@endforeach
        <tr>
            <td>Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ number_format($users->sum('original_balance'),2,',','.')}}</td>
            <td></td>
            <td></td>
            <td>{{ number_format($users->sum('loan_total'),2,',','.')}}</td>
            <td></td>
            <td></td>
            <td>{{ number_format($users->sum('amount'),2 ,',','.')}}</td>
        </tr>
</table>