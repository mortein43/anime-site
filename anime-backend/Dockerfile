FROM php:8.2-fpm

# Встановлення залежностей
RUN apt-get update && apt-get install -y procps \
    git unzip libpq-dev libzip-dev libicu-dev zip \
    netcat-openbsd postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql intl zip \
    # Встановлення Node.js та npm
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Встановлення Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Копіюємо кастомні налаштування PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Робоча директорія
WORKDIR /var/www/html

# Копіюємо файли для встановлення Node.js залежностей
COPY package.json package-lock.json ./

# Встановлюємо Node.js залежності
RUN npm install

# Копіюємо весь код проєкту
COPY . .

# Встановлюємо залежності Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Даємо права на кеш і логи
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Копіюємо кастомний entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Встановлюємо entrypoint
ENTRYPOINT ["/entrypoint.sh"]
