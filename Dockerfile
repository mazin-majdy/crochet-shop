FROM webdevops/php-nginx:8.2

# 1. تثبيت Node.js و npm (لأن الصورة الأساسية لا تحتويهم)
USER root
RUN apt-get update && apt-get install -y nodejs npm && rm -rf /var/lib/apt/lists/*
USER application

# نسخ الملفات مع ضبط الصلاحيات
COPY --chown=application:application . /app
WORKDIR /app

# إنشاء ملف .env من المثال عشان يشتغل artisan
RUN cp .env.example .env

# 2. تثبيت مكتبات PHP وتجهيز الأساسيات
RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link

# 3. بناء ملفات الواجهة (CSS/JS)
RUN npm install && npm run build

EXPOSE 8080
