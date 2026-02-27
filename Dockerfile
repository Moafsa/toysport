FROM wordpress:6.4-php8.2-apache

# Instalar dependências
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install zip mysqli pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache
RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod ssl

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Copiar configuração PHP personalizada
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html
