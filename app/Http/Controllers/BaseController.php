<?php

namespace App\Http\Controllers;

use App\Received;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function getViewData($data = null)
    {
        if (!is_array($data)) $data = [];
        $user = \Auth::user();
        $data['user'] = $user;

        $data['received_inquiries'] = Received::userInquiries($user);
        return $data;
    }
}
