FROM webdevops/php-nginx:8.2

WORKDIR /app

# نسخ الملفات مع ضبط المالك (مهم جداً للصلاحيات)
COPY --chown=application:application . /app

# تثبيت npm + تجهيز المشروع
# نستخدم && لدمج الأوامر عشان توفر مساحة ووقت
RUN apt-get update && \
    apt-get install -y nodejs npm && \
    rm -rf /var/lib/apt/lists/* && \
    cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install --legacy-peer-deps && \
    npm run build && \
    # منح صلاحيات الكتابة (عشان حل مشكلة 403 والتخزين)
    chmod -R 775 storage bootstrap/cache && \
    chown -R application:application storage bootstrap/cache

# إعداد متغير البيئة ليخبر الويب سيرفر بالمسار الصحيح (الحل السحري للـ 403)
# هذا أهم سطر: يوجه الويب سيرفر لمجلد public مباشرة
ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 8080

# تشغيل Migrations تلقائياً عند بدء التشغيل (بدون الحاجة لـ Shell)
# نستخدم ENTRYPOINT لتمرير أمر قبل تشغيل الخدمات
ENTRYPOINT ["sh", "-c", "php artisan migrate --force && /entrypoint.d/20-php-fpm.sh && /entrypoint.d/30-nginx.sh"]
