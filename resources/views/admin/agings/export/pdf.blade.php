

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
<h1 class="center">Agings</h1>
<table class="export-pdf-pd">
        <tr>
            <th>Name</th>
            <th>0 - 30</th>
            <th>31 - 60</th>
            <th>61 - 90</th>
            <th>> 91</th>
            <th>Total</th>
        </tr>
@foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ number_format($user->zerotothirty,2,',','.') }}</td>
            <td>{{ number_format($user->thirtyonetosixty,2,',','.') }}</td>
            <td>{{ number_format($user->sixtyonetoninety,2,',','.') }}</td>
            <td>{{ number_format($user->morethanninetyone,2,',','.') }}</td>
            <td>{{ number_format($user->total,2,',','.') }}</td>
        </tr>
@endforeach
        <tr>
            <td>Total</td>
            <td>{{ number_format($users->sum('zerotothirty'),2,',','.') }}</td>
            <td>{{ number_format($users->sum('thirtyonetosixty'),2,',','.') }}</td>
            <td>{{ number_format($users->sum('sixtyonetoninety'),2,',','.') }}</td>
            <td>{{ number_format($users->sum('morethanninetyone'),2,',','.') }}</td>
            <td>{{ number_format($users->sum('total'),2,',','.') }}</td>
        </tr>
        
</table>