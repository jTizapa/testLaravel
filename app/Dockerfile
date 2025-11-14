FROM node:20-alpine

WORKDIR /usr/src/app

# Install deps first (better layer caching)
COPY package*.json ./
RUN npm ci

# Copy source
COPY . .

EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]

