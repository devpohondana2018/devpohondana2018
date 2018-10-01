<?php

namespace App\Admin\Controllers;

use App\BankAccount;
use App\Bank;
use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class BankAccountController extends Controller
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

            $content->header('Bank Accounts');
            $content->description('List');
            $content->breadcrumb(
             ['text' => 'Bank Accounts', 'url' => '/bank_accounts']
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

            $content->header('Bank Accounts');
            $content->description('Edit');
            $content->breadcrumb(
             ['text' => 'Bank Accounts', 'url' => '/bank_accounts'],
             ['text' => 'Edit', 'url' => '/bank_accounts/edit']
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

            $content->header('Bank Accounts');
            $content->description('Create');
            $content->breadcrumb(
             ['text' => 'Bank Accounts', 'url' => '/bank_accounts'],
             ['text' => 'Create', 'url' => '/bank_accounts/create']
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
        return Admin::grid(BankAccount::class, function (Grid $grid) {

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
            $grid->user()->id('User')->sortable()->display(function ($id) {
                $user = User::find($id);
                return $user ? '<a href="'.url('admin/users/'.$user->id).'">'.$user->name.'</a>' : '';
            });
            $grid->bank()->id('Bank')->sortable()->display(function ($id) {
                $bank = Bank::find($id);
                return $bank ? '<a href="'.url('admin/banks/'.$bank->id).'">'.$bank->name.'</a>' : '';
            });
            $grid->account_name()->sortable();
            $grid->account_number()->sortable();
            $grid->created_at();

            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->equal('user_id','User');
                $filter->equal('bank_id','Bank')->select(Bank::get()->pluck('name','id'));
                $filter->equal('account_name');
                $filter->equal('account_number');
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
        return Admin::form(BankAccount::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->select('user_id', 'User')->options(User::get()->pluck('name','id'));
            $form->select('bank_id', 'Bank')->options(Bank::get()->pluck('name','id'));
            $form->text('account_name');
            $form->text('account_number');
            $form->display('created_at', 'Created At');

            $form->disableReset();
        });
    }
}
