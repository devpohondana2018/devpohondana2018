<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Alert;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Callout;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;

use App\Loan;
use App\LoanTenor;
use DB;
use App\LoanGrade;
use App\Investment;
use App\Installment;
use App\Transaction;
use App\Status;
use App\User;
use App\Company;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Home');

            $content->row(function ($row) {
                $row->column(12, $this->dashboard_title_view());
            });

            $content->row(function ($row) {
            	$verified_loans = DB::table('loans')
	                ->join('users', 'loans.user_id', '=', 'users.id')
	                ->where('users.verified', 1)
	                ->where('loans.status_id', 1)
	                ->whereNull('loans.deleted_at')
	                ->get();
	            $unpaid_investments = DB::table('investments')
	                ->join('users', 'investments.user_id', '=', 'users.id')
	                ->where('users.verified', 1)
	                ->where('investments.paid', 0)
	                ->where('investments.status_id', '!=', '8')
	                ->whereNull('investments.deleted_at')
	                ->get();
	                // dd($unpaid_investments);
                $row->column(4, new InfoBox('Unverified Users', 'users', 'aqua', '/admin/users?&verified=0', User::where('verified',0)->count()));
                $row->column(4, new InfoBox('Verified Loans', 'shopping-cart', 'green', '/admin/loans?&status_id[]=1&is_verified=1', $verified_loans->count()));
                $row->column(4, new InfoBox('Unpaid Fundings', 'book', 'yellow', '/admin/investments?&paid=0', $unpaid_investments->count()));
            });

            $content->row(function ($row) {
            	$overdue_loan_installments = DB::table('installments')
	                ->join('loans', 'loans.id', '=', 'installments.installmentable_id')
	                ->where('installments.installmentable_type', 'App\Loan')
	                ->where('loans.status_id', '3')
	                ->where('installments.paid', 0)
	                ->where('installments.due_date', '<=',Carbon::now()->format('Y-m-d'))
	                ->whereNull('installments.deleted_at')
	                ->get();
                $row->column(4, new InfoBox('Inactive Users', 'users', 'aqua', '/admin/users?&active=0',User::where('active',0)->count()));
                $row->column(4, new InfoBox('Overdue Loan Installments', 'shopping-cart', 'green', '/admin/installments?&installmentable_type=App%5CLoan&installmentable_id=&Reference+Status=3&due_date%5Bstart%5D=&due_date%5Bend%5D='.Carbon::now()->format('Y-m-d').'&paid=0',$overdue_loan_installments->count()));
            });

        });
    }

    public static function dashboard_title_view()
    {
    	return '
    		<style>
			    .title {
			        font-size: 30px;
			        color: #636b6f;
			        font-family: "Raleway", sans-serif;
			        font-weight: 100;
			        display: block;
			        text-align: center;
			        margin: 20px 0 10px 0px;
			    }

			    .links {
			        text-align: center;
			        margin-bottom: 20px;
			    }

			    .links > a {
			        color: #636b6f;
			        padding: 0 25px;
			        font-size: 12px;
			        font-weight: 600;
			        letter-spacing: .1rem;
			        text-decoration: none;
			        text-transform: uppercase;
			    }

			    .subtitle {
			    	font-size: 15px;
			    	color: #636b6f;
			        font-family: "Raleway", sans-serif;
			        font-weight: 100;
			        text-align: center;
			        display: block;
			        margin: 10px 0 20px 0px;
			    }
			</style>

			<div class="title">Welcome back, '.Admin::user()->name.'!</div>
			<div class="subtitle">Current Time: '.Carbon::now()->toDayDateTimeString().'</div>
    	';
    }
}
