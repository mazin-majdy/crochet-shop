FROM webdevops/php-nginx:8.2

# تثبيت Node.js و npm باستخدام apt (لأن الصورة مبنية على Debian)
USER root
RUN apt-get update && apt-get install -y nodejs npm && rm -rf /var/lib/apt/lists/*
USER application

WORKDIR /app
COPY --chown=application:application . /app

# تجهيز المشروع
RUN cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan key:generate && \
    php artisan storage:link && \
    npm install --legacy-peer-deps && \
    npm run build

EXPOSE 8080
