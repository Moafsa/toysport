# Diagnóstico: Plugin não aparece na lista

## Passos para Diagnosticar

### 1. Verificar Estrutura de Arquivos
Certifique-se de que o arquivo principal está em:
```
wp-content/plugins/ts-ml-integration/ts-ml-integration.php
```

### 2. Verificar Permissões
Os arquivos devem ter permissões de leitura:
- Arquivos PHP: 644
- Diretórios: 755

### 3. Verificar Logs de Erro
Verifique os logs de erro do PHP:
- `wp-content/debug.log` (se WP_DEBUG estiver ativo)
- Logs do servidor web
- Logs do PHP

### 4. Testar com Arquivo Mínimo
Temporariamente, renomeie `ts-ml-integration.php` para `ts-ml-integration.php.bak` e crie um arquivo mínimo:

```php
<?php
/**
 * Plugin Name: ToySport Mercado Livre Integration
 * Description: Teste
 * Version: 1.0.0
 */
```

Se este aparecer, o problema está no código do plugin principal.

### 5. Verificar Versão do PHP
O plugin requer PHP 8.2+. Verifique:
- Painel de controle do WordPress
- Ou use `phpinfo()`

### 6. Verificar Conflitos
- Desative outros plugins temporariamente
- Troque para tema padrão do WordPress
- Verifique se há erros no console do navegador

### 7. Usar Arquivo de Debug
Acesse: `http://seusite.com/wp-content/plugins/ts-ml-integration/debug-plugin.php`

Isso mostrará:
- Se o arquivo existe
- Se o WordPress detecta o plugin
- Se há erros PHP

## Correções Aplicadas

1. ✅ Inicialização movida para hook `plugins_loaded`
2. ✅ Verificação condicional do WooCommerce
3. ✅ Carregamento seguro de dependências
4. ✅ Proteção contra redefinição de constantes

## Próximos Passos

1. Recarregue a página de plugins
2. Se ainda não aparecer, verifique os logs de erro
3. Use o arquivo de debug para identificar o problema
4. Teste com o arquivo mínimo

## Contato para Suporte

Se o problema persistir, forneça:
- Versão do WordPress
- Versão do PHP
- Logs de erro
- Resultado do arquivo de debug
