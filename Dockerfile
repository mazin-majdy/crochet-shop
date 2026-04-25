FROM webdevops/php-nginx:8.2

WORKDIR /app

# نسخ كل الملفات
COPY --chown=application:application . /app

# إنشاء سكريبت المهاجريشن
RUN mkdir -p /docker-entrypoint.d && \
    echo '#!/bin/bash\nphp artisan migrate --force --seed' > /docker-entrypoint.d/99-migrate.sh && \
    chmod +x /docker-entrypoint.d/99-migrate.sh

# التثبيت والتجهيز
RUN apt-get update && \
    apt-get install -y --no-install-recommends nodejs npm && \
    rm -rf /var/lib/apt/lists/* && \
    cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install --legacy-peer-deps && \
    npm run build && \
    # 👇 أهم سطر: صلاحيات مجلد public
    chmod -R 755 public && \
    chmod -R 775 storage bootstrap/cache && \
    chown -R application:application .

# إعدادات البيئة
ENV WEB_DOCUMENT_ROOT=/app/public
ENV APP_URL=https://crochet-shop-y5ii.onrender.com
ENV ASSET_URL=https://crochet-shop-y5ii.onrender.com

RUN echo "* * * * * cd /app && php artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/laravel-scheduler && \
    chmod 0644 /etc/cron.d/laravel-scheduler && \
    crontab /etc/cron.d/laravel-scheduler

EXPOSE 8080
