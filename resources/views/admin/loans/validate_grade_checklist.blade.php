<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Validate Loan Grade Checklist</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>

    @php
        $user = $loan->user;
    @endphp

    <!-- /.box-header -->
    <div class="box-body">
        <h4>Loan ID: {{$loan->code}}</h4>
        <h4>User: <a href="{{url('admin/users/'.$user->id)}}">{{$user->name}}</a></h4>
        <h4>User Type: {{$user->type}}</h4>
        <h4>Suggested Loan Grade: {{@$loan->validateGrade()->rank}}</h4>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <th>Parameter</th>
                    <th>A</th>
                    <th>B</th>
                    <th>C</th>
                    <th>D</th>
                </thead>
                <tbody>
                    <!-- A -->
                    <tr>
                        <td colspan="5"><b>A</b></td>
                    </tr>
                    <tr>
                        <td>Has Completed Previous Loan</td>
                        <td>@if ($user->completedLoansCount > 1) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Owns a House</td>
                        <td>@if ($user->ownsHouse) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Salary higher than 10.000.000</td>
                        <td>@if ($user->employment_salary > 10000000) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Works more than 5 years</td>
                        <td>@if ($user->employment_duration > 60) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>BI Checking equals to Collect 1</td>
                        <td>@if (($user->bi_checking) && ($user->bi_checking == 1)) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Works in Affiliate Company</td>
                        <td>@if ($user->worksInAffiliateCompany) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <!-- B -->
                    <tr>
                        <td colspan="5"><b>B</b></td>
                    </tr>
                    <tr>
                        <td>Permanent Employee</td>
                        <td>-</td>
                        <td>@if ($user->isPermanentEmployee) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Salary higher than 5.000.000</td>
                        <td>-</td>
                        <td>@if ($user->employment_salary > 5000000) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Works more than 3 years</td>
                        <td>-</td>
                        <td>@if ($user->employment_duration > 36) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>BI Checking minimum Collect 2 or higher (Collect 2, Collect 1)</td>
                        <td>-</td>
                        <td>@if (($user->bi_checking) && ($user->bi_checking <= 2)) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Works in Affiliate Company</td>
                        <td>-</td>
                        <td>@if ($user->worksInAffiliateCompany) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <!-- C -->
                    <tr>
                        <td colspan="5"><b>C</b></td>
                    </tr>
                    <tr>
                        <td>Permanent Employee</td>
                        <td>-</td>
                        <td>-</td>
                        <td>@if ($user->isPermanentEmployee) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Salary minimum UMR (3.300.000)</td>
                        <td>-</td>
                        <td>-</td>
                        <td>@if ($user->employment_salary >= 3300000) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Works more than 1 year</td>
                        <td>-</td>
                        <td>-</td>
                        <td>@if ($user->employment_duration > 12) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>BI Checking minimum Collect 2 or higher (Collect 2, Collect 1)</td>
                        <td>-</td>
                        <td>-</td>
                        <td>@if (($user->bi_checking) && ($user->bi_checking <= 2)) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Works in Affiliate Company</td>
                        <td>-</td>
                        <td>-</td>
                        <td>@if ($user->worksInAffiliateCompany) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                    </tr>
                    <!-- D -->
                    <tr>
                        <td colspan="5"><b>D</b></td>
                    </tr>
                    <tr>
                        <td>Tidak memenuhi semua kriteria yang diatas</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>