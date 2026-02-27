# Script PowerShell para Atualizar Plugin WordPress
# Uso: .\atualizar-plugin.ps1 -PluginName "meu-plugin" -Version "1.2.4" -Server "seusite.com"

param(
    [Parameter(Mandatory=$true)]
    [string]$PluginName,
    
    [Parameter(Mandatory=$true)]
    [string]$Version,
    
    [Parameter(Mandatory=$true)]
    [string]$Server,
    
    [string]$Username,
    [string]$Password,
    [string]$RemotePath = "/wp-content/plugins",
    [string]$LocalPath = ".\wordpress\wp-content\plugins",
    [switch]$CreateBackup = $true,
    [switch]$UseGit = $false
)

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Atualizador de Plugin WordPress" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verifica se plugin existe localmente
$localPluginPath = Join-Path $LocalPath $PluginName
if (-not (Test-Path $localPluginPath)) {
    Write-Host "ERRO: Plugin não encontrado em $localPluginPath" -ForegroundColor Red
    exit 1
}

Write-Host "Plugin: $PluginName" -ForegroundColor Green
Write-Host "Versão: $Version" -ForegroundColor Green
Write-Host "Servidor: $Server" -ForegroundColor Green
Write-Host ""

# Cria backup local
if ($CreateBackup) {
    Write-Host "Criando backup local..." -ForegroundColor Yellow
    $backupPath = Join-Path $LocalPath "$PluginName-backup-$(Get-Date -Format 'yyyyMMdd-HHmmss')"
    Copy-Item -Path $localPluginPath -Destination $backupPath -Recurse -Force
    Write-Host "Backup criado em: $backupPath" -ForegroundColor Green
    Write-Host ""
}

if ($UseGit) {
    # Atualização via Git
    Write-Host "Atualizando via Git..." -ForegroundColor Yellow
    
    $gitCommands = @"
# Conectar ao servidor e atualizar plugin
ssh $Username@$Server << 'EOF'
cd $RemotePath/$PluginName
git fetch origin
git checkout tags/v$Version 2>/dev/null || git pull origin main
wp plugin deactivate $PluginName
wp plugin activate $PluginName
EOF
"@
    
    Write-Host "Execute os seguintes comandos no servidor:" -ForegroundColor Cyan
    Write-Host $gitCommands -ForegroundColor White
    Write-Host ""
    
} else {
    # Atualização via FTP/SFTP
    Write-Host "Preparando atualização via FTP/SFTP..." -ForegroundColor Yellow
    
    # Cria ZIP do plugin
    $zipName = "$PluginName-$Version.zip"
    $zipPath = Join-Path $env:TEMP $zipName
    
    Write-Host "Criando arquivo ZIP..." -ForegroundColor Yellow
    Compress-Archive -Path "$localPluginPath\*" -DestinationPath $zipPath -Force
    Write-Host "ZIP criado: $zipPath" -ForegroundColor Green
    Write-Host ""
    
    # Instruções para upload manual
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "INSTRUÇÕES PARA ATUALIZAÇÃO" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "1. Conecte-se ao servidor via FTP/SFTP:" -ForegroundColor Yellow
    Write-Host "   Servidor: $Server" -ForegroundColor White
    Write-Host "   Usuário: $Username" -ForegroundColor White
    Write-Host "   Caminho: $RemotePath/$PluginName" -ForegroundColor White
    Write-Host ""
    Write-Host "2. Faça backup do plugin atual no servidor:" -ForegroundColor Yellow
    Write-Host "   Renomeie a pasta para: $PluginName-backup" -ForegroundColor White
    Write-Host ""
    Write-Host "3. Faça upload do arquivo ZIP:" -ForegroundColor Yellow
    Write-Host "   Arquivo: $zipPath" -ForegroundColor White
    Write-Host "   Extraia na pasta: $RemotePath/$PluginName" -ForegroundColor White
    Write-Host ""
    Write-Host "4. Verifique permissões:" -ForegroundColor Yellow
    Write-Host "   Arquivos: 644" -ForegroundColor White
    Write-Host "   Pastas: 755" -ForegroundColor White
    Write-Host ""
    Write-Host "5. Acesse o WordPress Admin e reative o plugin se necessário" -ForegroundColor Yellow
    Write-Host ""
    
    # Opção: Usar WinSCP ou outro cliente FTP via script
    Write-Host "OU use WP-CLI no servidor:" -ForegroundColor Cyan
    Write-Host "ssh $Username@$Server" -ForegroundColor White
    Write-Host "cd $RemotePath" -ForegroundColor White
    Write-Host "wp plugin install $zipPath --force --activate" -ForegroundColor White
    Write-Host ""
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Atualização concluída!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
