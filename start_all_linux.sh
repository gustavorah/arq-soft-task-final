#!/bin/bash

# Lista de projetos
projects=("users" "api-gateway" "eventos" "front" "inscricao_evento" "presencas" "certificado")

# Porta inicial
base_port=8000

# Contador para as portas
i=0

# Arquivo para armazenar os PIDs
pid_file="../pids.txt"

# Criar ou limpar o arquivo de PIDs
> "$pid_file"

# Loop pelos projetos
for p in "${projects[@]}"; do
    port=$((base_port + i))
    echo "Iniciando $p na porta $port..."

    # Verifica se o projeto atual é "presencas"
    if [[ "$p" == "presencas" ]]; then
        # Para o projeto "presencas", usa bun dev em segundo plano
        (cd "$p" && bun dev > "../$p.log" 2>&1 & echo "$(date +'%T') - Processo $p PID: $!" >> "$pid_file")
    else
        # Para os demais projetos, usa php artisan serve em segundo plano
        (cd "$p" && php artisan serve --host=127.0.0.1 --port=$port > "../$p.log" 2>&1 & echo "$(date +'%T') - Processo $p PID: $!" >> "$pid_file")
    fi

    # Incrementa o contador
    ((i++))
done

echo "Todos os servidores foram iniciados. Veja os PIDs em $pid_file"
