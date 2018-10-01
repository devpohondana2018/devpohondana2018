<?php

namespace App\Admin\Controllers;

use App\ActivityLog;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use App\Admin\Extensions\ExcelExpoterActivityLog;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use DB;
use App\User;
use App\AdminUser;
use Illuminate\Http\Request;
use PDF;

class ActivityLogController extends Controller
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

            $content->header('Users Logs');
            // $content->description('description');
            $content->breadcrumb(
             ['text' => 'Activity Logs', 'url' => '/activitylogs']
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

            $content->header('Users Logs');
            // $content->description('description');
            $content->breadcrumb(
             ['text' => 'Activity Logs', 'url' => '/activitylogs'],
             ['text' => 'Edit', 'url' => '/activitylogs/edit']
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

            $content->header('Users Logs');
            // $content->description('description');
            $content->breadcrumb(
             ['text' => 'Activity Logs', 'url' => '/activitylogs'],
             ['text' => 'Create', 'url' => '/activitylogs/create']
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
        return Admin::grid(ActivityLog::class, function (Grid $grid) {
            // $grid->tools(function ($tools) {
            //     $params = '';
            //     if (isset($_SERVER['QUERY_STRING'])) {
            //         $params = $_SERVER['QUERY_STRING'];
            //     }
            //     $tools->append('<a target="_blank" href="activitylogs/export/pdf?' . $params . '" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export PDF</a>');
            // });

            $grid->model()->orderBy('id', 'desc');
            $grid->id('ID')->sortable();

            // $grid->subject_id();
            // $grid->causer_id();
            $grid->column('User')->display(function () {
                $activity_logs = ActivityLog::find($this->id);
                // dd($activity_logs);
                $getuser = $activity_logs->causer_type::find($activity_logs->causer_id);
                // dd($getuser);
                if ($activity_logs->causer_type == 'Encore\Admin\Auth\Database\Administrator') {
                    $activity_log_title         = $activity_logs->causer_tpye;
                    $activity_logs->causer_type = 'auth/users';
                }elseif($activity_logs->causer_type == 'Illuminate\Foundation\Auth\User'){
                    $activity_log_title         = $activity_logs->causer_tpye;
                    $activity_logs->causer_type = 'users';
                }else{
                    $activity_logs->causer_type = substr($activity_logs->causer_type,4);
                    $activity_log_title          = $activity_logs->causer_type;
                    $activity_logs->causer_type = strtolower($activity_logs->causer_type);
                    $activity_logs->causer_type = $activity_logs->causer_type.'s';
                }
                return !empty($activity_logs) && !empty($getuser) ? '<a href="'.url('admin/'.$activity_logs->causer_type.'/'.$getuser->id).'">'.$getuser->name.'</a>' : '-';
            });
            $grid->column('Related Document')->display(function () {
                $activity_logs = ActivityLog::find($this->id);
                if (!empty($activity_logs->subject_type)) {
                    $getrelateddocument = $activity_logs->subject_type::find($activity_logs->subject_id);
                    // dd($getrelateddocument);
                    if (!empty($getrelateddocument)) {
                        $activity_logs->subject_type = substr($activity_logs->subject_type,4);
                        $activity_log_title          = $activity_logs->subject_type;
                        $activity_logs->subject_type = strtolower($activity_logs->subject_type);
                        $activity_logs->subject_type = $activity_logs->subject_type.'s';
                        return !empty($activity_logs) && !empty($getrelateddocument) ? '<a href="'.url('admin/'.$activity_logs->subject_type.'/'.$getrelateddocument->id).'">'.$activity_log_title.' #'.$getrelateddocument->id.'</a>' : '-';
                    }
                        return '';
                }else{
                    return '';
                }
            });
            $grid->column('Related User')->display(function () {
                $activity_logs = ActivityLog::find($this->id);
                    $properties = $activity_logs->properties;
                    $properties = json_decode($properties);
                if (!empty($properties)) {
                    // dd($properties->user_id);
                    if (!empty($properties->user_id)) {
                        # code...
                    $getuser = User::find($properties->user_id);
                    // dd($getuser);
                        $activity_logs->causer_type = substr($activity_logs->causer_type,4);
                        $activity_log_title          = $activity_logs->causer_type;
                        $activity_logs->causer_type = strtolower($activity_logs->causer_type);
                        $activity_logs->causer_type = $activity_logs->causer_type.'s';
                    }
                    return !empty($getuser) ? '<a href="'.url('admin/users/'.$getuser->id).'">'.$getuser->name.'</a>' : '';
                }else{
                    return '';
                }
            });
            $grid->description('Activity');
            $grid->properties('Properties')->setAttributes(['class' => 'width-log'])->display(function($properties){
                $properties = json_decode($properties, true);
                $property = '';
                if (!empty($properties)) {
                foreach ($properties as $key => $value) {
                    $property .= $key . '</br>';
                }
                }
                return $property;
            });
            $grid->column('Value')->setAttributes(['class' => 'width-log'])->display(function(){
                $activity_logs = ActivityLog::find($this->id)->properties;
                $properties = json_decode($activity_logs, true);
                $property = '';
                if (!empty($properties)) {
                foreach($properties as $key => $val) {
                    !empty($val) ? $property .= $val . '<br>' : '';
                    //$property = $property.$val.'</br>';
                }
                }
                return $property;
            });
            // $grid->properties('Related User');
            $grid->created_at()->sortable();
            $grid->updated_at()->sortable();
            $grid->filter(function($filter){
                $filter->where(function ($query) {
                        $query->whereHas('admin_users', function ($query) {
                            $query->where('causer_id', '=', "{$this->input}");
                        });
                }, 'AdminUser')->select(AdminUser::get()->pluck('name','id'));
                $filter->where(function ($query) {
                    $query->join('users', 'users.id', '=', 'causer_id')
                            ->where('causer_id', '=', "{$this->input}");

                }, 'User')->select(User::get()->pluck('name','id'));
            });
            $grid->disableExport();
            // $grid->exporter(new ExcelExpoterActivityLog());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(ActivityLog::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }    
    public function exportpdf(Request $request)
    {
        $urlparams = $request->all();
        $activitylogs = DB::table('activity_log')->orderBy('id','DESC')->get();
        foreach ($activitylogs as $activitylog) {
            if (!empty($activitylog->causer_type)) {
                if (!empty($activitylog->causer_id)) {
                    if (!empty($activitylog->causer_type::find($activitylog->causer_id)->name)) {
                        $activitylog->username = $activitylog->causer_type::find($activitylog->causer_id)->name;
                    }
                }
            }
            if (!empty($activitylog->subject_type)) {
                $getrelateddocument = $activitylog->subject_type::find($activitylog->subject_id);
                if (!empty($getrelateddocument)) {
                    $activitylog->subject_type = substr($activitylog->subject_type,4);
                    $activity_log_title          = $activitylog->subject_type;
                    $activitylog->subject_type = strtolower($activitylog->subject_type);
                    $activitylog->subject_type = $activitylog->subject_type.'s';
                    $activitylog->related_document = !empty($activitylog) && !empty($getrelateddocument) ? $activity_log_title.' #'.$getrelateddocument->id : '-';
                }
                    
            }
            if (!empty($activitylog->properties)) {
                $properties = $activitylog->properties;
                    $properties = json_decode($properties);
                if (!empty($properties)) {
                    if (!empty($properties->user_id)) {
                    $getuser = User::find($properties->user_id);
                        $activitylog->causer_type = substr($activitylog->causer_type,4);
                        $activity_log_title          = $activitylog->causer_type;
                        $activitylog->causer_type = strtolower($activitylog->causer_type);
                        $activitylog->causer_type = $activitylog->causer_type.'s';
                    }
                    $activitylog->related_user = !empty($getuser) ? $getuser->name : '';
                }
            }
            if (!empty($activitylog->properties)) {
                $keyandvalues = json_decode($activitylog->properties, true);

                    $keyandvalue = array();
                    $keyandvalue2 = array();

                    foreach ($keyandvalues as $key => $value) {
                        !empty($key) ? array_push($keyandvalue, $key) : '';
                        !empty($value) ? array_push($keyandvalue2, $value) : '';
                    }
                    $activitylog->key = $keyandvalue;
                    $activitylog->value = $keyandvalue2;    
            }
        }
        
        // dd($activitylogs);
        view()->share('activitylogs',$activitylogs);
        $pdf = PDF::loadView('admin.activitylogs.export.pdf');
        return $pdf->stream('activitylogs.pdf');
        // return view('admin.activitylogs.export.pdf',compact('activitylogs'));

    }
}
