

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
        font-size: 8px;
    }
    table.export-pdf-pd td {
        font-size: 8px;
        padding: 3px; 
    }

    table.export-pdf-pd tr:nth-child(even) { 
        background: #eee;
    }

    table.export-pdf-pd tr {
        text-align: center;
    }
</style>
<h1 class="center">Installments</h1>
<table class="export-pdf-pd">
        <tr>
            <th>Installment ID</th>
            <th>Nama / Alamat</th>
            <th>Perusahaan Rekanan</th>
            <th>No Rekening</th>
            <th>Jangka Waktu</th>
            <th>Jatuh Tempo</th>
            <th>Plafond</th>
            <th>Saldo Akhir</th>
            @php
                if ($_GET['installmentable_type'] == 'App\Loan') {
                    echo '
                    <th>Pokok Angsuran</th>
                    <th>Bunga Angsuran</th>
                    ';
                }
            @endphp
            <th>Total Angsuran</th>
            <th>Status Pembayaran</th>
        </tr>
        @php
            $amountTotal = 0;
        @endphp
        @foreach($installments as $installment)
        <tr>
            <td>{{ $installment->code }}</td>
            <td>{{ $installment->username }} / {{ $installment->home_address }}</td>
            <td>{{ $installment->companyname }}</td>
            <td>{{ decrypt($installment->bank_account) }}</td>
            <td>{{ $installment->tenor }} / {{ $installment->totaltenor }}</td>
            <td>{{ $installment->due_date }}</td>
            <td>{{ number_format($installment->amount_total,2,',','.') }}</td>
            <td>{{ number_format($installment->balance,2,',','.') }}</td>
            @php
                if ($_GET['installmentable_type'] == 'App\Loan') {
                    echo '<td>'; @endphp
                        {{ number_format((!empty($installment) ? ($installment->amount_requested / $installment->month) : ""),2,',','.') }}
                        @php
                    echo '</td><td>';
                        @endphp
                        {{ number_format($installment->amount - (!empty($installment) ? ($installment->amount_requested / $installment->month) : ""),2,',','.') }}
                        @php
                    echo '</td>';
                }
            @endphp
            <td>{{ number_format($installment->amount,2,',','.') }}</td>
            <td>@if($installment->paid == 1) Paid @else Unpaid @endif</td>
        </tr>
        @php
        $amountTotal += $installment->amount;
        @endphp
        @endforeach

        <tr>
            <td></td>
            <td>Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
             @php
                if ($_GET['installmentable_type'] == 'App\Loan') {
	            echo '<td>'; @endphp
	            {{ number_format($amountTotal - $installments->sum('installment_rate'),2,',','.') }}@php
                echo '</td><td>';
                	@endphp
                {{ number_format($installments->sum('installment_rate'),2,',','.') }}
                @php
            	echo '</td>';
            	}
           	@endphp
            <td>{{ number_format($amountTotal,2,',','.') }}</td>
        </tr>
</table>