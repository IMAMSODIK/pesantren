<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Dashboard - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        return view('dashboard.index', $data);
    }
}
