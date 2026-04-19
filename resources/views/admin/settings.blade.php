@extends('layouts.admin')
@section('title', 'الإعدادات')
@section('page-title', 'إعدادات الموقع')

@push('styles')
    <style>
        /* ── Tab Nav ─────────────────────────────────────── */
        .settings-tabs {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            background: #fff;
            border-radius: 18px;
            padding: 10px;
            border: 1px solid #f0e8e0;
            margin-bottom: 28px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        }

        .stab {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 11px 18px;
            border-radius: 12px;
            border: none;
            font-family: 'Cairo', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.22s;
            color: #888;
            background: transparent;
            text-decoration: none;
            white-space: nowrap;
        }

        .stab i {
            font-size: 1rem;
        }

        .stab:hover {
            background: #f5ede6;
            color: #422018;
        }

        .stab.active {
            background: #7b1113;
            color: #fff;
            box-shadow: 0 4px 14px rgba(123, 17, 19, 0.28);
        }

        /* ── Settings Card ───────────────────────────────── */
        .s-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #f0e8e0;
            margin-bottom: 24px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        }

        .s-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f8f2ec;
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #fdfaf6, #faf4ed);
        }

        .s-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
        }

        .s-card-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #422018;
            margin: 0;
        }

        .s-card-desc {
            font-size: 0.78rem;
            color: #aaa;
            margin: 0;
        }

        .s-card-body {
            padding: 24px;
        }

        /* ── Field Row ───────────────────────────────────── */
        .field-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 16px;
            align-items: start;
            padding: 16px 0;
            border-bottom: 1px solid #faf4ef;
        }

        .field-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .field-label {
            font-size: 0.85rem;
            font-weight: 800;
            color: #422018;
            padding-top: 10px;
        }

        .field-sublabel {
            font-size: 0.75rem;
            color: #bbb;
            margin-top: 3px;
            font-weight: 500;
        }

        /* ── Inputs ──────────────────────────────────────── */
        .s-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 11px;
            border: 1.5px solid #e8ddd4;
            font-family: 'Cairo', sans-serif;
            font-size: 0.9rem;
            color: #2d1e14;
            transition: 0.2s;
            background: #fff;
        }

        .s-input:focus {
            border-color: #7b1113;
            box-shadow: 0 0 0 3px rgba(123, 17, 19, 0.08);
            outline: none;
        }

        .s-textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* ── Toggle Switch ───────────────────────────────── */
        .toggle-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 8px;
        }

        .toggle {
            position: relative;
            width: 50px;
            height: 26px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            inset: 0;
            border-radius: 26px;
            background: #e0d4cc;
            transition: 0.3s;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            left: 3px;
            top: 3px;
            background: #fff;
            transition: 0.3s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .toggle input:checked+.toggle-slider {
            background: #7b1113;
        }

        .toggle input:checked+.toggle-slider::before {
            transform: translateX(24px);
        }

        .toggle-label {
            font-size: 0.88rem;
            font-weight: 700;
            color: #422018;
        }

        /* ── Color Input ─────────────────────────────────── */
        .color-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .color-preview {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            border: 2px solid #e8ddd4;
            cursor: pointer;
            flex-shrink: 0;
            overflow: hidden;
        }

        .color-preview input[type="color"] {
            width: 100%;
            height: 100%;
            border: none;
            cursor: pointer;
            padding: 0;
            opacity: 0;
            position: absolute;
            inset: 0;
        }

        .color-preview {
            position: relative;
        }

        /* ── Save Bar ────────────────────────────────────── */
        .save-bar {
            position: sticky;
            bottom: 20px;
            z-index: 100;
            margin-top: 16px;
        }

        .save-bar-inner {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 16px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        /* ── Danger Zone ─────────────────────────────────── */
        .danger-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-radius: 14px;
            border: 1.5px solid #f5e0e0;
            background: #fdf9f9;
            margin-bottom: 12px;
        }

        .danger-action:last-child {
            margin-bottom: 0;
        }

        /* ── Stat Badge ──────────────────────────────────── */
        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--cream);
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #422018;
        }

        @media (max-width: 640px) {
            .field-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .settings-tabs {
                gap: 4px;
            }

            .stab {
                padding: 9px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <div>
                <h4 class="fw-bold m-0 text-brown">⚙️ الإعدادات</h4>
                <p class="text-muted small mb-0 mt-1">إدارة كاملة لإعدادات {{ setting('site.name') }}</p>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                    @csrf
                    <button class="btn-action py-2 px-3" title="مسح الكاش">
                        <i class="bi bi-arrow-clockwise"></i> تحديث الكاش
                    </button>
                </form>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="settings-tabs">
            @php
                $tabDefs = [
                    'site' => ['label' => 'الموقع العام', 'icon' => 'bi-globe2'],
                    'contact' => ['label' => 'التواصل', 'icon' => 'bi-telephone-fill'],
                    'social' => ['label' => 'السوشيال ميديا', 'icon' => 'bi-share-fill'],
                    'appearance' => ['label' => 'المظهر', 'icon' => 'bi-palette-fill'],
                    'account' => ['label' => 'الحساب', 'icon' => 'bi-person-fill'],
                    'maintenance' => ['label' => 'الصيانة', 'icon' => 'bi-tools'],
                ];
            @endphp

            @foreach ($tabDefs as $tabKey => $tabDef)
                <a href="{{ route('admin.settings', ['tab' => $tabKey]) }}"
                    class="stab {{ $activeTab === $tabKey ? 'active' : '' }}">
                    <i class="bi {{ $tabDef['icon'] }}"></i>
                    {{ $tabDef['label'] }}
                </a>
            @endforeach
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
       TAB: SITE
  ══════════════════════════════════════════════════════════════════ --}}
        @if ($activeTab === 'site')

            <form action="{{ route('admin.settings.save-group', 'site') }}" method="POST">
                @csrf

                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-icon" style="background:rgba(123,17,19,0.1);color:#7b1113">
                            <i class="bi bi-globe2"></i>
                        </div>
                        <div>
                            <p class="s-card-title">إعدادات الموقع العامة</p>
                            <p class="s-card-desc">المعلومات الأساسية التي تظهر في المتصفح ومحركات البحث</p>
                        </div>
                    </div>
                    <div class="s-card-body">

                        @foreach ($groups->get('site', collect()) as $setting)
                            @php $key = str_replace('site.', '', $setting->key) @endphp

                            <div class="field-row">
                                <div>
                                    <div class="field-label">{{ $setting->label }}</div>
                                    @if ($setting->description)
                                        <div class="field-sublabel">{{ $setting->description }}</div>
                                    @endif
                                </div>
                                <div>
                                    @if ($setting->type === 'boolean')
                                        <div class="toggle-wrap">
                                            <label class="toggle">
                                                <input type="hidden" name="{{ $key }}" value="0">
                                                <input type="checkbox" name="{{ $key }}" value="1"
                                                    {{ $setting->value == '1' ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">
                                                {{ $setting->value == '1' ? 'مُفعَّل' : 'معطَّل' }}
                                            </span>
                                        </div>
                                    @elseif($setting->type === 'textarea')
                                        <textarea name="{{ $key }}" class="s-input s-textarea" rows="3">{{ old($key, $setting->value) }}</textarea>
                                    @else
                                        <input type="{{ $setting->type === 'email' ? 'email' : 'text' }}"
                                            name="{{ $key }}" class="s-input"
                                            value="{{ old($key, $setting->value) }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="save-bar">
                    <div class="save-bar-inner">
                        <span style="font-size:0.85rem;color:#888;font-weight:600">
                            <i class="bi bi-info-circle me-1"></i>
                            التغييرات ستُطبَّق فور الحفظ
                        </span>
                        <button type="submit" class="btn-maroon">
                            <i class="bi bi-check-lg"></i> حفظ إعدادات الموقع
                        </button>
                    </div>
                </div>

            </form>

            {{-- ══════════════════════════════════════════════════════════════════
       TAB: CONTACT
  ══════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'contact')
            <form action="{{ route('admin.settings.save-group', 'contact') }}" method="POST">
                @csrf

                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-icon" style="background:rgba(37,211,102,0.1);color:#25D366">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <p class="s-card-title">بيانات التواصل</p>
                            <p class="s-card-desc">تظهر في صفحة "تواصل معنا" وتذييل الموقع</p>
                        </div>
                    </div>
                    <div class="s-card-body">

                        @foreach ($groups->get('contact', collect()) as $setting)
                            @php $key = str_replace('contact.', '', $setting->key) @endphp
                            <div class="field-row">
                                <div>
                                    <div class="field-label">{{ $setting->label }}</div>
                                    @if ($setting->description)
                                        <div class="field-sublabel">{{ $setting->description }}</div>
                                    @endif
                                </div>
                                <div>
                                    @php
                                        $inputType = match ($setting->type) {
                                            'email' => 'email',
                                            'tel' => 'tel',
                                            'url' => 'url',
                                            default => 'text',
                                        };
                                    @endphp
                                    <input type="{{ $inputType }}" name="{{ $key }}" class="s-input"
                                        value="{{ old($key, $setting->value) }}"
                                        @if ($key === 'whatsapp') placeholder="970591234567" @endif>
                                    @if ($key === 'whatsapp' && old($key, $setting->value))
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', old($key, $setting->value)) }}"
                                            target="_blank"
                                            style="display:inline-flex;align-items:center;gap:6px;margin-top:8px;font-size:0.8rem;color:#25D366;font-weight:700;text-decoration:none">
                                            <i class="bi bi-whatsapp"></i> اختبار الرابط
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="save-bar">
                    <div class="save-bar-inner">
                        <span style="font-size:0.85rem;color:#888;font-weight:600">
                            <i class="bi bi-whatsapp me-1" style="color:#25D366"></i>
                            رقم الواتساب يُستخدم في زر التواصل المباشر
                        </span>
                        <button type="submit" class="btn-maroon">
                            <i class="bi bi-check-lg"></i> حفظ بيانات التواصل
                        </button>
                    </div>
                </div>

            </form>

            {{-- ══════════════════════════════════════════════════════════════════
       TAB: SOCIAL
  ══════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'social')
            <form action="{{ route('admin.settings.save-group', 'social') }}" method="POST">
                @csrf

                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-icon" style="background:rgba(123,17,19,0.1);color:#7b1113">
                            <i class="bi bi-share-fill"></i>
                        </div>
                        <div>
                            <p class="s-card-title">روابط التواصل الاجتماعي</p>
                            <p class="s-card-desc">تظهر في تذييل الموقع — اتركها فارغة لإخفائها</p>
                        </div>
                    </div>
                    <div class="s-card-body">

                        @php
                            $socialIcons = [
                                'instagram' => ['bi-instagram', '#E1306C', 'bg:rgba(225,48,108,0.08)'],
                                'facebook' => ['bi-facebook', '#1877F2', 'background:rgba(24,119,242,0.08)'],
                                'tiktok' => ['bi-tiktok', '#010101', 'background:rgba(0,0,0,0.06)'],
                                'snapchat' => ['bi-snapchat', '#FFFC00', 'background:rgba(255,252,0,0.15)'],
                            ];
                        @endphp

                        @foreach ($groups->get('social', collect()) as $setting)
                            @php
                                $key = str_replace('social.', '', $setting->key);
                                $iconData = $socialIcons[$key] ?? ['bi-link-45deg', '#888', ''];
                            @endphp
                            <div class="field-row">
                                <div class="d-flex align-items-center gap-2 pt-2">
                                    <div
                                        style="width:34px;height:34px;border-radius:9px;{{ $iconData[2] }};display:flex;align-items:center;justify-content:center;color:{{ $iconData[1] }};font-size:1rem;flex-shrink:0">
                                        <i class="bi {{ $iconData[0] }}"></i>
                                    </div>
                                    <div class="field-label" style="padding-top:0">{{ $setting->label }}</div>
                                </div>
                                <div>
                                    <input type="url" name="{{ $key }}" class="s-input"
                                        value="{{ old($key, $setting->value) }}" placeholder="https://...">
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="save-bar">
                    <div class="save-bar-inner">
                        <span style="font-size:0.85rem;color:#888;font-weight:600">
                            <i class="bi bi-eye me-1"></i>
                            الروابط الفارغة لن تظهر في الموقع
                        </span>
                        <button type="submit" class="btn-maroon">
                            <i class="bi bi-check-lg"></i> حفظ روابط التواصل
                        </button>
                    </div>
                </div>

            </form>



            {{-- ══════════════════════════════════════════════════════════════════
       TAB: APPEARANCE
  ══════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'appearance')
            <form action="{{ route('admin.settings.save-group', 'appearance') }}" method="POST">
                @csrf

                <div class="s-card">
                    <div class="s-card-header">
                        <div class="s-card-icon" style="background:rgba(123,17,19,0.1);color:#7b1113">
                            <i class="bi bi-palette-fill"></i>
                        </div>
                        <div>
                            <p class="s-card-title">المظهر والتخصيص</p>
                            <p class="s-card-desc"> إعدادات العرض</p>
                        </div>
                    </div>
                    <div class="s-card-body">

                        @foreach ($groups->get('appearance', collect()) as $setting)
                            @php $key = str_replace('appearance.', '', $setting->key) @endphp
                            <div class="field-row">
                                <div>
                                    <div class="field-label">{{ $setting->label }}</div>
                                    @if ($setting->description)
                                        <div class="field-sublabel">{{ $setting->description }}</div>
                                    @endif
                                </div>
                                <div>
                                    @if ($setting->type === 'color')
                                        <div class="color-wrap">
                                            <div class="color-preview"
                                                style="background:{{ old($key, $setting->value) }}">
                                                <input type="color" name="{{ $key }}"
                                                    value="{{ old($key, $setting->value) }}"
                                                    oninput="this.parentElement.style.background=this.value">
                                            </div>
                                            <input type="text" value="{{ old($key, $setting->value) }}"
                                                class="s-input"
                                                style="max-width:120px;font-family:monospace;font-size:0.9rem"
                                                oninput="syncColor(this)" data-target="{{ $key }}">
                                        </div>
                                    @else
                                        <div style="display:flex;align-items:center;gap:10px">
                                            <input type="number" name="{{ $key }}" class="s-input"
                                                style="max-width:100px" value="{{ old($key, $setting->value) }}"
                                                min="6" max="48" step="6">
                                            <span style="font-size:0.85rem;color:#aaa">منتج / صفحة</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="save-bar">
                    <div class="save-bar-inner">
                        <span style="font-size:0.85rem;color:#888;font-weight:600">
                            <i class="bi bi-palette me-1"></i>
                            تغيير العدد يتطلب مسح الكاش بعد الحفظ
                        </span>
                        <button type="submit" class="btn-maroon">
                            <i class="bi bi-check-lg"></i> حفظ إعدادات المظهر
                        </button>
                    </div>
                </div>

            </form>

            {{-- ══════════════════════════════════════════════════════════════════
       TAB: ACCOUNT
  ══════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'account')
            <div class="s-card">
                <div class="s-card-header">
                    <div class="s-card-icon" style="background:rgba(123,17,19,0.1);color:#7b1113">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <p class="s-card-title">معلومات الحساب</p>
                        <p class="s-card-desc">بيانات مدير الموقع</p>
                    </div>
                </div>
                <div class="s-card-body">

                    {{-- Current user info --}}
                    <div class="d-flex align-items-center gap-4 p-4 rounded-4 mb-4" style="background:var(--cream)">
                        <div
                            style="width:60px;height:60px;border-radius:16px;background:#7b1113;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;font-weight:800;flex-shrink:0">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div style="font-size:1rem;font-weight:800;color:#422018">{{ auth()->user()->name }}</div>
                            <div style="font-size:0.85rem;color:#888">{{ auth()->user()->email }}</div>
                            <div style="font-size:0.78rem;color:#bbb;margin-top:3px">
                                مدير النظام · عضو منذ {{ auth()->user()->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Change Password --}}
            <div class="s-card" id="tab-account">
                <div class="s-card-header">
                    <div class="s-card-icon" style="background:rgba(212,175,55,0.12);color:#9a7d0a">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <p class="s-card-title">تغيير كلمة المرور</p>
                        <p class="s-card-desc">يُنصح بتغييرها كل 3 أشهر</p>
                    </div>
                </div>
                <div class="s-card-body">

                    @error('current_password')
                        <div
                            style="background:#fdf0f0;border-radius:12px;padding:12px 16px;margin-bottom:16px;color:#7b1113;font-size:0.88rem;font-weight:700">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $message }}
                        </div>
                    @enderror

                    <form action="{{ route('admin.settings.password') }}" method="POST">
                        @csrf

                        <div class="field-row">
                            <div class="field-label">كلمة المرور الحالية *</div>
                            <div style="position:relative">
                                <input type="password" name="current_password" class="s-input" style="padding-left:44px"
                                    placeholder="••••••••" required>
                                <button type="button" onclick="togglePass(this)"
                                    style="position:absolute;left:13px;top:50%;transform:translateY(-50%);background:none;border:none;color:#bbb;cursor:pointer;font-size:1rem">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="field-label">كلمة المرور الجديدة *</div>
                            <div>
                                <div style="position:relative;margin-bottom:10px">
                                    <input type="password" name="new_password" id="newPass" class="s-input"
                                        style="padding-left:44px" placeholder="8 أحرف على الأقل" minlength="8" required
                                        oninput="checkStrength(this.value)">
                                    <button type="button" onclick="togglePass(this)"
                                        style="position:absolute;left:13px;top:50%;transform:translateY(-50%);background:none;border:none;color:#bbb;cursor:pointer;font-size:1rem">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                {{-- Strength bar --}}
                                <div id="strengthBar"
                                    style="height:5px;border-radius:10px;background:#f0e8e0;transition:0.3s;overflow:hidden">
                                    <div id="strengthFill"
                                        style="height:100%;width:0%;transition:0.3s;border-radius:10px"></div>
                                </div>
                                <div id="strengthLabel" style="font-size:0.75rem;color:#bbb;margin-top:4px"></div>
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="field-label">تأكيد كلمة المرور *</div>
                            <div>
                                <input type="password" name="new_password_confirmation" class="s-input"
                                    placeholder="أعد كتابة كلمة المرور الجديدة" required>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn-maroon">
                                <i class="bi bi-shield-lock"></i> تغيير كلمة المرور
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════
       TAB: MAINTENANCE
  ══════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'maintenance')
            {{-- System Stats --}}
            <div class="s-card mb-4">
                <div class="s-card-header">
                    <div class="s-card-icon" style="background:rgba(26,106,138,0.1);color:#1a6b8a">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <div>
                        <p class="s-card-title">إحصاءات النظام</p>
                        <p class="s-card-desc">نظرة سريعة على حالة قاعدة البيانات</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="row g-3">
                        @foreach ([['الرسائل المقروءة', $stats['messages_read'], '#1a8a4a', 'bi-envelope-check-fill'], ['الرسائل غير المقروءة', $stats['messages_unread'], '#d35400', 'bi-envelope-exclamation-fill'], ['إجمالي الطلبات', $stats['orders_total'], '#1a6b8a', 'bi-bag-fill'], ['مفاتيح الإعدادات', $stats['cache_keys'], '#9a7d0a', 'bi-sliders']] as [$label, $count, $color, $icon])
                            <div class="col-6 col-md-3">
                                <div
                                    style="text-align:center;padding:20px 12px;background:var(--cream);border-radius:14px">
                                    <i class="bi {{ $icon }}"
                                        style="font-size:1.6rem;color:{{ $color }};display:block;margin-bottom:8px"></i>
                                    <div style="font-size:1.6rem;font-weight:900;color:{{ $color }}">
                                        {{ $count }}</div>
                                    <div style="font-size:0.78rem;color:#aaa;font-weight:700;margin-top:3px">
                                        {{ $label }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Danger Actions --}}
            <div class="s-card">
                <div class="s-card-header">
                    <div class="s-card-icon" style="background:rgba(123,17,19,0.1);color:#7b1113">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <p class="s-card-title">إجراءات الصيانة</p>
                        <p class="s-card-desc text-maroon fw-bold">⚠️ هذه الإجراءات لا يمكن التراجع عنها</p>
                    </div>
                </div>
                <div class="s-card-body">

                    <div class="danger-action">
                        <div>
                            <div style="font-weight:800;color:#422018;font-size:0.92rem;margin-bottom:3px">
                                <i class="bi bi-arrow-clockwise me-2" style="color:#1a6b8a"></i>
                                مسح كاش الإعدادات
                            </div>
                            <div style="font-size:0.8rem;color:#aaa">يُطبَّق عند تغيير الإعدادات يدوياً أو إضافة سجلات
                                مباشرة</div>
                        </div>
                        <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                            @csrf
                            <button class="btn-action" style="background:#eaf4fb;color:#1a6b8a;white-space:nowrap">
                                <i class="bi bi-arrow-clockwise"></i> مسح الكاش
                            </button>
                        </form>
                    </div>

                    <div class="danger-action">
                        <div>
                            <div style="font-weight:800;color:#422018;font-size:0.92rem;margin-bottom:3px">
                                <i class="bi bi-trash3-fill me-2" style="color:#d35400"></i>
                                حذف الرسائل المقروءة
                            </div>
                            <div style="font-size:0.8rem;color:#aaa">
                                سيُحذف {{ $stats['messages_read'] }} رسالة مقروءة — وتبقى {{ $stats['messages_unread'] }}
                                غير مقروءة
                            </div>
                        </div>
                        <form action="{{ route('admin.settings.clear-messages') }}" method="POST"
                            onsubmit="return confirm('حذف {{ $stats['messages_read'] }} رسالة مقروءة؟')">
                            @csrf
                            <button class="btn-action" style="background:#fdf0f0;color:#d35400;white-space:nowrap">
                                <i class="bi bi-trash3"></i> حذف المقروءة
                            </button>
                        </form>
                    </div>

                    <div class="danger-action" style="border-color:#fde0d0">
                        <div>
                            <div style="font-weight:800;color:#7b1113;font-size:0.92rem;margin-bottom:3px">
                                <i class="bi bi-power me-2"></i>
                                تسجيل الخروج من الجهاز الحالي
                            </div>
                            <div style="font-size:0.8rem;color:#aaa">ستحتاج لإعادة تسجيل الدخول</div>
                        </div>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button class="btn-action" style="background:#fdf0f0;color:#7b1113;white-space:nowrap">
                                <i class="bi bi-box-arrow-right"></i> خروج
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        @endif {{-- end tab checks --}}

    </div>
@endsection

@push('scripts')
    <script>
        // ── Password visibility toggle ─────────────────────────────────────
        function togglePass(btn) {
            const inp = btn.previousElementSibling || btn.parentElement.previousElementSibling;
            const icon = btn.querySelector('i');
            if (!inp) return;
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
        }

        // ── Password strength meter ────────────────────────────────────────
        function checkStrength(val) {
            const fill = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');
            if (!fill || !label) return;

            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            if (val.length >= 12) score++;

            const levels = [{
                    w: '0%',
                    c: '#f0e8e0',
                    t: ''
                },
                {
                    w: '25%',
                    c: '#d35400',
                    t: 'ضعيفة جداً'
                },
                {
                    w: '50%',
                    c: '#d4af37',
                    t: 'متوسطة'
                },
                {
                    w: '75%',
                    c: '#1a8a4a',
                    t: 'جيدة'
                },
                {
                    w: '100%',
                    c: '#1e4d2b',
                    t: 'قوية جداً ✅'
                },
            ];

            const lvl = levels[Math.min(score, 4)];
            fill.style.width = lvl.w;
            fill.style.background = lvl.c;
            label.textContent = lvl.t;
            label.style.color = lvl.c;
        }

        // ── Color picker sync ──────────────────────────────────────────────
        function syncColor(textInput) {
            const key = textInput.dataset.target;
            const picker = document.querySelector(`input[type="color"][name="${key}"]`);
            if (picker && /^#[0-9a-fA-F]{6}$/.test(textInput.value)) {
                picker.value = textInput.value;
                picker.parentElement.style.background = textInput.value;
            }
        }

        // ── Auto-hide alerts ───────────────────────────────────────────────
        document.querySelectorAll('.alert').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity 0.5s, transform 0.5s';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(() => el.remove(), 500);
            }, 4000);
        });
    </script>
@endpush
