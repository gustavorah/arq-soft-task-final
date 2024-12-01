#!/bin/bash

# Lista de portas para desativar
ports=(8000 8001 8002 8003 8004 8005 8006)

# Loop pelas portas
for p in "${ports[@]}"; do
    echo "Procurando processo na porta $p..."

    # Encontra o PID do processo associado à porta
    pid=$(lsof -t -i:$p)

    if [ -n "$pid" ]; then
        echo "Processo encontrado na porta $p com PID: $pid"

        # Finaliza o processo encontrado
        kill -9 $pid > /dev/null 2>&1
        if [ $? -eq 0 ]; then
            echo "Processo com PID $pid na porta $p foi finalizado com sucesso."
        else
            echo "Falha ao finalizar o processo na porta $p."
        fi
    else
        echo "Nenhum processo encontrado na porta $p."
    fi
done

echo "Todos os processos associados às portas especificadas foram desativados."
