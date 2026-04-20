FROM webdevops/php-nginx:8.2

WORKDIR /app

# نسخ الملفات مع ضبط المالك
COPY --chown=application:application . /app

# إنشاء سكريبت تشغيل تلقائي للمهاجريشن (الطريقة الرسمية لـ webdevops)
# أي ملف هنا رح ينفذ تلقائياً قبل ما يقلع السيرفر
RUN mkdir -p /docker-entrypoint.d && \
    echo '#!/bin/bash\nphp artisan migrate --force' > /docker-entrypoint.d/99-migrate.sh && \
    chmod +x /docker-entrypoint.d/99-migrate.sh

# تثبيت وتجهيز كل شي في خطوة وحدة
RUN apt-get update && \
    apt-get install -y --no-install-recommends nodejs npm && \
    rm -rf /var/lib/apt/lists/* && \
    cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install --legacy-peer-deps && \
    npm run build && \
    chmod -R 775 storage bootstrap/cache && \
    chown -R application:application storage bootstrap/cache

# توجيه الويب سيرفر لمجلد public (حل مشكلة 403)
ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 8080

# ⚠️ لا تكتب CMD أو ENTRYPOINT: الصورة مهيأة مسبقاً وستشغل الخدمات تلقائياً
