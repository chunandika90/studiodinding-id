<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PortofolioController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function list(Request $request) {
        return view('pages.portofolio.list');
    }

    public function details($portofolio) {
        return view('pages.portofolio.details.' . str_replace('-', '_', $portofolio));
    }

    public function commercial() {
        return view('pages.portofolio.commercial');
    }

    public function residential() {
        return view('pages.portofolio.residential');
    }

}
