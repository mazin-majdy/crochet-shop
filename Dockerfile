FROM webdevops/php-nginx:8.2-alpine

# نسخ ملفات المشروع
COPY --chown=application:application . /app
WORKDIR /app

# تثبيت التبعيات وتجهيز المشروع
# نستخدم --ignore-platform-reqs لتجنب أخطاء الذاكرة على Render Free
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install && \
    npm run build

# exposing port 8080 (المنفذ الافتراضي لـ Render)
EXPOSE 8080

# ملاحظة: ما نحتاج نكتب CMD لأن الصورة مهيأة مسبقاً
# لكن نحتاج ننفذ migrate قبل ما يقلع الموقع
# الحل: نستخدم entrypoint بسيط
ENTRYPOINT ["sh", "-c", "php artisan migrate --force && /entrypoint.d/20-php-fpm.sh && /entrypoint.d/30-nginx.sh"]
