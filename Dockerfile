FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && npm ci \
    && npm run build \
    && php artisan storage:link

EXPOSE $PORT

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
