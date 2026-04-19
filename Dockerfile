FROM webdevops/php-nginx:8.2

# تثبيت Node.js فقط (لأن الصورة ما فيها)
# نستخدم apk لأن الصورة مبنية على Alpine
USER root
RUN apk add --no-cache nodejs npm
USER application

WORKDIR /app
COPY --chown=application:application . /app

# تجهيز المشروع
RUN cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install && \
    npm run build

EXPOSE 8080
