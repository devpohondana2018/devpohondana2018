<?php

namespace App\Admin\Controllers;

use App\LoanGrade;
use App\LoanTenor;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class LoanGradeController extends Controller
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

            $content->header('Loan Grades');
            $content->description('List');
            $content->breadcrumb(
             ['text' => 'Loan Grades', 'url' => '/grades']
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

            $content->header('Loan Grades');
            $content->description('Edit');
            $content->breadcrumb(
             ['text' => 'Loan Grades', 'url' => '/grades'],
             ['text' => 'Edit', 'url' => '/grades/edit']
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

            $content->header('Loan Grades');
            $content->description('Create');
            $content->breadcrumb(
             ['text' => 'Loan Grades', 'url' => '/grades'],
             ['text' => 'Create', 'url' => '/grades/create']
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
        return Admin::grid(LoanGrade::class, function (Grid $grid) {

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

            $grid->id('ID')->sortable();
            $grid->tenor()->month('Tenor (month)')->sortable();
            $grid->rank('Grade')->sortable();
            $grid->platform_rate()->sortable();
            $grid->borrower_rate()->sortable();
            $grid->lender_rate()->sortable();

            $grid->created_at();

            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->equal('loan_tenor_id','Tenor')->select(LoanTenor::get()->pluck('month','id'));
                $filter->equal('rank','Grade');
                $filter->between('platform_rate');
                $filter->between('borrower_rate');
                $filter->between('lender_rate');
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
        return Admin::form(LoanGrade::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('rank', 'Grade');
            $form->select('loan_tenor_id', 'Tenor')->options(LoanTenor::get()->pluck('month','id'));
            $form->text('platform_rate','Platform Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
            $form->text('borrower_rate','Borrower Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');
            $form->text('lender_rate','Lender Rate (%)')->help('Isi dengan angka tanpa simbol persentase (%), gunakan titik (.) untuk desimal. Contoh: 7 atau 7.5');

            $form->display('created_at', 'Created At');
            $form->disableReset();
        });
    }
}
