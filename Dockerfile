FROM webdevops/php-nginx:8.2

# تثبيت Node.js و npm عشان نبني الـ Assets
USER root
RUN apt-get update && apt-get install -y nodejs npm && rm -rf /var/lib/apt/lists/*
USER application

WORKDIR /app
COPY --chown=application:application . .

# 1. تثبيت مكتبات PHP وتجهيز الأساسيات
RUN composer install --no-dev --optimize-autoloader && \
    php artisan key:generate && \
    php artisan storage:link

# 2. تثبيت حزم الواجهة وبناء الملفات (CSS/JS)
RUN npm install && npm run build

EXPOSE 8080

# 3. الأمر اللي بيشتغل لما السيرفر يقلع: بيعمل Migrations ثم يشغل الموقع
CMD php artisan migrate --force && php-fpm
