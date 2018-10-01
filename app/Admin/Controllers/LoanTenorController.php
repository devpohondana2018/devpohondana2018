<?php

namespace App\Admin\Controllers;

use App\LoanTenor;
use Carbon\Carbon;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\LoanGrade;

class LoanTenorController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Loan Tenors');
            $content->description('List');
            $content->breadcrumb(
             ['text' => 'Loan Tenors', 'url' => '/loan_tenors']
            );
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Loan Tenors');
            $content->description('Edit');
            $content->breadcrumb(
             ['text' => 'Loan Tenors', 'url' => '/loan_tenors'],
             ['text' => 'Edit', 'url' => '/loan_tenors/edit']
            );
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('Loan Tenors');
            $content->description('Create');
            $content->breadcrumb(
             ['text' => 'Loan Tenors', 'url' => '/loan_tenors'],
             ['text' => 'Create', 'url' => '/loan_tenors/create']
            );
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(LoanTenor::class, function (Grid $grid) {

            if(!Admin::user()->inRoles(['administrator', 'super_administrator'])) {
                $grid->tools(function ($tools) {
                    $tools->batch(function ($batch) {
                        $batch->disableDelete();
                    });
                });
                $grid->actions(function ($actions) {
                    $actions->disableDelete();
                });
            }

            $grid->model()->orderBy('month','asc')->withoutGlobalScopes();

            $grid->id('ID')->sortable();
            $grid->month()->sortable();
            $grid->active()->sortable()->display(function ($active) {
                return $active == true ? 'Yes' : 'No';
            });
            $grid->created_at();

            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->between('month');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(LoanTenor::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('month','Month');
            $states = [
                'on'  => ['value' => 1, 'text' => 'Yes', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => 'No', 'color' => 'danger'],
            ];
            $form->switch('active', 'Active')->states($states);
            $form->display('created_at', 'Created At');

            $form->disableReset();

            $form->saved(function (Form $form) {

                if (LoanGrade::select('id')->where([
                        'loan_tenor_id' => $form->model()->id, 
                        'rank' => 'A']
                    )->count() < 1) {
                    LoanGrade::insert([
                        'rank' => 'A',
                        'platform_rate' => '1',
                        'borrower_rate' => '2',
                        'lender_rate' => '1.25',
                        'active' => '1',
                        'loan_tenor_id' => $form->model()->id,
                        'created_at' => Carbon::now(),
                    ]);
                }

                if (LoanGrade::select('id')->where([
                        'loan_tenor_id' => $form->model()->id, 
                        'rank' => 'B']
                    )->count() < 1) {
                    LoanGrade::insert([
                        'rank' => 'B',
                        'platform_rate' => '1',
                        'borrower_rate' => '2.5',
                        'lender_rate' => '1.3',
                        'active' => '1',
                        'loan_tenor_id' => $form->model()->id,
                        'created_at' => Carbon::now(),
                    ]);
                }

                if (LoanGrade::select('id')->where([
                        'loan_tenor_id' => $form->model()->id, 
                        'rank' => 'C']
                    )->count() < 1) {
                    LoanGrade::insert([
                        'rank' => 'C',
                        'platform_rate' => '1',
                        'borrower_rate' => '3',
                        'lender_rate' => '1.5',
                        'active' => '1',
                        'loan_tenor_id' => $form->model()->id,
                        'created_at' => Carbon::now(),
                    ]);
                }

                if (LoanGrade::select('id')->where([
                        'loan_tenor_id' => $form->model()->id, 
                        'rank' => 'D']
                    )->count() < 1) {
                    LoanGrade::insert([
                        'rank' => 'D',
                        'platform_rate' => '1',
                        'borrower_rate' => '0',
                        'lender_rate' => '0',
                        'active' => '1',
                        'loan_tenor_id' => $form->model()->id,
                        'created_at' => Carbon::now(),
                    ]);
                }
            });
        });
    }
}
