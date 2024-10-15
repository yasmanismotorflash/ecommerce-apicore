FROM php:8.3-fpm

# Instalar extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    unzip \
    uuid-dev \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mbstring exif pcntl bcmath opcache intl

# Instalar Redis o Memcached
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install apcu && docker-php-ext-enable apcu
RUN pecl install uuid && docker-php-ext-enable uuid

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Limpiar cachés
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Healthcheck para monitorear la aplicación
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost || exit 1

WORKDIR /var/www/apicore
