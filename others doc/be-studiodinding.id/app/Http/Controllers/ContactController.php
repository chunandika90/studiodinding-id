<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Mail\NotifikasiEmail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request) {
        return view('pages.contact_us');
    }

    public function submit(Request $request) {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

		
		$data = [
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'subject' => $request->input('subject'),
			'message' => $request->input('message'),
			'contact_type' => $request->input('contact_type'),
		];
        if($request->input('contact_type') == 'project') $mailto = ['hi.studiodinding@gmail.com'];
        else if($request->input('contact_type') == 'collaboration') $mailto = ['design.studiodinding@gmail.com'];
        else if($request->input('contact_type') == 'hiring_job') $mailto = ['hiring.studiodinding@gmail.com'];

		Mail::to($mailto)
            // ->bcc('herman.puncak88@gmail.com')
            ->send(new NotifikasiEmail($data));

        // Process the contact form submission (e.g., send an email, save to database, etc.)
        // For demonstration purposes, we'll just return a success message.
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }

}
