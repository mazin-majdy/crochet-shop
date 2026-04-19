# نستخدم صورة Alpine خفيفة وقياسية
FROM php:8.2-fpm-alpine

# نثبت كل المتطلبات كـ root وقت البناء (مسموح)
RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    git \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-libs \
    zlib-dev \
    libzip-dev \
    postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl

# نثبت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# نجهز المشروع
RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    cp .env.example .env && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install && \
    npm run build && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# نضيف إعدادات Nginx
COPY nginx.conf /etc/nginx/nginx.conf

EXPOSE 8080

# نشتغل كـ www-data (المستخدم الآمن)
USER www-data

# أمر التشغيل: migrations ثم تشغيل الخدمات
CMD php artisan migrate --force && php-fpm -D && nginx -g 'daemon off;'
