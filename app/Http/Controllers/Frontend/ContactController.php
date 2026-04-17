<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
            'email'      => 'nullable|email|max:150',
            'subject'    => 'nullable|string|in:order,custom,inquiry,other',
            'message'    => 'required|string|max:2000',
            'product_id' => 'nullable|exists:products,id',
        ], [
            'name.required'    => 'الاسم مطلوب',
            'phone.required'   => 'رقم الجوال مطلوب',
            'message.required' => 'الرسالة مطلوبة',
        ]);

        ContactMessage::create([
            'name'       => $request->name,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'subject'    => $request->subject ?? 'inquiry',
            'message'    => $request->message,
            'product_id' => $request->product_id,
            'is_read'    => false,
        ]);


        return back()->with(
            'success',
            'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً ✨'
        );
    }
}
