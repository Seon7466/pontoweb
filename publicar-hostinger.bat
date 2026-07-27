@echo off
setlocal EnableExtensions EnableDelayedExpansion
chcp 65001 >nul
title Gerar pacote PontoWeb para Hostinger

cd /d "%~dp0"

set "LOG=%~dp0publicar-hostinger.log"
set "DEPLOY=%~dp0deploy"
set "TEMP_DEPLOY=%TEMP%\pontoweb-deploy-%RANDOM%-%RANDOM%"

> "%LOG%" echo Inicio: %date% %time%
>>"%LOG%" echo Projeto: %CD%

echo.
echo ============================================================
echo       GERAR PACOTE PONTOWEB PARA A HOSTINGER
echo ============================================================
echo.
echo Pasta atual:
echo %CD%
echo.

if not exist "%CD%\artisan" (
    echo [ERRO] O arquivo artisan nao foi encontrado.
    echo.
    echo Coloque este BAT dentro da raiz do projeto:
    echo C:\Projetos\Pontoweb
    echo.
    >>"%LOG%" echo ERRO: artisan nao encontrado.
    pause
    exit /b 1
)

if not exist "%DEPLOY%" (
    mkdir "%DEPLOY%"
    if errorlevel 1 (
        echo [ERRO] Nao foi possivel criar:
        echo %DEPLOY%
        >>"%LOG%" echo ERRO: falha ao criar deploy.
        pause
        exit /b 1
    )
)

echo [1/4] Verificando Git...
where git >nul 2>&1

if errorlevel 1 (
    echo Git nao encontrado. O ZIP sera criado sem enviar ao GitHub.
    >>"%LOG%" echo AVISO: Git nao encontrado.
) else (
    for /f "delims=" %%B in ('git branch --show-current 2^>nul') do set "BRANCH=%%B"

    if defined BRANCH (
        echo Branch atual: !BRANCH!
        echo.
        git status --short

        set /p "MENSAGEM=Mensagem da atualizacao (Enter para usar padrao): "

        if "!MENSAGEM!"=="" set "MENSAGEM=Atualizacao do PontoWeb"

        git add -A >>"%LOG%" 2>&1
        git diff --cached --quiet

        if errorlevel 1 (
            git commit -m "!MENSAGEM!" >>"%LOG%" 2>&1

            if errorlevel 1 (
                echo [AVISO] Nao foi possivel criar o commit.
                echo O ZIP continuara sendo criado.
                >>"%LOG%" echo AVISO: commit falhou.
            ) else (
                echo Commit criado.
            )
        ) else (
            echo Nenhuma alteracao nova para commit.
        )

        echo Enviando para o GitHub...
        git push origin "!BRANCH!" >>"%LOG%" 2>&1

        if errorlevel 1 (
            echo [AVISO] O push falhou, mas o ZIP continuara sendo criado.
            echo Consulte o arquivo publicar-hostinger.log.
            >>"%LOG%" echo AVISO: push falhou.
        ) else (
            echo GitHub atualizado.
        )
    ) else (
        echo [AVISO] Nao foi possivel identificar a branch.
        echo O ZIP continuara sendo criado.
        >>"%LOG%" echo AVISO: branch nao identificada.
    )
)

echo.
echo [2/4] Copiando arquivos para uma pasta temporaria...

if exist "%TEMP_DEPLOY%" rmdir /s /q "%TEMP_DEPLOY%"
mkdir "%TEMP_DEPLOY%"

robocopy "%CD%" "%TEMP_DEPLOY%" /E ^
 /XD ".git" "node_modules" "deploy" ".idea" ".vscode" ^
      "storage\logs" ^
      "storage\framework\cache" ^
      "storage\framework\sessions" ^
      "storage\framework\views" ^
      "storage\app\public" ^
 /XF ".env" "*.log" ".phpunit.result.cache" ".phpunit.cache" ^
 /R:1 /W:1 /NFL /NDL /NJH /NJS /NP >>"%LOG%" 2>&1

set "RC=!ERRORLEVEL!"

if !RC! GEQ 8 (
    echo [ERRO] Falha ao copiar os arquivos. Codigo: !RC!
    echo Veja o log:
    echo %LOG%
    >>"%LOG%" echo ERRO ROBOCOPY: !RC!
    pause
    exit /b 1
)

del /q "%TEMP_DEPLOY%\bootstrap\cache\config.php" 2>nul
del /q "%TEMP_DEPLOY%\bootstrap\cache\packages.php" 2>nul
del /q "%TEMP_DEPLOY%\bootstrap\cache\services.php" 2>nul
del /q "%TEMP_DEPLOY%\bootstrap\cache\events.php" 2>nul

for /f "delims=" %%T in ('powershell -NoProfile -Command "Get-Date -Format yyyyMMdd-HHmmss"') do set "STAMP=%%T"

set "ZIP=%DEPLOY%\pontoweb-hostinger-!STAMP!.zip"

echo.
echo [3/4] Criando o ZIP...
echo Isso pode levar alguns minutos por causa da pasta vendor.

where tar >nul 2>&1

if not errorlevel 1 (
    pushd "%TEMP_DEPLOY%"
    tar -a -c -f "%ZIP%" * >>"%LOG%" 2>&1
    set "ZIP_RC=!ERRORLEVEL!"
    popd
) else (
    powershell -NoProfile -ExecutionPolicy Bypass -Command ^
      "Compress-Archive -LiteralPath '%TEMP_DEPLOY%\*' -DestinationPath '%ZIP%' -CompressionLevel Optimal -Force" >>"%LOG%" 2>&1
    set "ZIP_RC=!ERRORLEVEL!"
)

if not "!ZIP_RC!"=="0" (
    echo [ERRO] Nao foi possivel criar o ZIP.
    echo Veja o arquivo:
    echo %LOG%
    >>"%LOG%" echo ERRO ZIP: !ZIP_RC!
    pause
    exit /b 1
)

if not exist "%ZIP%" (
    echo [ERRO] O comando terminou, mas o ZIP nao foi localizado.
    echo Veja:
    echo %LOG%
    >>"%LOG%" echo ERRO: ZIP inexistente.
    pause
    exit /b 1
)

rmdir /s /q "%TEMP_DEPLOY%" >nul 2>&1

for %%F in ("%ZIP%") do set "TAMANHO=%%~zF"

echo.
echo [4/4] Finalizado.
echo.
echo ============================================================
echo PACOTE CRIADO COM SUCESSO
echo ============================================================
echo.
echo Arquivo:
echo %ZIP%
echo.
echo Tamanho em bytes:
echo !TAMANHO!
echo.
echo A pasta sera aberta agora.
echo.

>>"%LOG%" echo ZIP: %ZIP%
>>"%LOG%" echo Tamanho: !TAMANHO!
>>"%LOG%" echo Final: %date% %time%

explorer "%DEPLOY%"
pause
endlocal
