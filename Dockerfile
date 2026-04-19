FROM webdevops/php-nginx:8.2-alpine
# تثبيت Node.js و npm
USER root
RUN apt-get update && apt-get install -y nodejs npm git unzip && rm -rf /var/lib/apt/lists/*
USER application

WORKDIR /app
COPY --chown=application:application . .

# زيادة حد الذاكرة لـ PHP مؤقتاً لـ Composer
ENV COMPOSER_MEMORY_LIMIT=-1
ENV PHP_MEMORY_LIMIT=512M

# 1. تثبيت مكتبات PHP (بدون scripts لتوفير الذاكرة)
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs && \
    php artisan key:generate && \
    php artisan storage:link

# 2. بناء الـ Assets (CSS/JS)
RUN npm install && npm run build

EXPOSE 8080

# 3. تشغيل Migrations ثم الموقع
CMD php artisan migrate --force && php-fpm
