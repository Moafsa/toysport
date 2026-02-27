# Como Verificar se o Plugin Aparece

## Passo 1: Limpar Cache do WordPress

1. Vá em **Configurações > Permalinks**
2. Clique em **Salvar alterações** (mesmo sem mudar nada)
3. Isso limpa o cache de plugins do WordPress

## Passo 2: Verificar Arquivo de Debug

Acesse no navegador:
```
http://localhost:8089/wp-content/plugins/ts-ml-integration/debug-plugin.php
```

Isso mostrará:
- Se o arquivo existe
- Se o WordPress detecta o plugin
- Se há erros PHP

## Passo 3: Verificar Logs de Erro

Verifique se há erros em:
- `wp-content/debug.log` (se WP_DEBUG estiver ativo)
- Logs do servidor web
- Console do navegador (F12)

## Passo 4: Testar com Arquivo Mínimo

Se ainda não aparecer, teste com um arquivo mínimo:

1. Renomeie `ts-ml-integration.php` para `ts-ml-integration.php.bak`
2. Crie um novo arquivo `ts-ml-integration.php` com apenas:

```php
<?php
/**
 * Plugin Name: ToySport Mercado Livre Integration
 * Description: Teste
 * Version: 1.0.0
 */
```

3. Recarregue a página de plugins
4. Se este aparecer, o problema está no código do plugin principal

## Possíveis Problemas

1. **Versão do PHP**: O plugin requer PHP 8.2+. Se for menor, não aparecerá
2. **Erro Fatal**: Um erro fatal impede o WordPress de ler o cabeçalho
3. **Permissões**: Arquivos sem permissão de leitura não são detectados
4. **Cache**: Cache do WordPress pode estar desatualizado

## Solução Rápida

Execute no terminal (se tiver acesso ao servidor):

```powershell
# Verificar se arquivo existe
Test-Path "c:\Users\User\Documents\CONEXT\ToySport\wp-content\plugins\ts-ml-integration\ts-ml-integration.php"

# Verificar permissões
Get-Acl "c:\Users\User\Documents\CONEXT\ToySport\wp-content\plugins\ts-ml-integration\ts-ml-integration.php"
```
