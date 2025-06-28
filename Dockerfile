FROM php:8.3-fpm

# Installation des dépendances système
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
    libpq-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installation de Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Configuration de l'utilisateur
RUN groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Création du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers de configuration
COPY --chown=www:www . /var/www/html/

# Configuration des permissions
RUN chown -R www:www /var/www/html \
    && chmod -R 755 /var/www/html/var || true

# Changement d'utilisateur
USER www

# Exposition du port
EXPOSE 9000

# Commande par défaut
CMD ["php-fpm"] 