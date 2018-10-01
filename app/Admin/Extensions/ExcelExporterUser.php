<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExporterUser extends AbstractExporter
{
    public function export()
    {
        Excel::create('Users', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

            	$rows = array();
                $header['head'] = "Users";
                array_push($rows, $header);
            	$title['id'] = "User ID";
                $title['company'] = "Company";
            	$title['name'] = "Name";
            	$title['email'] = "Email";
                $title['type'] = "Type";
                $title['roles'] = "Roles";
            	$title['active'] = "Active";
            	$title['verified'] = "Verified";
            	$title['created_at'] = "Created at";
            	array_push($rows, $title);

            	foreach ($this->getData() as $item) {
            		$user = \App\User::find($item['id']);
	                $roles = $user->roles->pluck('name')->first();
	                $active = $item['active'] == true ? 'Active' : 'Inactive';
	                $verified = $item['verified'] == true ? 'Verified' : 'Not verified';
                    $company = $user->company ? $user->company->name : '';
                    $response['id'] = $item['code'];
                    $response['company'] = $company;
                    $response['name'] = $item['name'];
                    $response['email'] = $item['email'];
                    $response['type'] = $item['type'];
                    $response['roles'] = $roles;
                    $response['active'] = $active;
                    $response['verified'] = $verified;
                    $response['created_at'] = $item['created_at'];
                    array_push($rows, $response);
            	}

                $sheet->rows($rows);

            });

        })->export('xls');
    }
}