<?php

namespace App\Http\Controllers;
use App\Models\ContactMessage;

use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(12);
        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        $message->markAsRead();
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')
            ->with('success', 'تم حذف الرسالة');
    }
}
