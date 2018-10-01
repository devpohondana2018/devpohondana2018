<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use App\Loan;
use App\Investment;
use App\LoanTenor;
use Auth;
use App\Http\Middleware\CompleteBankAccount;
use App\Status;
use App\LoanGrade;
use Carbon\Carbon;
use App\Transaction;
use App\Events\CashInInvestment;
use App\Events\InvestmentAccepted;

class MarketplaceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*$this->middleware('auth');
        $this->middleware('role:lender');
        $this->middleware(CompleteBankAccount::class)->only('loan');*/
    }

    public function index()
    {
        $expire_date = Carbon::now()->addDays(3);
    	$loans = Loan::where('status_id','2')
                /*->whereDate('date_expired','<',$expire_date)*/
                ->whereRaw('amount_funded <> amount_total')
                ->whereNotNull('loan_grade_id')
                ->orderBy('date_expired', 'asc')
                ->paginate(10);
        $loanGrades = LoanGrade::select('rank', 'lender_rate')->where('rank', '<>', 'D')->groupBy('rank', 'lender_rate')->get();
        $tenors = LoanTenor::orderBy('month', 'ASC')->pluck('month','id');

    	return view('marketplaces.index', compact('loans', 'loanGrades', 'tenors'));
    }

    public function dataTable(Request $request)
    {
        $amountFunded = $request->input('amount_funded');
        $grade = $request->input('grade');
        $investRate = $request->input('investRate');

        //return 'asdasd';

        $search = $request->input('search');
        $draw = $request->input('draw');

        $columns = [
            'loans.id',
            'loans.amount_requested',
            DB::raw('(loans.amount_requested - loans.amount_funded) as amount_funded'),
            'lt.month',
            'lg.rank',
            'loans.invest_rate as interest_rate',
            'loans.created_at',
            'loans.amount_funded',
            'loans.date_expired',
        ];
                /*->whereDate('date_expired','<',$expire_date)*/
                /*->whereRaw('loans.amount_funded <> loans.amount_borrowed')*/

        $datas = Loan::join('loan_tenors as lt', 'lt.id', '=', 'loans.loan_tenor_id')
                ->join('loan_grades as lg', 'lg.id', '=', 'loans.loan_grade_id')
                ->join('users as u', 'u.id', '=', 'loans.user_id')
                ->join('installments as im', 'im.installmentable_id', '=', 'loans.id')
                ->whereNotNull('loans.loan_grade_id')
                ->where('loans.status_id','3')
                ->whereRaw('loans.amount_funded < loans.amount_requested')
                ->where('loans.date_expired', '>=', Carbon::now()->toDateString())
                // ->where('loans.created_at', '>=', Carbon::now()->addDays(-7)->toDateString())
                ->where([
                    'im.installmentable_type' => 'App\Loan',
                    'im.tenor' => '1',
                    'im.paid' => '0',
                ]);

        if (!empty($amountFunded)) {
            $datas = $datas->whereRaw('IF (loans.amount_funded is not null , (loans.amount_requested - loans.amount_funded), loans.amount_requested) <= ' . intval($amountFunded));
            $datas = $datas->where('loans.amount_requested', '<=', $amountFunded);
        }
        if (!empty($grade)) {
            $tenorMonth = LoanTenor::where('id', $grade)->first(['month']);
            $datas = $datas->where('lt.month', '<=', $tenorMonth->month);
            //$datas = $datas->where('lt.id', $grade);
        }
        if (!empty($investRate)) {
            $datas = $datas->where('loans.invest_rate', '>=', $investRate);
        }
                    
        $response['recordsTotal']    = $datas->count();
        $response['recordsFiltered'] = $datas->count();

        $searchValue = $search['value'];

        if ($request->has ( 'order' )) {
            if ($request->input ( 'order.0.column' ) != '') {
                $orderColumn = $request->input ( 'order.0.column' );
                $orderDirection = $request->input ( 'order.0.dir' );
                $datas->orderBy ( $columns [intval ( $orderColumn )], $orderDirection );
            }
        }

        $start  = $request->input ( 'start' );   
        $length = $request->input ( 'length' ); 


        $response['data'] = array();
        $response['data'] = $this->getDataTable($datas->select($columns)->get());
        return response()->json($response);
        
        //return response()->json($datas->get());
        return response()->json($response);
    }

    private function getDataTable($datas)
    {
        $response = array();
        $count = count($datas);
        $actionFormat = '<button onclick="onLoanFundingClick(this)" data-id="{id}" data-amount="{amount}" class="btn btn-success btn-success-pohondana btn-loan-funding">Pilih</button>';

        foreach ($datas as $data) {
            $amountPending = $data->amount_funded != null ? $data->amount_requested - $data->amount_funded : $data->amount_requested; 
            $action = str_replace('{id}', $data->id, $actionFormat); 
            $action = str_replace('{amount}', $amountPending, $action); 
            $data = [
                '<center>' . $data->id . '</center>',
                '<center>Rp. ' . number_format($data->amount_requested, 0, '.', '.') . '</center>',
                '<center>Rp. ' . number_format($data->amount_requested - $data->amount_funded, 0, '.', '.') . '</center>',
                '<center>' . $data->month. ' Bulan</center>',
                '<center>' . $data->rank . '</center>',
                '<center>' . $data->interest_rate . '%</center>',
                '<center>' . $data->date_expired . '</center>',
                '<center>' . ( $data->amount_funded != null ? round(($data->amount_funded/$data->amount_requested)*100,2) : 0 ) . '%</center>',
                '<div class="table-action">' . $action . '</div>',
            ];
            array_push($response, $data);
        }
        return $response;
    }

    public function getLoan($id)
    {
        $loan = Loan::join('loan_tenors as lt', 'lt.id', '=', 'loans.loan_tenor_id')
                ->join('statuses as s', 's.id', '=', 'loans.status_id')
                ->select([
                    'loans.id',
                    'loans.amount_requested',
                    'loans.loan_tenor_id',
                    'loans.description',
                    'loans.amount_borrowed',
                    'loans.amount_total',
                    'loans.interest_rate',
                    'loans.invest_rate',
                    'loans.amount_borrowed',
                    'loans.amount_total',
                    'loans.loan_grade_id',
                    'loans.amount_funded',
                    'lt.month',
                    's.name',
                    's.state',
                    DB::raw('DATE_FORMAT(loans.created_at, "%d/%m/%Y") as created_date'),
                ])
                ->findOrFail($id);
        return response()->json($loan);        
    }

    public function getLenderRate($id)
    {
        $lenderGrade = LoanGrade::where('rank', $id)->select('id', 'lender_rate')->first();
        return response()->json($lenderGrade);
    }

    public function loan($id)
    {
    	$loan = Loan::findOrFail($id);
    	return view('marketplaces.loan', compact('loan'));
    }

    public function funding(Request $request)
    {
    	
        $request->validate([
            'uid'    => 'required|exists:loans,id',
            'jumlah' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $loan = Loan::select()->find($request->uid);

            $pendingLoanAmount = $loan->amount_funded != null ? ($loan->amount_total - $loan->amount_funded) : $loan->amount_total;
            $checkHasPendingInvesment = Investment::where([
                'user_id' => Auth::user()->id,
                'paid' => 0,
                'status_id' => 3, 
            ])
            ->count();

            /*if (!empty($checkHasPendingInvesment)) {
                return back()
                     ->withInput($request->all())
                     ->with('error','Anda memiliki pendanaan yang belum dibayar. Untuk bisa menggunakan fitur pengajuan pendaan Silahkan bayar terlebih dahulu pendanaan Anda yang sebelumnya.');
            }*/

            if ($request->jumlah > $pendingLoanAmount) {
                return back()
                     ->withInput($request->all())
                     ->with('error','Jumlah pendanaan melebihi jumlah/sisa peminjaman');
            }

            $investment = new Investment;
            $investment->amount_invested = $request->jumlah;
            $investment->invest_rate     = $loan->invest_rate;
            $investment->loan_id         = $loan->id;
            $investment->user_id         = Auth::user()->id;
            $investment->status_id       = Status::where('name','accepted')->first()->id;
            $investment->save();
            $investment->calculateRates();
            $investment->generateInstallments();

            $loan->amount_funded += $request->jumlah;
            $loan->save();

            $transactionid = Transaction::insertGetId([
                'amount' => $investment->amount_invested,
                'user_id' => $investment->user_id,
                'transactionable_id' => $investment->id,
                'transactionable_type' => 'App\Investment',
                'payment_date' => Carbon::now(),
                'type' => 'Cash In',
                'status_id' => 1,
                'created_at' => Carbon::now()
            ]);

            $transaction3 = Transaction::find($transactionid);
            event(new CashInInvestment($transaction3));
            
            event(new InvestmentAccepted($investment));
            activity()
                ->performedOn($investment)
                ->log('Create Investment');
        } catch (Exception $e) {
            DB::rollback();
            activity()
                ->log('Failed Create Investment');
            return back()->with(
                'error',
                'Terjadi kesalahan saat mengirim data. Silahkan ulangi kembali ' .
                (!config('app.debug') ?: $e->getMessage())  
            );
        }

        DB::commit();

        return redirect('member/investments/'.$investment->id)->with('success','Pengajuan pendanaan berhasil disimpan');

       
    }
}
