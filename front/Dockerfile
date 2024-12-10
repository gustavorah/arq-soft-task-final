# Usar a imagem base PHP
FROM php:8.3-cli

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões do PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar o Vite (Node.js e NPM)
RUN npm install -g vite

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar os arquivos do aplicativo
COPY . /var/www

# Instalar dependências do PHP
RUN composer install --no-interaction --no-plugins --no-scripts

# Instalar dependências do Node.js
RUN npm install

# Construir os assets com Vite
RUN npm run build

# Gerar chave do aplicativo e otimizar
RUN php artisan key:generate
RUN composer dump-autoload

# Expor a porta 8000
EXPOSE 8000

# Iniciar o servidor de desenvolvimento do Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
