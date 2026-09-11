FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
        libicu-dev \
        git \
        unzip \
    && docker-php-ext-install intl mysqli pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-interaction --prefer-dist

EXPOSE 8080

CMD ["php", "spark", "serve", "--host", "0.0.0.0", "--port", "8080"]
