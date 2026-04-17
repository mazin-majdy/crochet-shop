<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    // ── Ordered groups for the tab display ──────────────────────────────
    private const GROUPS = ['site', 'contact', 'social', 'payment', 'appearance'];

    /*
    |─────────────────────────────────────────────────────────────────────────
    | INDEX — عرض صفحة الإعدادات مع التبويبات
    |─────────────────────────────────────────────────────────────────────────
    */
    public function index(Request $request)
    {
        // جلب كل الإعدادات مجمّعة حسب المجموعة
        $allSettings = Setting::orderBy('group')->orderBy('sort')->get()
            ->groupBy('group');

        // ترتيب المجموعات حسب الثابت
        $groups = collect(self::GROUPS)->mapWithKeys(function ($g) use ($allSettings) {
            return [$g => $allSettings->get($g, collect())];
        });

        // التبويب النشط
        $activeTab = $request->get('tab', 'site');

        // إحصاءات الصيانة
        $stats = [
            'messages_read'   => ContactMessage::where('is_read', true)->count(),
            'messages_unread' => ContactMessage::where('is_read', false)->count(),
            'orders_total'    => Order::count(),
            'cache_keys'      => Setting::count(),
        ];

        return view('admin.settings', compact('groups', 'activeTab', 'stats'));
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | SAVE GROUP — حفظ مجموعة إعدادات معيّنة
    |─────────────────────────────────────────────────────────────────────────
    */
    // 
    public function saveGroup(Request $request, string $group)
    {
        if (!in_array($group, self::GROUPS)) {
            abort(404);
        }

        $settings = Setting::where('group', $group)->get()->keyBy('key');

        $rules = [];
        foreach ($settings as $key => $setting) {
            $shortKey = str_replace("{$group}.", '', $key);
            $rules[$shortKey] = match ($setting->type) {
                'email'   => 'nullable|email|max:150',
                'url'     => 'nullable|url|max:255',
                'boolean' => 'nullable|boolean',
                'color'   => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
                default   => 'nullable|string|max:1000',
            };
        }

        $validated = $request->validate($rules);

        foreach ($settings as $fullKey => $setting) {
            $shortKey = str_replace("{$group}.", '', $fullKey);

            // ✅ FIXED: Proper boolean handling
            if ($setting->type === 'boolean') {
                $value = $request->boolean($shortKey) ? '1' : '0';
            } else {
                $value = $validated[$shortKey] ?? '';
            }

            $setting->update(['value' => $value]);
            Cache::forget("setting:{$fullKey}");
        }

        Cache::forget("settings_group:{$group}");

        return redirect()
            ->route('admin.settings', ['tab' => $group])
            ->with('success', 'تم حفظ إعدادات ' . $this->groupLabel($group) . ' بنجاح ✅');
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | CHANGE PASSWORD — تغيير كلمة المرور
    |─────────────────────────────────────────────────────────────────────────
    */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ], [
            'new_password.min'       => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'new_password.confirmed' => 'كلمتا المرور الجديدة غير متطابقتين',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة'])
                ->withFragment('tab-account');
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return redirect()
            ->route('admin.settings', ['tab' => 'account'])
            ->with('success', 'تم تغيير كلمة المرور بنجاح 🔐');
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | CLEAR CACHE — مسح كاش الإعدادات
    |─────────────────────────────────────────────────────────────────────────
    */
    public function clearCache()
    {
        Setting::flushCache();

        return back()->with('success', 'تم مسح الكاش بنجاح — ستُحدَّث الإعدادات فوراً ✅');
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | CLEAR READ MESSAGES — حذف الرسائل المقروءة
    |─────────────────────────────────────────────────────────────────────────
    */
    public function clearReadMessages()
    {
        $count = ContactMessage::where('is_read', true)->count();
        ContactMessage::where('is_read', true)->delete();

        return redirect()
            ->route('admin.settings', ['tab' => 'maintenance'])
            ->with('success', "تم حذف {$count} رسالة مقروءة ✅");
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Private helpers
    |─────────────────────────────────────────────────────────────────────────
    */
    private function groupLabel(string $group): string
    {
        return match ($group) {
            'site'       => 'الموقع العام',
            'contact'    => 'بيانات التواصل',
            'social'     => 'التواصل الاجتماعي',
            'payment'    => 'معلومات الدفع',
            'appearance' => 'المظهر والتخصيص',
            default      => $group,
        };
    }
}
