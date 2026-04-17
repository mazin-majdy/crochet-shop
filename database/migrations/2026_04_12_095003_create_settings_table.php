<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // المفتاح: مجموعة.اسم  مثل: site.name  أو  social.instagram
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('site')->index();  // site | social | contact | payment | appearance
            $table->string('type', 20)->default('text');            // text | textarea | boolean | color | email | url | tel | image
            $table->string('label', 150);                           // الاسم المعروض
            $table->text('description')->nullable();                // وصف اختياري
            $table->unsignedSmallInteger('sort')->default(0);       // ترتيب العرض
            $table->timestamps();
        });

        // ── Seed default settings ─────────────────────────────────────────
        $now = now();
        $defaults = [

            // ── Site group ──────────────────────────────────────────────
            ['key' => 'site.name',          'value' => 'لمسة خيط',               'group' => 'site',       'type' => 'text',     'label' => 'اسم الموقع',               'description' => 'يظهر في التبويب والـ SEO',       'sort' => 1],
            ['key' => 'site.tagline',       'value' => 'نصنع الجمال يدوياً ✨',   'group' => 'site',       'type' => 'text',     'label' => 'شعار الموقع (Tagline)',     'description' => 'جملة قصيرة تحت اسم الموقع',     'sort' => 2],
            [
                'key' => 'site.description',
                'value' => 'متجر متخصص في الأشغال اليدوية، التطريز، وأعمال الصوف. كل قطعة تُصنع بحبٍّ وإتقان.',
                'group' => 'site',
                'type' => 'textarea',
                'label' => 'وصف الموقع (SEO)',
                'description' => 'يظهر في نتائج محركات البحث',
                'sort' => 3
            ],
            ['key' => 'site.maintenance',   'value' => '0',                       'group' => 'site',       'type' => 'boolean',  'label' => 'وضع الصيانة',               'description' => 'عند التفعيل يُخفى الموقع العام', 'sort' => 4],

            // ── Contact group ───────────────────────────────────────────
            ['key' => 'contact.whatsapp',   'value' => '970591234567',            'group' => 'contact',    'type' => 'tel',      'label' => 'رقم الواتساب',              'description' => 'بدون + أو 00 — مثال: 970591234567', 'sort' => 1],
            ['key' => 'contact.email',      'value' => 'info@lamsitkhait.com',    'group' => 'contact',    'type' => 'email',    'label' => 'البريد الإلكتروني',         'description' => 'يظهر في صفحة تواصل معنا',       'sort' => 2],
            ['key' => 'contact.address',    'value' => 'فلسطين',                  'group' => 'contact',    'type' => 'text',     'label' => 'العنوان / الموقع الجغرافي', 'description' => 'يظهر في تذييل الصفحة',          'sort' => 4],
            ['key' => 'contact.hours',      'value' => 'السبت – الخميس: 9 ص – 9 م', 'group' => 'contact',  'type' => 'text',     'label' => 'أوقات العمل',               'description' => '',                               'sort' => 5],

            // ── Social group ────────────────────────────────────────────
            ['key' => 'social.instagram',   'value' => '',                        'group' => 'social',     'type' => 'url',      'label' => 'رابط إنستغرام',             'description' => '',                               'sort' => 1],
            ['key' => 'social.facebook',    'value' => '',                        'group' => 'social',     'type' => 'url',      'label' => 'رابط فيسبوك',               'description' => '',                               'sort' => 2],


            // ── Appearance group ────────────────────────────────────────
            ['key' => 'appearance.products_per_page', 'value' => '12',            'group' => 'appearance', 'type' => 'text',     'label' => 'عدد المنتجات في الصفحة',    'description' => 'من 6 إلى 48',                    'sort' => 3],
        ];

        foreach ($defaults as $row) {
            DB::table('settings')->insert(array_merge($row, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
