# Usamos PHP 8.0 que es compatible con Laravel 8
FROM php:8.0-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar PHP
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"


# Crear usuario para evitar problemas de permisos
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Crear directorio para logs
RUN mkdir -p /var/log/php && chown www:www /var/log/php

# Establecer directorio de trabajo
WORKDIR /var/www

# Cambiar al usuario no root
USER www

# Exponer el puerto de PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]