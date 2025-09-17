# Usa la imagen oficial de PHP con FPM, específica para PHP 8.4
FROM php:8.4-fpm

# Instala dependencias del sistema y herramientas de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libxslt1-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    g++ \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

    # Instala Node.js y npm
RUN curl -sL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && rm -rf /var/lib/apt/lists/*

# Instala las extensiones de PHP necesarias para Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pdo_mysql \
        pgsql \
        zip \
        mbstring \
        bcmath \
        ctype \
        xml \
        gd \
        intl \
        pcntl \
        opcache

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Cambia permisos
RUN chown -R www-data:www-data /var/www/html

# Copia configuración de PHP-FPM
COPY ./zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Expone el puerto del servidor de PHP-FPM
EXPOSE 9000

# Comando para ejecutar PHP-FPM en primer plano
CMD ["php-fpm", "-F"]
