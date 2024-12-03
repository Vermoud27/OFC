<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class FaqController extends Controller
{
    public function index()
    {
        // Charge la vue FAQ
        return view('faq');
    }
}
