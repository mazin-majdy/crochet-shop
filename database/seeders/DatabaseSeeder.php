<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\ContactMessage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 جاري تشغيل البيانات التجريبية...');

        // ─── مدير الموقع ──────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@lamsitkhait.com'],
            [
                'name'     => 'مدير لمسة خيط',
                'password' => Hash::make('Admin@123456'),
            ]
        );
        $this->command->info('✅ تم إنشاء حساب المدير: admin@lamsitkhait.com');

        // ─── منتجات تجريبية ───────────────────────────
        $products = [
            // التطريز
            [
                'name'        => 'شال تطريز فلسطيني أصيل',
                'description' => 'شال مصنوع من قماش الكتان الطبيعي مع تطريز فلسطيني تقليدي بألوان حمراء وسوداء وبيضاء. قطعة تراثية فاخرة تعكس جمال الموروث الفلسطيني.',
                'price'       => 280,
                'category'    => 'embroidery',
                'target'      => 'women',
                'tags'        => 'تطريز, فلسطيني, كتان, شال',
                'is_featured' => true,
            ],
            [
                'name'        => 'ثوب غرزة الفلاحي المطرز',
                'description' => 'ثوب فلسطيني تقليدي مطرز يدوياً بأجمل غرزات الفلاحي. مناسب للمناسبات الخاصة والأعراس.',
                'price'       => 650,
                'category'    => 'embroidery',
                'target'      => 'women',
                'tags'        => 'ثوب, فلسطيني, تطريز يدوي, عروس',
                'is_featured' => true,
            ],
            [
                'name'        => 'منديل تطريز بالحرير',
                'description' => 'منديل أنيق من قماش الحرير الطبيعي مع تطريز رقيق بخيوط ذهبية. هدية راقية لكل مناسبة.',
                'price'       => 95,
                'category'    => 'embroidery',
                'target'      => 'general',
                'tags'        => 'منديل, حرير, تطريز, هدية',
                'is_featured' => false,
            ],
            [
                'name'        => 'حقيبة يد مطرزة بالخرز',
                'description' => 'حقيبة يد عصرية مزينة بتطريز يدوي وخرز ملون. تجمع بين الأصالة والعصرية.',
                'price'       => 180,
                'category'    => 'embroidery',
                'target'      => 'girls',
                'tags'        => 'حقيبة, خرز, تطريز, عصري',
                'is_featured' => true,
            ],

            // الأشغال اليدوية
            [
                'name'        => 'طقم كروشيه للأطفال',
                'description' => 'طقم كروشيه للأطفال يشمل قبعة، وجوارب، وقفازات. مصنوع من خيوط قطنية ناعمة آمنة للأطفال وبألوان زاهية.',
                'price'       => 120,
                'category'    => 'handicraft',
                'target'      => 'kids',
                'tags'        => 'كروشيه, أطفال, قبعة, هدية',
                'is_featured' => true,
            ],
            [
                'name'        => 'سلة خرازة جلدية مطرزة',
                'description' => 'سلة مصنوعة يدوياً من الجلد الطبيعي مع تطريز ملون على الجوانب. عملية وجميلة.',
                'price'       => 220,
                'category'    => 'handicraft',
                'target'      => 'women',
                'tags'        => 'جلد, خرازة, سلة, يدوي',
                'is_featured' => false,
            ],
            [
                'name'        => 'دمية قماش يدوية',
                'description' => 'دمية قماش يدوية الصنع بتصميمات مختلفة. هدية رائعة للأطفال والمقتنيات.',
                'price'       => 75,
                'category'    => 'handicraft',
                'target'      => 'kids',
                'tags'        => 'دمية, قماش, هدية, أطفال',
                'is_featured' => false,
            ],

            // أعمال الصوف
            [
                'name'        => 'بطانية صوف كروشيه شتوية',
                'description' => 'بطانية دافئة ومريحة مصنوعة بتقنية الكروشيه من صوف عالي الجودة. متوفرة بعدة ألوان وأحجام.',
                'price'       => 350,
                'category'    => 'wool',
                'target'      => 'general',
                'tags'        => 'بطانية, صوف, كروشيه, شتاء',
                'is_featured' => true,
            ],
            [
                'name'        => 'شال صوف مرينو فاخر',
                'description' => 'شال من صوف المرينو الطبيعي الفاخر. ناعم جداً على البشرة ودافئ للشتاء. تصميم عصري أنيق.',
                'price'       => 195,
                'category'    => 'wool',
                'target'      => 'women',
                'tags'        => 'شال, مرينو, صوف طبيعي, شتاء',
                'is_featured' => true,
            ],
            [
                'name'        => 'كنزة صوف للشباب',
                'description' => 'كنزة شبابية مصنوعة يدوياً من خيوط الصوف بتصاميم جيومترية عصرية. دافئة وأنيقة.',
                'price'       => 280,
                'category'    => 'wool',
                'target'      => 'men',
                'tags'        => 'كنزة, صوف, شباب, شتاء',
                'is_featured' => false,
            ],
            [
                'name'        => 'طقم تريكو أطفال ملوّن',
                'description' => 'طقم تريكو للأطفال من عمر 1-3 سنوات يشمل كنزة وبنطال. خيوط ناعمة ومريحة وبألوان مبهجة.',
                'price'       => 145,
                'category'    => 'wool',
                'target'      => 'kids',
                'tags'        => 'تريكو, أطفال, طقم, ملون',
                'is_featured' => true,
            ],
            [
                'name'        => 'لفحة شبابية كروشيه',
                'description' => 'لفحة شبابية عصرية مصنوعة بتقنية الكروشيه من خيوط صوف ملونة. تناسب الشباب والبنات.',
                'price'       => 85,
                'category'    => 'wool',
                'target'      => 'girls',
                'tags'        => 'لفحة, كروشيه, شباب, ملون',
                'is_featured' => false,
            ],
        ];



        foreach ($products as $productData) {
            $productData['slug'] = Str::slug($productData['name'] . '-' . Str::random(5));
            $productData['is_featured'] = $productData['is_featured'] ?? false;
            $productData['is_active'] = true;

            Product::firstOrCreate(['name' => $productData['name']], $productData);
        }
        $this->command->info('✅ تم إضافة ' . count($products) . ' منتج تجريبي');

        // ─── رسائل تجريبية ────────────────────────────
        $messages = [
            [
                'name'    => 'سارة أحمد',
                'phone'   => '0591234567',
                'email'   => 'sara@example.com',
                'subject' => 'order',
                'message' => 'السلام عليكم، أريد طلب شال التطريز الفلسطيني الأصيل هل متوفر؟ وما هي طريقة الدفع؟',
                'is_read' => false,
            ],
            [
                'name'    => 'خديجة محمد',
                'phone'   => '0599876543',
                'subject' => 'custom',
                'message' => 'أريد طلب ثوب مطرز مخصص لحفل زفافي، هل يمكن تصميم نمط خاص؟',
                'is_read' => false,
            ],
            [
                'name'    => 'أم أحمد',
                'phone'   => '0568765432',
                'subject' => 'inquiry',
                'message' => 'عندي استفسار عن البطانية الصوفية - هل هي متوفرة بالأحمر والبيج؟',
                'is_read' => true,
            ],
        ];

        foreach ($messages as $msg) {
            ContactMessage::firstOrCreate(
                ['phone' => $msg['phone'], 'message' => $msg['message']],
                $msg
            );
        }
        $this->command->info('✅ تم إضافة ' . count($messages) . ' رسالة تجريبية');

        $this->command->info('🎉 اكتمل تشغيل السييدر بنجاح!');
    }
}
