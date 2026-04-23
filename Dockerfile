FROM webdevops/php-nginx:8.2

WORKDIR /app

# نسخ الملفات مع ضبط المالك
COPY --chown=application:application . /app

# إنشاء سكريبت تشغيل تلقائي للمهاجريشن والـ Seeder
RUN mkdir -p /docker-entrypoint.d && \
    echo '#!/bin/bash\nphp artisan migrate --force --seed' > /docker-entrypoint.d/99-migrate.sh && \
    chmod +x /docker-entrypoint.d/99-migrate.sh

# تثبيت وتجهيز كل شي
RUN apt-get update && \
    apt-get install -y --no-install-recommends nodejs npm && \
    rm -rf /var/lib/apt/lists/* && \
    cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install --legacy-peer-deps && \
    npm run build && \
    # 👇 مهم جداً: صلاحيات مجلد public
    chmod -R 755 public && \
    chmod -R 775 storage bootstrap/cache && \
    chown -R application:application storage bootstrap/cache public

# 👇 فرض HTTPS والصلاحيات
ENV WEB_DOCUMENT_ROOT=/app/public
ENV APP_URL=https://crochet-shop-y5ii.onrender.com
ENV ASSET_URL=https://crochet-shop-y5ii.onrender.com
ENV TRUSTED_PROXIES=*

EXPOSE 8080
