FROM webdevops/php-nginx:8.2

WORKDIR /app

# نسخ الملفات مع ضبط المالك
COPY --chown=application:application . /app

# إنشاء سكريبت تشغيل تلقائي للمهاجريشن والـ Seeder
RUN mkdir -p /docker-entrypoint.d && \
    echo '#!/bin/bash\nphp artisan migrate --force --seed' > /docker-entrypoint.d/99-migrate.sh && \
    chmod +x /docker-entrypoint.d/99-migrate.sh

# تثبيت وتجهيز كل شي
# 👇 مهم: نمرر المتغيرات لـ npm build عشان Vite يعرف المسار الصحيح
RUN apt-get update && \
    apt-get install -y --no-install-recommends nodejs npm && \
    rm -rf /var/lib/apt/lists/* && \
    cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link && \
    # 👇 نمرر APP_URL و ASSET_URL لـ Vite وقت البناء
    export APP_URL=${APP_URL:-https://crochet-shop-y5ii.onrender.com} && \
    export ASSET_URL=${ASSET_URL:-https://crochet-shop-y5ii.onrender.com} && \
    npm install --legacy-peer-deps && \
    npm run build && \
    chmod -R 775 storage bootstrap/cache public/build && \
    chown -R application:application storage bootstrap/cache public/build

# توجيه الويب سيرفر لمجلد public
ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 8080
