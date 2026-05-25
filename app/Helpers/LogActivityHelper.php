<?php

namespace App\Helpers;

use App\Models\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogActivityHelper
{
    public static function addToLog($subject, $properties = [])
    {
        $log = [];
        $log['subject'] = $subject;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
        $log['user_id'] = Auth::check() ? Auth::id() : null;
        $log['properties'] = $properties;

        LogActivity::create($log);
    }

    public static function logList()
    {
        return LogActivity::latest()->get();
    }
}
