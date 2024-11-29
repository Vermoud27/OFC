<?php

namespace App\Controllers;

use Config\Pager;

class ControllerOFC extends BaseController
{
    public function index(): string
    {
        return view('pageAccueil');
    }
}

?>  