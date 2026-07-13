<?php

namespace App\Http\Controllers;

use App\Models\Empresa;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmpresas = Empresa::count();

        return view('dashboard', compact('totalEmpresas'));
    }

}
