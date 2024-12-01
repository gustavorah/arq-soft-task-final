# Usar uma imagem base (dependendo do seu projeto, pode ser PHP, Node.js, etc.)
FROM php:8.3-cli

# Instalar dependências do sistema e PHP (ajuste conforme necessário)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto para o contêiner
COPY . .

# Instalar dependências (ajuste conforme a tecnologia que está usando)
RUN composer install

# Expor a porta do servidor (se for o caso)
EXPOSE 8000

# Comando para rodar o servidor (ajuste conforme necessário)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
