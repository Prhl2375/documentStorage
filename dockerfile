FROM phpswoole/swoole:6.1.3-php8.3

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    procps \
    iproute2 \
    nodejs \
    npm \
    lsof \
    supervisor \
    && docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Копіюємо тільки composer файли — шар кешується поки не змінився composer.json
COPY composer.json composer.lock ./

RUN composer install \
    --no-interaction \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

# Копіюємо весь код
COPY . .

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

EXPOSE 8000

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
