<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(ContactRequest $request)
    {
        ContactMessage::create($request->validated());

        return redirect()
            ->route('contact.index')
            ->with('success', 'Terima kasih, pesan Anda telah kami terima. Tim kami akan segera menghubungi Anda.');
    }
}
