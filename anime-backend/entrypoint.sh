#!/bin/sh

echo "⚠ Чекаємо доступності бази даних..."

until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" > /dev/null 2>&1; do
  echo "База недоступна, чекаємо..."
  sleep 2
done

echo "✔ База доступна!"

# Запускаємо міграції
echo "🚀 Запускаємо php artisan migrate"
php artisan migrate || true

# Перевіряємо, чи потрібно запускати сідери та чи база даних порожня
if [ "$RUN_SEEDER" = "true" ]; then
  echo "Перевіряємо, чи база даних порожня для сідерів..."
  # Перевіряємо, чи існує таблиця users і чи вона порожня
  # Використовуємо psql для перевірки наявності записів у таблиці users
  # Якщо записів немає, то таблиця порожня, і можна запускати сідери
  if ! psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" -d "$DB_DATABASE" -tAc "SELECT 1 FROM users LIMIT 1" | grep -q 1; then
    echo "🚀 Запускаємо php artisan db:seed"
    php artisan db:seed || true
  else
    echo "База даних не порожня, сідери не запускаються."
  fi
else
  echo "Запуск сідерів пропущено (RUN_SEEDER не встановлено в 'true')."
fi

# Запускаємо php-fpm
echo "🚀 Запускаємо php-fpm"
exec php-fpm
