# Use a imagem base oficial do PostgreSQL 16
FROM postgres:16

# Atualize os pacotes e instale o pglogical e certificados
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    ca-certificates \
    postgresql-16-pglogical && \
    update-ca-certificates && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copiar qualquer script de inicialização personalizado (opcional)
# Se você tiver scripts SQL ou shell para rodar ao iniciar o banco
# descomente a linha abaixo e adicione os arquivos ao diretório indicado
# COPY ./scripts/ /docker-entrypoint-initdb.d/
