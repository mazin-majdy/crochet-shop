FROM webdevops/php-nginx:8.2

# نسخ الملفات مع ضبط الصلاحيات
COPY --chown=application:application . /app
WORKDIR /app

# 1. إنشاء ملف .env من المثال (هنا الحل)
RUN cp .env.example .env

# 2. تثبيت الباكيجات وتجهيز الأساسيات
RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link

# 3. بناء ملفات الواجهة (CSS/JS)
RUN npm install && npm run build

EXPOSE 8080
