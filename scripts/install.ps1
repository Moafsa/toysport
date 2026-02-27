# Script de Instalação - Toy Sport (Windows PowerShell)
# Este script auxilia na instalação do ambiente WordPress/WooCommerce

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Instalação Toy Sport - WordPress     " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar pré-requisitos
Write-Host "Verificando pré-requisitos..." -ForegroundColor Yellow

# Verificar PHP
$phpVersion = php -v 2>&1 | Select-String -Pattern "PHP (\d+\.\d+)" | ForEach-Object { $_.Matches.Groups[1].Value }
if ($phpVersion) {
    Write-Host "✓ PHP encontrado: versão $phpVersion" -ForegroundColor Green
} else {
    Write-Host "✗ PHP não encontrado. Por favor, instale o PHP 7.4 ou superior." -ForegroundColor Red
    Write-Host "  Download: https://windows.php.net/download/" -ForegroundColor Yellow
    exit 1
}

# Verificar MySQL
$mysqlVersion = mysql --version 2>&1
if ($mysqlVersion) {
    Write-Host "✓ MySQL encontrado" -ForegroundColor Green
} else {
    Write-Host "✗ MySQL não encontrado. Por favor, instale o MySQL 5.7 ou superior." -ForegroundColor Red
    Write-Host "  Download: https://dev.mysql.com/downloads/installer/" -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "Pré-requisitos verificados com sucesso!" -ForegroundColor Green
Write-Host ""

# Perguntar sobre download do WordPress
$downloadWordPress = Read-Host "Deseja baixar o WordPress automaticamente? (S/N)"
if ($downloadWordPress -eq "S" -or $downloadWordPress -eq "s") {
    Write-Host "Baixando WordPress..." -ForegroundColor Yellow
    
    $wordpressUrl = "https://wordpress.org/latest.zip"
    $zipFile = "wordpress-latest.zip"
    $extractPath = "wordpress"
    
    try {
        Invoke-WebRequest -Uri $wordpressUrl -OutFile $zipFile
        Write-Host "✓ WordPress baixado" -ForegroundColor Green
        
        Write-Host "Extraindo arquivos..." -ForegroundColor Yellow
        Expand-Archive -Path $zipFile -DestinationPath $extractPath -Force
        Write-Host "✓ Arquivos extraídos" -ForegroundColor Green
        
        Remove-Item $zipFile
        Write-Host "✓ Arquivo temporário removido" -ForegroundColor Green
    } catch {
        Write-Host "✗ Erro ao baixar WordPress: $_" -ForegroundColor Red
        exit 1
    }
}

# Perguntar sobre configuração do banco de dados
Write-Host ""
$configureDB = Read-Host "Deseja criar o banco de dados agora? (S/N)"
if ($configureDB -eq "S" -or $configureDB -eq "s") {
    $dbName = Read-Host "Nome do banco de dados (padrão: toysport_db)"
    if ([string]::IsNullOrWhiteSpace($dbName)) {
        $dbName = "toysport_db"
    }
    
    $dbUser = Read-Host "Usuário do banco de dados"
    $dbPass = Read-Host "Senha do banco de dados" -AsSecureString
    $dbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass))
    
    Write-Host "Criando banco de dados..." -ForegroundColor Yellow
    
    $createDbQuery = "CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    
    try {
        $result = mysql -u $dbUser -p$dbPassPlain -e $createDbQuery
        Write-Host "✓ Banco de dados criado: $dbName" -ForegroundColor Green
    } catch {
        Write-Host "✗ Erro ao criar banco de dados. Crie manualmente." -ForegroundColor Red
        Write-Host "  SQL: $createDbQuery" -ForegroundColor Yellow
    }
}

# Criar wp-config.php
Write-Host ""
$createConfig = Read-Host "Deseja criar o arquivo wp-config.php? (S/N)"
if ($createConfig -eq "S" -or $createConfig -eq "s") {
    if (-not (Test-Path "wordpress/wp-config.php")) {
        Copy-Item "wordpress/wp-config-sample.php" "wordpress/wp-config.php"
        
        Write-Host "Configurando wp-config.php..." -ForegroundColor Yellow
        
        if ([string]::IsNullOrWhiteSpace($dbName)) {
            $dbName = Read-Host "Nome do banco de dados"
        }
        if ([string]::IsNullOrWhiteSpace($dbUser)) {
            $dbUser = Read-Host "Usuário do banco de dados"
        }
        if ([string]::IsNullOrWhiteSpace($dbPassPlain)) {
            $dbPass = Read-Host "Senha do banco de dados" -AsSecureString
            $dbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass))
        }
        
        $dbHost = Read-Host "Host do banco de dados (padrão: localhost)"
        if ([string]::IsNullOrWhiteSpace($dbHost)) {
            $dbHost = "localhost"
        }
        
        $wpConfig = Get-Content "wordpress/wp-config.php" -Raw
        
        $wpConfig = $wpConfig -replace "database_name_here", $dbName
        $wpConfig = $wpConfig -replace "username_here", $dbUser
        $wpConfig = $wpConfig -replace "password_here", $dbPassPlain
        $wpConfig = $wpConfig -replace "localhost", $dbHost
        
        # Gerar chaves de segurança
        $authKeys = Invoke-WebRequest -Uri "https://api.wordpress.org/secret-key/1.1/salt/" | Select-Object -ExpandProperty Content
        
        Set-Content -Path "wordpress/wp-config.php" -Value $wpConfig
        Write-Host "✓ wp-config.php configurado" -ForegroundColor Green
    } else {
        Write-Host "→ wp-config.php já existe" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Instalação concluída!                 " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Próximos passos:" -ForegroundColor Yellow
Write-Host "1. Configure seu servidor web (Apache/Nginx)" -ForegroundColor White
Write-Host "2. Acesse o site no navegador" -ForegroundColor White
Write-Host "3. Complete a instalação do WordPress" -ForegroundColor White
Write-Host "4. Instale o WooCommerce e plugins" -ForegroundColor White
Write-Host "5. Execute o script setup-plugins.php" -ForegroundColor White
Write-Host ""
