

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
        font-size: 14px;
    }
    table.export-pdf-pd td {
        font-size: 10px;
        padding: 2px; 
    }

    table.export-pdf-pd tr:nth-child(even) { 
        background: #eee;
    }

    table.export-pdf-pd tr {
        text-align: center;
    }
</style>
<h1 class="center">Activity Logs</h1>


    <table class="export-pdf-pd">
            <tr>
                <th>User</th>
                <th>Related Document</th>
                <th>Related User</th>
                <th>Activity</th>
                <th>Properties</th>
                <!-- <th>Value</th> -->
                <th>Created at</th>
                <th>Updated at</th>
            </tr>
    @foreach($activitylogs as $activitylog)
            <tr>
                <td>{{ !empty($activitylog->username) ? $activitylog->username : "" }} </td>
                <td>{{ !empty($activitylog->related_document) ? $activitylog->related_document : "" }}</td>
                <td>{{ !empty($activitylog->related_user) ? $activitylog->related_user : "" }}</td>
                <td>{{ !empty($activitylog->description) ? $activitylog->description : "" }}</td>
                <td style="text-align: left;">
                    @foreach($activitylog->key as $keyValue)
                        {{ $keyValue }} <br>
                    @endforeach
                </td>
                <!-- <td>User</td> -->
                <!-- <td style="text-align: left;">
                    @foreach($activitylog->value as $keyValue)
                        {{ $keyValue }} <br>
                    @endforeach
                </td> -->
                <td>{{ !empty($activitylog->created_at) ? $activitylog->created_at : "" }}</td>
                <td>{{ !empty($activitylog->updated_at) ? $activitylog->updated_at : "" }}</td>
            </tr>
    @endforeach
    </table