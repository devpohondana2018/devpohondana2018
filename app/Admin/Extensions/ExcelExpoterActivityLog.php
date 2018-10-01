<?php

namespace App\Admin\Extensions;
use App\User;
use App\ActivityLog;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExcelExpoterActivityLog extends AbstractExporter
{
    public function export()
    {
        Excel::create('Activity Logs', function($excel) {

            $excel->sheet('Activity Logs', function($sheet) {

                // dd($this->getData());
                $rows = array();
                $header['head'] = "Activity Logs";
                array_push($rows, $header);
                $title['user'] = "User";
                $title['related_document'] = "Related Document";
                $title['related_user'] = "Related User";
                $title['activity'] = "Activity";
                $title['properties'] = "Properties";
                $title['value'] = "Value";
                $title['created_at'] = "Created at";
                $title['updated_at'] = "Updated at";
                array_push($rows, $title);
                
                foreach ($this->getData() as $item) {
                        $activity_logs = ActivityLog::find($item['id']);
                        // dd($activity_logs);  
                        if (!empty($activity_logs->causer_id)) {
                            $getuser = $activity_logs->causer_type::find($activity_logs->causer_id);
                        }else{
                            $getuser = '';
                        }
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
                        $response['user'] = !empty($activity_logs) && !empty($getuser) ? $getuser->name : '-';
                        $activity_logs = ActivityLog::find($item['id']);
                        if (!empty($activity_logs->subject_type)) {
                            $getrelateddocument = $activity_logs->subject_type::find($activity_logs->subject_id);
                            // dd($getrelateddocument);
                            if (!empty($getrelateddocument)) {
                                $activity_logs->subject_type = substr($activity_logs->subject_type,4);
                                $activity_log_title          = $activity_logs->subject_type;
                                $activity_logs->subject_type = strtolower($activity_logs->subject_type);
                                $activity_logs->subject_type = $activity_logs->subject_type.'s';
                                $response['related_document'] = !empty($activity_logs) && !empty($getrelateddocument) ? $activity_log_title.' #'.$getrelateddocument->id : '-';
                            }
                                $response['related_document'] = '';
                        }else{
                            $response['related_document'] = '';
                        }
                        $activity_logs = ActivityLog::find($item['id']);
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
                            $response['related_user'] = !empty($getuser) ? $getuser->name : '';
                        }else{
                            $response['related_user'] = '';
                        }
                        $property = '';
                        $property2 = '';
                        $activity_logs = ActivityLog::find($item['id'])->properties;
                        $properties = json_decode($activity_logs, true);
                        $response['activity'] = $item['description'];
                        foreach($properties as $key => $val) {
                            !empty($val) ? $property .= $key . ',' : '';
                            !empty($val) ? $property2 .= $val . ',' : '';
                        }
                        $response['properties'] = $property;
                        $response['value'] = $property2;
                        $response['created_at'] = $item['created_at'];
                        $response['updated_at'] = $item['updated_at'];
                        array_push($rows, $response);
                    }     
                $sheet->rows($rows);

            });

        })->export('xls');
    }
}