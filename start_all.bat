@echo off
:: Ativar expansão atrasada
setlocal EnableDelayedExpansion

:: Lista de projetos
set projects=users api-gateway eventos front inscricao_evento presencas certificado

:: Porta inicial
set base_port=8000

:: Contador para as portas
set /a i=0

:: Loop pelos projetos
for %%p in (%projects%) do (
    set /a port=!base_port! + !i!
    echo Iniciando %%p na porta !port!...

    :: Verifica se o projeto atual é "presencas"
    if "%%p"=="presencas" (
        :: Para o projeto "presencas", usa bun dev em segundo plano
        start /B "%%p" cmd /c "cd %%p && bun dev > ..\%%p.log 2>&1 & echo !time! - Processo %%p PID: !$! >> ..\pids.txt"
    ) else (
        :: Para os demais projetos, usa php artisan serve em segundo plano
        start /B "%%p" cmd /c "cd %%p && php artisan serve --host=127.0.0.1 --port=!port! > ..\%%p.log 2>&1 & echo !time! - Processo %%p PID: !$! >> ..\pids.txt"
    )

    :: Incrementa o contador
    set /a i+=1
)

echo Todos os servidores foram iniciados. Veja os PIDs em pids.txt
pause
