# Usando a imagem base do Node.js
FROM node:18-alpine

# Instalar curl e bash
RUN apk add --no-cache curl bash

# Instalar o Bun
RUN curl -fsSL https://bun.sh/install | bash

# Adicionar o Bun ao PATH
ENV PATH="/root/.bun/bin:${PATH}"

# Configurar o diretório de trabalho
WORKDIR /var/www

# Copiar os arquivos do projeto
COPY . .

# Instalar as dependências com Bun
RUN bun install

# Expor a porta (ajuste conforme necessário para seu serviço)
EXPOSE 8000

# Comando para rodar o servidor
CMD ["bun", "dev"]
