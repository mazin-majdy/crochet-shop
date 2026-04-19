FROM webdevops/php-nginx:8.2

# تثبيت Node.js عشان نعمل build للـ CSS/JS
USER root
RUN apt-get update && apt-get install -y nodejs npm && rm -rf /var/lib/apt/lists/*
USER application

WORKDIR /app
COPY --chown=application:application . .

# تثبيت مكتبات PHP + توليد مفتاح + ربط storage
# ⚠️ شلت migrate من هنا عشان الداتابيز ما جاهزة وقت البناء
RUN composer install --no-dev --optimize-autoloader && \
    php artisan key:generate && \
    php artisan storage:link

# تثبيت حزم الواجهة وبناء الملفات
RUN npm install && npm run build

EXPOSE 8080
CMD ["php-fpm"]
