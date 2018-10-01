<div class="box box-default">
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-xs-4">
                <div class="btn-group">
                    <a class="btn btn-sm btn-twitter"> Company</a>
                    <button type="button" class="btn btn-sm btn-twitter dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/admin/reports/users?report_=1">All</a></li>
                        <li><a href="/admin/reports/users?report_=1&company=rekanan">Rekanan</a></li>
                        <li><a href="/admin/reports/users?report_=1&company=nonrekanan" class="export-selected">Non Rekanan</a></li>
                    </ul>
                    <input class="btn btn-sm" placeholder="Select Due Days" style="border: 1px solid #d9d9d9;margin-left: 2em;" type="date" name="days">
                </div>
            </div>
            <div class="col-sm-2 col-xs-2">
                <select name="company" class="form-control hidden">
                    <option value="" disabled selected="true">Filter by Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" >{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="float: right;text-align: right;">
            @php
            if (isset($_GET['company_id'])) {
                $company = $_GET['company_id'];
            }else{
                $company = 0;
            }
            if (isset($_GET['company'])) {
                $companies = $_GET['company'];
            }else{
                $companies = 0;
            }
            if (isset($_GET['days'])) {
                $days = $_GET['days'];
            }else{
                $days = 0;
            }
            @endphp
            <a href="users/pdf?company_id={{ $company }}&company={{ $companies }}&days={{ $days }}" target="_blank" class="btn btn-sm btn-success grid-refresh"><i class="fa fa-download"></i> Export PDF</a>
            <a href="users/excel?company_id={{ $company }}&company={{ $companies }}&days={{ $days }}" target="_blank" class="btn btn-sm btn-primary grid-refresh"><i class="fa fa-download"></i> Export Excel</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <th>No</th>
                    <!-- <th>Bank</th> -->
                    <th>Account Number</th>
                    <th>Perusahaan</th>
                    <th>Debitur Name</th>
                    <th>Address</th>
                    <th>Postal Code</th>
                    <th>Mobile Phone</th>
                    <th>Home Phone</th>
                    <th>ID</th>
                    <th>POB</th>
                    <th>DOB</th>
                    <th>NPWP Number</th>
                    <!-- <th>No Rekening</th> -->
                    <th>Start date</th>
                    <th>End Date</th>
                    <th>Total Tenor</th>
                    <th>Original Balance</th>
                    <th>Current Balance</th>
                    <th>Interest Rate</th>
                    <th>Total Borrowed</th>
                    <th>Tenor</th>
                    <th>Amount</th>
                    <th>Installment Due Date</th>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        @php
                            $dateFormat = 'Y-m-d';
                            $dueDateArray = array();
                            $dueDate = \Carbon\Carbon::now();
                            $dueDateTimeStamp = \Carbon\Carbon::createFromFormat('Y-m-d', $user->due_date);

                            $dueDateArray['h0'] = $dueDate->format($dateFormat);
                            $dueDateArray['h3'] = $dueDate->addDays(3)->format($dateFormat);
                            $dueDateArray['h7'] = $dueDate->addDays(4)->format($dateFormat);
                        @endphp
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <!-- <td>{{$user->bank_name}}</td> -->
                            <td>{{decrypt($user->bank_account_number)}}</td>
                            @if($user->affiliate == 1)
                                <td>{{$user->company_name}}</td>
                            @else
                                <td>Non Rekanan</td>
                            @endif
                            <td>{{$user->user_name}}</td>
                            <td>{{$user->user_address}}</td>
                            <td>{{$user->user_poscode}}</td>
                            <td>{{$user->user_mobile_phone}}</td>
                            <td>{{$user->user_home_phone}}</td>
                            <td>{{$user->user_id_no}}</td>
                            <td>{{$user->user_pob}}</td>
                            <td>{{$user->user_dob}}</td>
                            <td>{{$user->user_npwp_no}}</td>
                            <!-- <td>{{$user->no_rek}}</td> -->
                            <td>{{$user->loan_start_date}}</td>
                            <td>{{$user->loan_end_date}}</td>
                            <td>{{$user->loan_tenor}}</td>
                            <td>{{number_format($user->original_balance,2,",",".")}}</td>
                            <td>{{number_format($user->original_balance - $user->current_balance,2,",",".")}}</td>
                            <td>{{$user->interest_rate}}</td>
                            <td>{{number_format($user->loan_total,2,",",".")}}</td>
                            <td>{{$user->tenor}}</td>
                            <td>{{number_format($user->amount,2,",",".")}}</td>
                            <td>
                            @if( ($user->due_date == $dueDateArray['h0'] && $user->due_date <= $dueDateArray['h3']) ||  $user->due_date < $dueDateArray['h0'])
                                <span class="label label-danger">{{$user->due_date}}</span>
                            @elseif($user->due_date >= $dueDateArray['h3'] && $user->due_date < $dueDateArray['h7'])
                                <span class="label label-warning">{{$user->due_date}}</span>
                            @elseif ($user->due_date >= $dueDateArray['h7'])
                                <span class="label label-success">{{$user->due_date}}</span>
                            @else
                                <span class="label label-success">{{$user->due_date}}</span>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>

<script>
    $(document).ready(function($) {
        var company = getUrlParameter('company');
        var companyId = getUrlParameter('company_id');
        if (company == 'rekanan' || companyId != null) {
            $('select[name="company"]').removeClass('hidden');
        }
        $('select[name="company"]').change(function(){
            window.location.href = window.location.href + "?&company_id=" + $(this).val();
        });
        $('input[name="days"]').change(function(){
            window.location.href = window.location.href + '?&days=' + $(this).val();
        });
    });

    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };
</script>