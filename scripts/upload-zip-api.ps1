# Script PowerShell para fazer upload de ZIP para servidor da API
# Uso: .\upload-zip-api.ps1 -Version "1.1.0" -Username "usuario" -Server "api.seusite.com.br"

param(
    [Parameter(Mandatory=$true)]
    [string]$Version,
    
    [Parameter(Mandatory=$true)]
    [string]$Username,
    
    [Parameter(Mandatory=$true)]
    [string]$Server,
    
    [string]$LocalPath = "releases\ts-ml-integration-$Version.zip",
    [string]$RemotePath = "/var/www/html/downloads"
)

$PluginName = "ts-ml-integration"
$ZipName = "$PluginName-$Version.zip"
$RemoteFile = "$RemotePath/$ZipName"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Upload ZIP para Servidor da API" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Versão: $Version" -ForegroundColor Green
Write-Host "Arquivo: $LocalPath" -ForegroundColor Green
Write-Host "Servidor: $Username@$Server" -ForegroundColor Green
Write-Host "Destino: $RemoteFile" -ForegroundColor Green
Write-Host ""

# Verificar se arquivo existe
if (-not (Test-Path $LocalPath)) {
    Write-Host "ERRO: Arquivo não encontrado: $LocalPath" -ForegroundColor Red
    Write-Host ""
    Write-Host "Criar ZIP primeiro:" -ForegroundColor Yellow
    Write-Host "  .\criar-zip-plugin.sh $PluginName $Version" -ForegroundColor White
    exit 1
}

# Upload via SCP
Write-Host "Fazendo upload..." -ForegroundColor Yellow

# Usar SCP via PowerShell (requer OpenSSH instalado)
$scpCommand = "scp `"$LocalPath`" ${Username}@${Server}:${RemoteFile}"
Invoke-Expression $scpCommand

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "✅ Upload concluído!" -ForegroundColor Green
    Write-Host ""
    
    # Verificar no servidor
    Write-Host "Verificando no servidor..." -ForegroundColor Yellow
    $verifyCommand = "ssh ${Username}@${Server} 'if [ -f `"$RemoteFile`" ]; then ls -lh `"$RemoteFile`"; chmod 644 `"$RemoteFile`"; echo `"✅ Arquivo OK`"; else echo `"❌ Arquivo não encontrado`"; fi'"
    Invoke-Expression $verifyCommand
    
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "✅ Upload concluído com sucesso!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "URL de download:" -ForegroundColor Yellow
    Write-Host "https://$Server/downloads/$ZipName" -ForegroundColor White
    Write-Host ""
    Write-Host "Próximos passos:" -ForegroundColor Yellow
    Write-Host "1. Atualize versão na API: api/update-api.php" -ForegroundColor White
    Write-Host "2. Commit e push: git push" -ForegroundColor White
    Write-Host "3. Teste API" -ForegroundColor White
} else {
    Write-Host ""
    Write-Host "❌ ERRO: Falha no upload" -ForegroundColor Red
    exit 1
}
