FROM webdevops/php-nginx:8.2

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader && \
    php artisan key:generate && \
    php artisan migrate --force && \
    php artisan storage:link && \
    npm ci --production && \
    npm run build && \
    chown -R application:application /app/storage /app/bootstrap/cache

EXPOSE 8080
