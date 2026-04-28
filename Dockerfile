# استخدم صورة PHP الرسمية مع nginx
FROM webdevops/php-nginx:8.2

# اذهب إلى مجلد التطبيق
WORKDIR /app

# انسخ الملفات
COPY . /app

# ثبت Node.js و npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# ثبت التبعيات
RUN composer install --no-dev --optimize-autoloader --no-scripts \
    && npm install --legacy-peer-deps \
    && npm run build

# أنشئ ملف .env
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# صلاحيات
RUN chown -R www-data:www-data /app \
    && chmod -R 755 storage bootstrap/cache

# قم بإنشاء مفتاح التطبيق
RUN php artisan key:generate \
    && php artisan storage:link \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# المنافذ
EXPOSE 80 443

# ابدأ nginx و PHP-FPM
CMD ["/usr/sbin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

