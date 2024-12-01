@echo off
:: Ativar expansão atrasada
setlocal EnableDelayedExpansion

:: Lista de portas para desativar
set ports=8000 8001 8002 8003 8004 8005 8006

:: Loop pelas portas
for %%p in (%ports%) do (
    echo Procurando processo na porta %%p...

    :: Encontra o PID do processo associado à porta
    for /f "tokens=5" %%a in ('netstat -ano ^| findstr :%%p') do (
        set pid=%%a
        echo Processo encontrado na porta %%p com PID: !pid!

        :: Finaliza o processo encontrado
        taskkill /pid !pid! /f > nul 2>&1
        if !errorlevel! == 0 (
            echo Processo com PID !pid! na porta %%p foi finalizado com sucesso.
        ) else (
            echo Falha ao finalizar o processo na porta %%p.
        )
    )
)

echo Todos os processos associados às portas especificadas foram desativados.
pause
