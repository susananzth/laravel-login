# --------------------------------------
# Etapa 1: Dependencias de Backend (Composer)
# --------------------------------------
FROM composer:2 AS backend_build

WORKDIR /app

COPY composer.json composer.lock ./

# Instalamos dependencias optimizadas
RUN composer install \
    --no-dev \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# --------------------------------------
# Etapa 2: Construcción del Frontend (Node)
# --------------------------------------
FROM node:22-alpine AS frontend_build

WORKDIR /app

# Copiamos archivos de dependencias de Node
COPY package*.json ./

# Instalamos dependencias (npm ci es más estricto y rápido que npm install para CI)
RUN npm ci

# Copiamos el resto del código y construimos los assets
COPY . .

# Traemos la carpeta vendor de la etapa anterior ANTES de compilar
# Vite necesita acceder a vendor/livewire/flux/dist/flux.css
COPY --from=backend_build /app/vendor ./vendor

# Compilo los assets
RUN npm run build

# --------------------------------------
# Etapa 3: Imagen Final de Producción (PHP)
# --------------------------------------
FROM php:8.4-fpm AS app_prod

# Instala dependencias del sistema y herramientas de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    dialog \
    nano \
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
    curl \
    unzip \
    zip \
    g++ \
    libpq-dev \
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

# 1. Copiamos el código fuente de tu proyecto al contenedor
COPY . .

# Limpieza defensiva: Eliminamos caches que se hayan colado y creamos carpetas necesarias
RUN rm -rf bootstrap/cache/*.php \
    && mkdir -p storage/framework/cache/data \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views

# 2. Traemos la carpeta 'vendor' desde la etapa de Composer
COPY --from=backend_build /app/vendor ./vendor

# 3. Traemos los assets compilados (public/build) desde la etapa de Node
COPY --from=frontend_build /app/public ./public

# Configuración de permisos críticos
# Asignamos la propiedad al usuario www-data (el usuario por defecto de php-fpm)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Copia configuración personalizada de PHP
COPY ./zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Expone el puerto del servidor de PHP-FPM
EXPOSE 9000

# Usamos un script de entrada para tareas de arranque
COPY ./docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
# Comando para ejecutar PHP-FPM en primer plano
CMD ["php-fpm", "-F"]

# --------------------------------------
# ETAPA 4: Imagen Final de WEB (Nginx)
# --------------------------------------
FROM nginx:alpine AS web_prod

# Eliminar config por defecto
RUN rm /etc/nginx/conf.d/default.conf

# Copiar configuración personalizada
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copia SOLO la carpeta public del proyecto local
COPY --from=frontend_build /app/public /var/www/html/public
