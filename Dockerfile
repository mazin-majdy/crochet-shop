FROM webdevops/php-nginx:8.2

WORKDIR /app

# نسخ الملفات مع ضبط المالك
COPY --chown=application:application . /app

# 1. تثبيت nodejs و npm (باستخدام apt لأن الصورة Debian)
# 2. تجهيز المشروع
# كل هذا بيصير وقت البناء (مسموح)
RUN apt-get update && \
    apt-get install -y nodejs npm && \
    rm -rf /var/lib/apt/lists/* && \
    cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install --legacy-peer-deps && \
    npm run build

EXPOSE 8080

# ⚠️ ما نكتب CMD: الصورة مهيأة مسبقاً بتشغل الخدمات تلقائياً
