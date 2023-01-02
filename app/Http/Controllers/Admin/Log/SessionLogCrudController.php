<?php

namespace App\Http\Controllers\Admin\Log;

use App\Models\Log\SessionLog;
use App\Base\BaseCrudController;
use App\Http\Requests\Log\SessionLogRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class SessionLogCrudController extends BaseCrudController
{
    public function setup()
    {
        $SessionLog = new SessionLog;

        $this->crud->setModel(SessionLog::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/session_log');
        $this->crud->setEntityNameStrings('sessionlog', 'Session Log');
        $this->crud->addButtonFromModelFunction('line', 'activityLog', 'activityLog', 'beginning');
        $this->crud->denyAccess(['create','update','delete','show']);
    }

    protected function setupListOperation()
    {
        $cols = [
            [
                'name' => 'row_number',
                'type' => 'row_number',
                'label' => trans('S.N.'),
                'orderable' => true,
                
            ],
            [
                'label' => trans('User Name'),
                'type' => 'text',
                'name' => 'username', // the db column for the foreign key
                
            ],
            [
                'label' => trans('User Email'),
                'name' => 'user_email', // the db column for the foreign key
                
            ],
            [
                'label' => trans('Login Date'),
                'type' => 'text',
                'name' => 'login_date', // the db column for the foreign key
                
            ],
            [
                'label' => trans('Login time'),
                'type' => 'text',
                'name' => 'login_time', // the db column for the foreign key
                
            ],
            [
                'label' => trans('Currently <br> logged In?'),
                'type' => 'radio',
                'name' => 'is_currently_logged_in',
                'options' => [
                    1 => 'Yes',
                    0 => 'No',
                ]
                
            ],
            [
                'label' => trans('Logout time'),
                'type' => 'text',
                'name' => 'logout_time', // the db column for the foreign key
                
            ],               
            [
                'label' => trans('IP'),
                'type' => 'text',
                'name' => 'user_ip', // the db column for the foreign key
                
            ],               
            [
                'label' => trans('Device'),
                'type' => 'text',
                'name' => 'device', // tdesktophe db column for the foreign key
                
            ],               
            [
                'label' => trans('Platform'),
                'type' => 'text',
                'name' => 'platform', // tdesktophe db column for the foreign key
                
            ],               
            [
                'label' => trans('Browser'),
                'type' => 'text',
                'name' => 'browser', // tdesktophe db column for the foreign key
                
            ],               
        ];
        $cols = array_filter($cols);
        $this->crud->addColumns($cols);
    }
}
