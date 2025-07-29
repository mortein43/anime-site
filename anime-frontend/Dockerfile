# Dockerfile
FROM node:18

WORKDIR /app

COPY package*.json ./

# додай це перед встановленням
ENV NODE_ENV=development

# встановлюємо всі залежності
RUN npm install

COPY . .

EXPOSE 3000

CMD ["npm", "run", "dev"]
