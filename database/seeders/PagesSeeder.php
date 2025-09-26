<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProtectedPage;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'title_ar' => 'من نحن',
                'slug' => 'about-us',
                'content' => 'Welcome to QAADS, your trusted partner for premium design and print solutions. We are a leading company specializing in creative design services and high-quality printing solutions.

Our team of experienced designers and printing professionals work together to deliver exceptional results that exceed our clients\' expectations. We believe in the power of great design and its ability to transform businesses and create lasting impressions.

With years of experience in the industry, we have built a reputation for excellence, reliability, and innovation. Our commitment to quality and customer satisfaction drives everything we do.',
                'content_ar' => 'مرحباً بك في قعدس، شريكك الموثوق لحلول التصميم والطباعة المتميزة. نحن شركة رائدة متخصصة في خدمات التصميم الإبداعي وحلول الطباعة عالية الجودة.

يعمل فريقنا من المصممين ذوي الخبرة ومتخصصي الطباعة معاً لتقديم نتائج استثنائية تتجاوز توقعات عملائنا. نؤمن بقوة التصميم العظيم وقدرته على تحويل الأعمال وخلق انطباعات دائمة.

مع سنوات من الخبرة في الصناعة، بنينا سمعة في التميز والموثوقية والابتكار. التزامنا بالجودة ورضا العملاء يدفع كل ما نقوم به.',
                'type' => 'about',
                'access_level' => 'public',
                'is_active' => true,
                'sort_order' => 1,
                'meta_title' => 'About Us - QAADS',
                'meta_title_ar' => 'من نحن - قعدس',
                'meta_description' => 'Learn about QAADS, your trusted partner for premium design and print solutions. Discover our story, values, and commitment to excellence.',
                'meta_description_ar' => 'تعرف على قعدس، شريكك الموثوق لحلول التصميم والطباعة المتميزة. اكتشف قصتنا وقيمنا والتزامنا بالتميز.',
                'meta_keywords' => 'about us, company, design, printing, quality, excellence',
                'meta_keywords_ar' => 'من نحن، شركة، تصميم، طباعة، جودة، تميز',
            ],
            [
                'title' => 'Terms of Service',
                'title_ar' => 'شروط الخدمة',
                'slug' => 'terms-of-service',
                'content' => 'These Terms of Service ("Terms") govern your use of our website and services. By accessing or using our services, you agree to be bound by these Terms.

1. Acceptance of Terms
By using our services, you acknowledge that you have read, understood, and agree to be bound by these Terms.

2. Use of Services
You may use our services for lawful purposes only. You agree not to use our services in any way that could damage, disable, or impair our services.

3. Intellectual Property
All content, designs, and materials provided through our services are protected by intellectual property laws. You may not reproduce, distribute, or create derivative works without our written permission.

4. Limitation of Liability
To the maximum extent permitted by law, QAADS shall not be liable for any indirect, incidental, or consequential damages.

5. Changes to Terms
We reserve the right to modify these Terms at any time. Changes will be effective immediately upon posting.',
                'content_ar' => 'تحكم شروط الخدمة هذه ("الشروط") استخدامك لموقعنا وخدماتنا. من خلال الوصول إلى خدماتنا أو استخدامها، فإنك توافق على الالتزام بهذه الشروط.

1. قبول الشروط
باستخدام خدماتنا، فإنك تقر بأنك قد قرأت وفهمت ووافقت على الالتزام بهذه الشروط.

2. استخدام الخدمات
يمكنك استخدام خدماتنا للأغراض القانونية فقط. توافق على عدم استخدام خدماتنا بأي طريقة قد تضر أو تعطل أو تضعف خدماتنا.

3. الملكية الفكرية
جميع المحتويات والتصاميم والمواد المقدمة من خلال خدماتنا محمية بقوانين الملكية الفكرية. لا يجوز لك نسخ أو توزيع أو إنشاء أعمال مشتقة دون إذن كتابي منا.

4. تحديد المسؤولية
إلى أقصى حد يسمح به القانون، لن تكون قعدس مسؤولة عن أي أضرار غير مباشرة أو عرضية أو تبعية.

5. تغييرات على الشروط
نحتفظ بالحق في تعديل هذه الشروط في أي وقت. ستكون التغييرات سارية فور النشر.',
                'type' => 'terms',
                'access_level' => 'public',
                'is_active' => true,
                'sort_order' => 2,
                'meta_title' => 'Terms of Service - QAADS',
                'meta_title_ar' => 'شروط الخدمة - قعدس',
                'meta_description' => 'Read our Terms of Service to understand the rules and guidelines for using QAADS services.',
                'meta_description_ar' => 'اقرأ شروط الخدمة الخاصة بنا لفهم القواعد والإرشادات لاستخدام خدمات قعدس.',
                'meta_keywords' => 'terms of service, legal, agreement, conditions',
                'meta_keywords_ar' => 'شروط الخدمة، قانوني، اتفاقية، شروط',
            ],
            [
                'title' => 'Privacy Policy',
                'title_ar' => 'سياسة الخصوصية',
                'slug' => 'privacy-policy',
                'content' => 'At QAADS, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, and safeguard your information.

1. Information We Collect
We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.

2. How We Use Your Information
We use your information to provide and improve our services, process transactions, and communicate with you.

3. Information Sharing
We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.

4. Data Security
We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.

5. Your Rights
You have the right to access, update, or delete your personal information. You may also opt out of certain communications from us.

6. Changes to This Policy
We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page.',
                'content_ar' => 'في قعدس، نحن ملتزمون بحماية خصوصيتك وضمان أمان معلوماتك الشخصية. توضح سياسة الخصوصية هذه كيفية جمع واستخدام وحماية معلوماتك.

1. المعلومات التي نجمعها
نجمع المعلومات التي تقدمها لنا مباشرة، مثل عندما تنشئ حساباً أو تقوم بعملية شراء أو تتصل بنا للحصول على الدعم.

2. كيفية استخدام معلوماتك
نستخدم معلوماتك لتقديم وتحسين خدماتنا ومعالجة المعاملات والتواصل معك.

3. مشاركة المعلومات
لا نبيع أو نتاجر أو ننقل معلوماتك الشخصية إلى أطراف ثالثة دون موافقتك، باستثناء ما هو موضح في هذه السياسة.

4. أمان البيانات
نطبق تدابير أمنية مناسبة لحماية معلوماتك الشخصية من الوصول غير المصرح به أو التغيير أو الكشف أو التدمير.

5. حقوقك
لديك الحق في الوصول إلى معلوماتك الشخصية أو تحديثها أو حذفها. يمكنك أيضاً إلغاء الاشتراك في بعض الاتصالات منا.

6. تغييرات على هذه السياسة
قد نقوم بتحديث سياسة الخصوصية هذه من وقت لآخر. سنخطرك بأي تغييرات عن طريق نشر السياسة الجديدة على هذه الصفحة.',
                'type' => 'privacy',
                'access_level' => 'public',
                'is_active' => true,
                'sort_order' => 3,
                'meta_title' => 'Privacy Policy - QAADS',
                'meta_title_ar' => 'سياسة الخصوصية - قعدس',
                'meta_description' => 'Learn how QAADS protects your privacy and handles your personal information. Read our comprehensive Privacy Policy.',
                'meta_description_ar' => 'تعرف على كيفية حماية قعدس لخصوصيتك والتعامل مع معلوماتك الشخصية. اقرأ سياسة الخصوصية الشاملة الخاصة بنا.',
                'meta_keywords' => 'privacy policy, data protection, personal information, security',
                'meta_keywords_ar' => 'سياسة الخصوصية، حماية البيانات، معلومات شخصية، أمان',
            ],
        ];

        foreach ($pages as $page) {
            ProtectedPage::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
