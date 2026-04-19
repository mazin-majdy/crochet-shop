FROM webdevops/php-nginx:8.2

COPY --chown=application:application . /app
WORKDIR /app

# تثبيت التبعيات خطوة بخطوة عشان ما تفشل الذاكرة
RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link

RUN npm install && npm run build

EXPOSE 8080
