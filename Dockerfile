FROM php:8.2-fpm

# تثبيت المتطلبات الأساسية
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# إعداد الصلاحيات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# إعداد Nginx
COPY nginx.conf /etc/nginx/sites-available/default

EXPOSE 8080

# أمر التشغيل النهائي
CMD service php8.2-fpm start && \
    nginx -c /etc/nginx/nginx.conf && \
    php artisan migrate --force && \
    tail -f /var/log/nginx/access.log
