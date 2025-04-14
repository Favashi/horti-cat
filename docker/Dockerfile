# Usa la imagen oficial de PHP con FPM
FROM php:8.2-fpm

# Instala dependencias necesarias (por ejemplo, para Composer o extensiones)
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    # Si necesitas MySQL, Postgres o GD, añade aquí las librerías necesarias, ej:
    libpq-dev \
    # libpng-dev ... etc
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer globalmente
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Instalar Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copiamos la configuración de Xdebug (definida en xdebug.ini) hacia el contenedor
COPY docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Establecemos el directorio de trabajo
WORKDIR /var/www/html

# Exponemos el puerto de PHP-FPM (generalmente no se usa externamente,
# porque FPM se comunica con Nginx/Apache internamente)
EXPOSE 9000

# El ENTRYPOINT por defecto es php-fpm, así que no hace falta declararlo explícitamente