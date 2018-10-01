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
                        <td>Perusahaan rekanan</td>
                        <td>@if ($user->worksInAffiliateCompany) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Nilai jaminan > 1.5 x total pinjaman<br/>
                            <ul>
                                <ol>Jaminan:  {{ number_format($user->collateral_amount,2) }}</ol>
                                <ol>Total pinjaman: {{ number_format($loan->amount_requested,2) }}</ol>
                                <ol>1.5 * total pinjaman: {{ number_format(1.5*$loan->amount_requested,2) }}</ol>
                            </ul>
                        </td>
                        <td>@if ($user->collateral_amount > (1.5*$loan->amount_requested))<i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Current ratio > 2 ( asset / liabilities) <br/>
                            <ul>
                                <ol>Asset:  {{ number_format($user->current_asset,2) }}</ol>
                                <ol>Liabilities: {{ number_format($user->current_debt,2) }}</ol>
                                <ol>Current ratio: {{ ($user->current_asset/$user->current_debt) }}</ol>
                            </ul>
                        </td>
                        <td>@if ((($user->current_asset/$user->current_debt) > 2)) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Quick ratio > 1 ( (asset - inventory) / liabilities)<br/>
                            <ul>
                                <ol>Asset:  {{ number_format($user->current_asset,2) }}</ol>
                                <ol>Inventory: {{ number_format($user->current_inventory,2) }}</ol>
                                <ol>Liabilities: {{ number_format($user->current_debt,2) }}</ol>
                                <ol>Quick ratio: {{ (($user->current_asset - $user->current_inventory)/$user->current_debt) }}</ol>
                            </ul>
                        </td>
                        <td>@if ((($user->current_asset - $user->current_inventory)/$user->current_debt)>1) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Modal disetor = pinjaman<br/>
                            <ul>
                                <ol>Equity:  {{ number_format($user->current_equity,2) }}</ol>
                                <ol>Loan amount: {{ number_format($loan->amount_requested,2) }}</ol>
                            </ul>
                        </td>
                        <td>@if ($user->current_equity == $loan->amount_requested) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <!-- B -->
                    <tr>
                        <td colspan="5"><b>B</b></td>
                    </tr>
                    <tr>
                        <td>Nilai jaminan > 1.3 x total pinjaman<br/>
                            <ul>
                                <ol>Jaminan:  {{ number_format($user->collateral_amount,2) }}</ol>
                                <ol>Total pinjaman: {{ number_format($loan->amount_requested,2) }}</ol>
                                <ol>1.3 * total pinjaman: {{ number_format(1.3*$loan->amount_requested,2) }}</ol>
                            </ul>
                        </td>
                        <td>-</td>
                        <td>@if ($user->collateral_amount > (1.3*$loan->amount_requested))<i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Current ratio > 1.5 ( asset / liabilities) <br/>
                            <ul>
                                <ol>Asset:  {{ number_format($user->current_asset,2) }}</ol>
                                <ol>Liabilities: {{ number_format($user->current_debt,2) }}</ol>
                                <ol>Current ratio: {{ ($user->current_asset/$user->current_debt) }}</ol>
                            </ul>
                        </td>
                        <td>-</td>
                        <td>@if ((($user->current_asset/$user->current_debt) > 1.5)) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Quick ratio > 0.75 ( (asset - inventory) / liabilities)<br/>
                            <ul>
                                <ol>Asset:  {{ number_format($user->current_asset,2) }}</ol>
                                <ol>Inventory: {{ number_format($user->current_inventory,2) }}</ol>
                                <ol>Liabilities: {{ number_format($user->current_debt,2) }}</ol>
                                <ol>Quick ratio: {{ (($user->current_asset - $user->current_inventory)/$user->current_debt) }}</ol>
                            </ul>
                        </td>
                        <td>-</td>
                        <td>@if ((($user->current_asset - $user->current_inventory)/$user->current_debt)>0.75) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Modal disetor > 75% pinjaman<br/>
                            <ul>
                                <ol>Equity:  {{ number_format($user->current_equity,2) }}</ol>
                                <ol>Loan amount: {{ number_format($loan->amount_requested,2) }}</ol>
                            </ul>
                        </td>
                        <td>-</td>
                        <td>@if ($user->current_equity > ($loan->amount_requested*0.75)) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    
                    <!-- C -->
                    <tr>
                        <td colspan="5"><b>C</b></td>
                    </tr>
                    <tr>
                        <td>Nilai jaminan > 1.1 x total pinjaman<br/>
                            <ul>
                                <ol>Jaminan:  {{ number_format($user->collateral_amount,2) }}</ol>
                                <ol>Total pinjaman: {{ number_format($loan->amount_requested,2) }}</ol>
                                <ol>1.1 * total pinjaman: {{ number_format(1.1*$loan->amount_requested,2) }}</ol>
                            </ul>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>@if ($user->collateral_amount > (1.1*$loan->amount_requested))<i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Current ratio > 1 ( asset / liabilities) <br/>
                            <ul>
                                <ol>Asset:  {{ number_format($user->current_asset,2) }}</ol>
                                <ol>Liabilities: {{ number_format($user->current_debt,2) }}</ol>
                                <ol>Current ratio: {{ ($user->current_asset/$user->current_debt) }}</ol>
                            </ul>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>@if ((($user->current_asset/$user->current_debt) > 1)) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Quick ratio > 0.5 ( (asset - inventory) / liabilities)<br/>
                            <ul>
                                <ol>Asset:  {{ number_format($user->current_asset,2) }}</ol>
                                <ol>Inventory: {{ number_format($user->current_inventory,2) }}</ol>
                                <ol>Liabilities: {{ number_format($user->current_debt,2) }}</ol>
                                <ol>Quick ratio: {{ (($user->current_asset - $user->current_inventory)/$user->current_debt) }}</ol>
                            </ul>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>@if ((($user->current_asset - $user->current_inventory)/$user->current_debt)>0.5) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Modal disetor > 50% pinjaman<br/>
                            <ul>
                                <ol>Equity:  {{ number_format($user->current_equity,2) }}</ol>
                                <ol>Loan amount: {{ number_format($loan->amount_requested,2) }}</ol>
                            </ul>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>@if ($user->current_equity > ($loan->amount_requested*0.5)) <i class="fa fa-check"></i> @else <i class="fa fa-times"></i> @endif</td>
                        <td>-</td>
                    </tr>
                    
                    <!-- D -->
                    <tr>
                        <td colspan="5"><b>D</b></td>
                    </tr>
                    <tr>
                        <td>Tidak memenuhi semua kriteria di atas</td>
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