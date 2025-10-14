# Dockerfile
FROM node:18-alpine

WORKDIR /app

COPY package*.json ./

# додай це перед встановленням
ENV NODE_ENV=development

# встановлюємо всі залежності
RUN npm ci

COPY . .

# Додаємо змінні для правильного hot reload у Docker
ENV CHOKIDAR_USEPOLLING=true
ENV WATCHPACK_POLLING=true
ENV NEXT_WEBPACK_USEPOLLING=true

# Виносимо NODE_ENV у змінні середовища (development або production)
ENV NODE_ENV=development

EXPOSE 3000

CMD ["npm", "run", "dev"]
