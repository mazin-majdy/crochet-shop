FROM webdevops/php-nginx:8.2

WORKDIR /app

# نسخ الملفات مع ضبط المالك
COPY --chown=application:application . /app

# خطوة وحدة تجمع: تثبيت npm + تجهيز Laravel + بناء الواجهة + الصلاحيات
# دمجنا كل شي بـ RUN واحد عشان نتجنب مشاكل الكاش ونضمن إن npm مثبت قبل استخدامه
RUN apt-get update && \
    apt-get install -y --no-install-reremends nodejs npm && \
    rm -rf /var/lib/apt/lists/* && \
    cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install --legacy-peer-deps && \
    npm run build && \
    chmod -R 775 storage bootstrap/cache && \
    chown -R application:application storage bootstrap/cache

# توجيه الويب سيرفر لمجلد public مباشرة (حل مشكلة 403)
ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 8080

# تشغيل Migrations تلقائياً ثم رفع الخدمات
ENTRYPOINT ["sh", "-c", "php artisan migrate --force && /entrypoint.d/20-php-fpm.sh && /entrypoint.d/30-nginx.sh"]
