<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    public function index()
    {
        $requests = ContactRequest::orderBy('request_date', 'desc')->get();
        return view('admin.contact-requests.index', compact('requests'));
    }

    public function destroy(ContactRequest $contactRequest)
    {
        $contactRequest->delete();
        return redirect()->back()->with('success', 'Запит видалено');
    }

    public function markAsDone(ContactRequest $contactRequest)
    {
        $contactRequest->update(['is_pending' => false]);
        return redirect()->back()->with('success', 'Запит позначено як виконаний');
    }
}
