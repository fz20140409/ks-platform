<?php

namespace App\Http\Controllers\Admin;

use Rap2hpoutre\LaravelLogViewer\LogViewerController;

class LogsController extends LogViewerController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin_login_auth');
        $this->middleware('admin_permission_auth');
    }
}
