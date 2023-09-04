FROM php:8.2-cli

ENV COMPOSER_ALLOW_SUPERUSER=1

#crear imagen con los recursos necesarios

RUN apt-get update -y && apt-get install -y libmcrypt-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    npm

#composer para instalar laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install zip pdo pdo_mysql

WORKDIR /app
COPY . .

RUN composer install

#instalar recursos de vite
RUN npm install

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000