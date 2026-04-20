FROM webdevops/php-nginx:8.2

WORKDIR /app

# نسخ الملفات مع ضبط المالك أثناء البناء (مسموح)
COPY --chown=application:application . /app

# كل التجهيزات بتتم وقت البناء (كـ root تلقائياً - مسموح)
RUN cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install --legacy-peer-deps && \
    npm run build

EXPOSE 8080

# ⚠️ ملاحظة مهمة: ما نكتب CMD نهائياً!
# الصورة مهيأة مسبقاً بتشغل Nginx + PHP-FPM تلقائياً
# والمهاجريشن رح نشغلها يدوياً من الـ Shell بعد النشر
