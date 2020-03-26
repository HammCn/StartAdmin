<?php

namespace app\oauth\controller;

use app\oauth\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return redirect('/oauth/test');
    }
}
