<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@home')->name('frontpage');
Route::get('/home', 'PagesController@home')->name('frontpage');
Route::get('/syarat-dan-ketentuan', 'PagesController@syarat_dan_ketentuan')->name('syarat-dan-ketentuan');
Route::get('/kontak-kami', 'PagesController@kontak_kami')->name('kontak-kami');
Route::get('/pinjaman', 'PagesController@pinjaman')->name('pinjaman');
Route::get('/pendanaan', 'PagesController@pendanaan')->name('pendanaan');
Route::get('/simulasi', 'PagesController@simulasi')->name('simulasi');
Route::get('/tentang-kami', 'PagesController@tentang_kami')->name('tentang-kami');
Route::get('/privacy-policy', 'PagesController@privacy_policy')->name('privacy-policy');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Auth::routes();
Route::get('register/{type?}', '\App\Http\Controllers\Auth\RegisterController@showRegistrationTypeForm');
Route::get('register_lender/{type?}', '\App\Http\Controllers\Auth\RegisterController@showRegisterLenderType');
Route::get('/loangrage/{id}', '\App\Http\Controllers\Auth\RegisterController@getLenderRate');
Route::get('/marketplacesdata', '\App\Http\Controllers\Auth\RegisterController@dataTable');
Route::get('/checkmobile',['uses'=>'\App\Http\Controllers\Auth\RegisterController@checkMobile']);
Route::get('/checkmobilephone',['uses'=>'\App\Http\Controllers\Auth\RegisterController@checkMobilePhone']);
Route::get('/checkemailV2',['uses'=>'\App\Http\Controllers\Auth\RegisterController@checkEmailV2']);
Route::get('/checkemail',['uses'=>'\App\Http\Controllers\Auth\RegisterController@checkEmail']);
Route::get('/checkKTPV2',['uses'=>'\App\Http\Controllers\Auth\RegisterController@checkKTPV2']);
Route::get('/checkKTP',['uses'=>'\App\Http\Controllers\Auth\RegisterController@checkKTP']);
Route::get('/checkNPWPV2',['uses'=>'\App\Http\Controllers\Auth\RegisterController@checkNPWPV2']);
Route::get('/checkNPWP',['uses'=>'\App\Http\Controllers\Auth\RegisterController@checkNPWP']);
Route::get('/autocomplete',['uses'=>'\App\Http\Controllers\Auth\RegisterController@autocomplete']);
Route::get('/district',['uses'=>'\App\Http\Controllers\Auth\RegisterController@district']);
Route::get('/verify/token/{verifytoken}', 'Auth\VerificationController@verify')->name('auth.verify');
Route::get('/verify/resend', 'Auth\VerificationController@resend')->name('auth.verify.resend');
Route::post('/verify/resend', '\App\Http\Controllers\Auth\VerificationController@resend_email')->name('auth.verify.email');

Route::prefix('marketplace')->group(function () {
	Route::get('/', 'MarketplaceController@index')->name('marketplace');
	Route::get('loan/{loan}', 'MarketplaceController@loan')->name('marketplace.loan.view');
	Route::get('loanapi/{loan}', 'MarketplaceController@getLoan')->name('marketplace.loan.api');
	Route::get('lendergrade/{id}', 'MarketplaceController@getLenderRate')->name('marketplace.lendergrade.api');
	Route::get('data', 'MarketplaceController@dataTable');
	Route::post('funding', 'MarketplaceController@funding')->name('marketplace.funding');
});

Route::group(['middleware' => ['isActive']], function () {
	Route::prefix('member')->group(function () {
		Route::get('/', 'HomeController@index')->name('home');
		Route::get('dashboard', 'HomeController@index');
		Route::get('profile', 'HomeController@profile');
		Route::patch('profile', 'HomeController@update');
		Route::get('account', 'HomeController@account');
		Route::patch('account', 'HomeController@accountUpdate');
		Route::get('profile_bank', 'HomeController@profile_bank');
		Route::patch('profile_bank', 'HomeController@update_bank');
		Route::resource('loans','LoanController', ['only' => [ 'index', 'create', 'store', 'show' ]]);
		Route::get('loans/accept/{id}', 'LoanController@acceptLoan');
		Route::get('loans/decline/{id}', 'LoanController@declineLoan');
		Route::get('loans/doc/{id}', 'LoanController@viewDoc');
		Route::post('loans/payment/{id}', 'LoanController@paymentConfirmation')->name('loan-payment-confimation');
		Route::get('investments/download','InvestmentController@download');
		Route::get('investments/doc/{id}', 'InvestmentController@viewDoc');
		Route::resource('investments','InvestmentController', ['only' => [ 'index', 'create', 'store', 'show' ]]);
		Route::get('investments/accept/{id}', 'InvestmentController@acceptInvestment');
		Route::get('investments/decline/{id}', 'InvestmentController@declineInvestment');
		Route::post('investments/payment/{id}', 'InvestmentController@paymentConfirmation')->name('investment-payment-confimation');
		Route::resource('transactions','TransactionController', ['only' => [ 'index' ]]);

		Route::group(['prefix' => 'messages'], function () {
		    Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
		    Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
		    Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
		    Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
		    Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
		});
	});

	Route::group(['prefix' => 'test'], function () {
	    Route::get('/investmentexpired', 'TestController@sampleInvestmentExpired');
		Route::get('/viewmailreminder', '\App\Http\Controllers\TestController@viewMailReminder')->name('test-mail-reminder');
		Route::get('/sampleMultipleEmail', '\App\Http\Controllers\TestController@sampleMultipleEmail');
		Route::get('/loanreject/{id}', '\App\Http\Controllers\TestController@sampleSendLoanReject');
		Route::get('/loanaccept/{id}', '\App\Http\Controllers\TestController@sampleSendLoanAccepted');
		Route::get('/investmentreject/{id}', '\App\Http\Controllers\TestController@sampleSendInvesmentReject');
		Route::get('/cashininstallment', '\App\Http\Controllers\TestController@testCashInInstallment');
		Route::get('/cashoutinstallment', '\App\Http\Controllers\TestController@testCashOutInstallment');
		Route::get('/cashoutloan', '\App\Http\Controllers\TestController@testCashOutLoan');
		Route::get('/cashininvestment', '\App\Http\Controllers\TestController@testCashInInvestment');
		Route::get('/encrypt', '\App\Http\Controllers\TestController@encrypt');
	});
});

Route::get('test', 'HomeController@test');
// Route::get('encrypt', 'TestController@encrypt');
