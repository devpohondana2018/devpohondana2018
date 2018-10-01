<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->get('users/report', 'UserController@report');
    $router->resource('users', UserController::class);
    $router->get('users/verify/{user}', 'UserController@verifyUser');
    $router->get('users/export/pdf', 'UserController@exportpdf');
    $router->resource('loans', LoanController::class);
    $router->get('loans/validateGrade/{loan}/{display?}', 'LoanController@validateGrade');
    $router->get('loans/updateStatus/{loan}/{status}', 'LoanController@updateStatus');
    $router->get('loans/calculateRates/{loan}', 'LoanController@calculateRates');
    $router->get('loans/generateInstallments/{loan}', 'LoanController@generateInstallments');
    $router->get('loans/deleteInstallments/{loan}', 'LoanController@deleteInstallments');
    $router->post('loans/mass_approves', 'LoanController@mass_approves');
    $router->post('loans/mass_validate_grades', 'LoanController@mass_validate_grades');
    $router->resource('investments', InvestmentController::class);
    $router->get('investments/updateStatus/{investment}/{status}', 'InvestmentController@updateStatus');
    $router->get('investments/updatePayment/{investment}', 'InvestmentController@updatePayment');
    $router->get('investments/calculateRates/{investment}', 'InvestmentController@calculateRates');
    $router->get('investments/generateInstallments/{investment}', 'InvestmentController@generateInstallments');
    $router->get('investments/deleteInstallments/{investment}', 'InvestmentController@deleteInstallments');
    $router->resource('grades', LoanGradeController::class);
    $router->resource('transactions', TransactionController::class);
    $router->resource('installments', InstallmentController::class);
    $router->resource('companies', CompanyController::class);
    $router->resource('banks', BankController::class);
    $router->resource('bank_accounts', BankAccountController::class);
    $router->resource('loan_tenors', LoanTenorController::class);
    $router->get('reports', 'ReportController@index');
    $router->get('reports/users', 'ReportController@users');
    $router->get('reports/loans', 'ReportController@loans');
    $router->get('reports/investments', 'ReportController@investments');
    $router->get('reports/users/excel', 'ReportController@exportuserojk');
    $router->get('reports/users/pdf', 'ReportController@exportuserpdf');
    $router->get('installments/export/pdf', 'InstallmentController@exportpdf');
    $router->get('transactions/export/pdf', 'TransactionController@exportpdf');
    $router->get('investments/export/pdf', 'InvestmentController@exportpdf');
    $router->get('loans/export/pdf', 'LoanController@exportpdf');
    $router->get('loanprofits/export/pdf', 'LoanProfitController@exportpdf');
    $router->resource('activitylogs', ActivityLogController::class);
    $router->resource('balances', BalanceController::class);
    $router->resource('detailsbalances', DetailsBalanceController::class);
    $router->resource('detailsbalanceinstallments', DetailsBalanceInstallmentController::class);
    $router->resource('loanprofits', LoanProfitController::class);
    $router->resource('agings', AgingController::class);
    $router->resource('investmentbalances', InvestmentBalanceController::class);
    $router->get('loansbalance/export/pdf', 'BalanceController@exportpdf');
    $router->get('agings/export/pdf', 'AgingController@exportpdf');
    $router->get('detailsbalances/export/pdf', 'DetailsBalanceController@exportpdf');
    $router->get('detailsbalancesinstallments/export/pdf', 'DetailsBalanceInstallmentController@exportpdf');
    $router->get('investmentsbalances/export/pdf', 'InvestmentBalanceController@exportpdf');
    $router->get('activitylogs/export/pdf', 'ActivityLogController@exportpdf');
});
