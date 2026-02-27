# 🔧 Solução para Erro de Migração WordPress - ERR_CONNECTION_RESET

## 📋 Problema Identificado

O erro `net::ERR_CONNECTION_RESET` durante a importação do All-in-One WP Migration ocorre devido a:
- Limites de tempo de execução do PHP
- Limites de tamanho de upload
- Timeout do servidor web
- Limites de memória do PHP

## ✅ Soluções Aplicadas

### 1. Arquivo .htaccess Atualizado

O arquivo `wordpress/.htaccess` foi atualizado com as seguintes configurações:

```apache
# Aumenta limites de upload para 256MB
upload_max_filesize = 256M
post_max_size = 256M

# Aumenta tempo de execução para 5 minutos
max_execution_time = 300
max_input_time = 300

# Aumenta memória para 512MB
memory_limit = 512M
```

## 🔄 Próximos Passos

### No Servidor Online (toysport.conext.click)

#### Opção 1: Usar .htaccess (já aplicado)
O arquivo `.htaccess` atualizado deve ser enviado para o servidor online na raiz do WordPress.

#### Opção 2: Configurar php.ini (se tiver acesso)
Se você tiver acesso ao `php.ini` do servidor, edite:

```ini
max_execution_time = 300
memory_limit = 512M
upload_max_filesize = 256M
post_max_size = 256M
```

#### Opção 3: Configurar via cPanel/Control Panel
1. Acesse o **cPanel** do seu hosting
2. Vá em **Select PHP Version** ou **MultiPHP INI Editor**
3. Configure:
   - `upload_max_filesize`: 256M
   - `post_max_size`: 256M
   - `max_execution_time`: 300
   - `memory_limit`: 512M

### Configurações Adicionais para Nginx (se aplicável)

Se seu servidor usa Nginx, adicione ao arquivo de configuração do site:

```nginx
client_max_body_size 256M;
client_body_timeout 300s;
fastcgi_read_timeout 300;
```

## 🚀 Tentar Migração Novamente

1. **Faça upload do `.htaccess` atualizado** para o servidor online
2. **Limpe o cache** do WordPress (se usar plugin de cache)
3. **Desative plugins de cache** temporariamente durante a migração
4. **Tente a migração novamente**

## 📦 Alternativas se Ainda Falhar

### 1. Usar Extensão File Extension
- Instale a extensão **"File Extension"** do All-in-One WP Migration
- Ela permite dividir arquivos grandes em partes menores

### 2. Usar Versão Premium
- A versão Premium permite upload via FTP/SFTP, evitando problemas de timeout

### 3. Migração Manual via FTP
- Exporte via phpMyAdmin (banco de dados)
- Faça upload dos arquivos via FTP
- Importe o banco de dados no servidor online

### 4. Usar WP-CLI (via SSH)
```bash
# Exportar do servidor local
wp db export backup.sql

# Importar no servidor online
wp db import backup.sql
wp search-replace 'http://localhost:8089' 'https://toysport.conext.click'
```

## ⚠️ Verificações Importantes

1. **Tamanho do arquivo de migração**: Se for maior que 256MB, considere usar a extensão File Extension
2. **Espaço no servidor**: Certifique-se de ter espaço suficiente no servidor online
3. **Cloudflare/Firewall**: Se usar Cloudflare, desative temporariamente durante a migração
4. **Plugins conflitantes**: Desative plugins de segurança/firewall temporariamente

## 🔍 Debugging

Se o erro persistir, verifique os logs:

### Logs do PHP
```bash
# Via cPanel > Error Logs
# Ou via FTP: logs/error_log
```

### Logs do WordPress
Ative o debug no `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## 📞 Suporte

Se o problema persistir após seguir todos os passos:
1. Verifique os logs de erro do servidor
2. Entre em contato com o suporte do hosting
3. Considere usar migração via FTP/SFTP (versão Premium)

---
**Última atualização:** $(Get-Date -Format "dd/MM/yyyy")
