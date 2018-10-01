<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use Auth;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $transactions = Auth::user()->transactions()->orderBy('id', 'desc')->paginate(10);
        return view('transactions.index',compact('transactions'));
    }
}
