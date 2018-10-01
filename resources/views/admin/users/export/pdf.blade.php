

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
        
        font-size: 10px;
        padding: 5px; 
    }

    table.export-pdf-pd tr:nth-child(even) { 
        background: #eee;
    }

    table.export-pdf-pd tr {
        text-align: center;
    }
</style>
<h1 class="center">Users</h1>
<table class="export-pdf-pd">
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Role</th>
            <th>Email</th>
            <th>Active</th>
            <th>Verified</th>
            <th>BI Checking</th>
            <th>Created at</th>
        </tr>
@foreach($users as $user)
        <tr>
            <td>{{ $user->code }}</td>
            <td>{{ $user->name }}</td>
            @if(isset($user->roles[0]))
                <td>{{ $user->roles{0}->name }}</td>
            @else
                <td></td>
            @endif
            <td>{{ $user->email }}</td>
            <td>@if($user->active == 1) Active @else Inactive @endif</td>
            <td>@if($user->verified == 1) Verified @else Unverified @endif</td>
            <td>Collect x{{ $user->bi_checking }}</td>
            <td>{{ $user->created_at }}</td>
        </tr>
@endforeach
</table>