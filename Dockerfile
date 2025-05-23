FROM php:8.3-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    libzip-dev libpq-dev libcurl4-openssl-dev \
    libssl-dev vim supervisor gnupg2 ca-certificates lsb-release && \
    docker-php-ext-configure zip && \
    docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath sockets && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install redis && docker-php-ext-enable redis

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Установка Node.js (если нужен Laravel Mix или WebSockets)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get update && apt-get install -y nodejs

# Laravel Echo Server (если нужен)
RUN npm install -g laravel-echo-server

# Рабочая директория
WORKDIR /var/www

# Копирование проекта
COPY . .

# Права доступа
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
