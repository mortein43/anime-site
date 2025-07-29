#!/bin/sh

echo "⚠ Чекаємо доступності бази даних..."

until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" > /dev/null 2>&1; do
  echo "База недоступна, чекаємо..."
  sleep 2
done

echo "✔ База доступна!"

# Запускаємо міграції (без fresh, щоб не видаляти дані)
echo "🚀 Запускаємо php artisan migrate --seed"
php artisan migrate --seed || true

# Запускаємо php-fpm
echo "🚀 Запускаємо php-fpm"
exec php-fpm
