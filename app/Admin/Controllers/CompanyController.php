<?php

namespace App\Admin\Controllers;

use App\Company;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CompanyController extends Controller
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

            $content->header('Companies');
            $content->description('List');
            $content->breadcrumb(
             ['text' => 'Companies', 'url' => '/companies']
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

            $content->header('Companies');
            $content->description('Edit');
            $content->breadcrumb(
             ['text' => 'Companies', 'url' => '/companies'],
             ['text' => 'Edit', 'url' => '/companies/edit']
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

            $content->header('Companies');
            $content->description('Create');
            $content->breadcrumb(
             ['text' => 'Companies', 'url' => '/companies'],
             ['text' => 'Create', 'url' => '/companies/create']
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
        return Admin::grid(Company::class, function (Grid $grid) {

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
            $grid->affiliate()->sortable()->display(function ($affiliate) {
                return $affiliate ? 'Yes' : 'No';
            });
            $grid->created_at();

            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->like('name');
                $filter->equal('affiliate')->radio([0 => 'No', 1 => 'Yes']);
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
        return Admin::form(Company::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name');
            $states = [
                'on'  => ['value' => 1, 'text' => 'Yes', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => 'No', 'color' => 'danger'],
            ];
            $form->switch('affiliate')->states($states);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->disableReset();
        });
    }
}
