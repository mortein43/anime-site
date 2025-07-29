## Локальний запуск

1. Клонувати репозиторій: git clone https://github.com/yourname/anime-site.git
   cd anime-site
2. Запустити: docker compose up -d --build (лише при першому запуску або після змін у Dockerfile/compose)
3. Коли завершив робочу сесію: docker compose down
4. Що продовжити роботу: docker compose up -d
5. Комітити прямо сюди.
6. Для логів використовуєш docker compose logs -f service-name(backend, nginx, frontend, db).

### API: http://localhost:8000/api
### Frontend: http://localhost:3000
