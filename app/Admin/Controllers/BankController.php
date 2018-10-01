<?php

namespace App\Admin\Controllers;

use App\Bank;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class BankController extends Controller
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

            $content->header('Banks');
            $content->description('List');
            $content->breadcrumb(
             ['text' => 'Banks', 'url' => '/banks']
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

            $content->header('Banks');
            $content->description('Edit');
            $content->breadcrumb(
             ['text' => 'Banks', 'url' => '/banks'],
             ['text' => 'Edit', 'url' => '/banks/edit']
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

            $content->header('Banks');
            $content->description('Create');
            $content->breadcrumb(
             ['text' => 'Banks', 'url' => '/banks'],
             ['text' => 'Create', 'url' => '/banks/create']
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
        return Admin::grid(Bank::class, function (Grid $grid) {

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
            $grid->name()->sortable();
            $grid->code()->sortable();
            $grid->created_at();

            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->equal('name');
                $filter->equal('code');
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
        return Admin::form(Bank::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name');
            $form->text('code');
            $form->display('created_at', 'Created At');

            $form->disableReset();
        });
    }
}
