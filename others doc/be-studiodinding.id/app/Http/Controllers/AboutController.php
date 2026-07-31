<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AboutController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request) {
        return view('pages.about_0');
    }

    public function detail($people) {
        if($people == 'Ryan-Dharmansyah') {
            return view('pages.about_ryan_dharmansyah');
        }
        elseif($people == 'Melita-Lumanto') {
            return view('pages.about_melita_lumanto');
        }
        elseif($people == 'Henry-Chandra') {
            return view('pages.about_henry_chandra');
        }
    }

}
