# Solução: Plugin não aparece na lista de plugins

## Problema Identificado

O plugin "ToySport Mercado Livre Integration" não está aparecendo na página de plugins do WordPress.

## Causas Possíveis

1. **Erro Fatal de PHP**: Um erro fatal antes do WordPress ler o cabeçalho do plugin impede sua detecção
2. **Estrutura de Diretórios**: Arquivo principal não está no local correto
3. **Permissões de Arquivo**: Servidor não consegue ler os arquivos
4. **Erro de Sintaxe**: Erro de sintaxe PHP impede o WordPress de processar o plugin

## Correções Aplicadas

### 1. Inicialização Atrasada
- Movida a inicialização do plugin para o hook `plugins_loaded` com prioridade 20
- Isso garante que o WordPress leia o cabeçalho do plugin antes de executar código

### 2. Verificação Condicional do WooCommerce
- Verificação do WooCommerce movida para depois do hook `plugins_loaded`
- Evita erros quando o WooCommerce ainda não foi carregado

### 3. Carregamento Seguro de Dependências
- Adicionada verificação de existência de arquivos antes de carregar
- Evita erros fatais se algum arquivo estiver faltando

### 4. Tratamento de Erros
- Código ajustado para não bloquear a detecção do plugin mesmo com erros

## Como Verificar se Funcionou

1. **Recarregue a página de plugins** no WordPress
2. **Procure por "ToySport Mercado Livre Integration"** na lista
3. **Verifique os logs de erro** do PHP se ainda não aparecer

## Arquivo de Debug

Um arquivo de debug foi criado em:
`wp-content/plugins/ts-ml-integration/debug-plugin.php`

Acesse via navegador para verificar:
- Se o arquivo existe
- Se o WordPress detecta o plugin
- Se há erros PHP

## Próximos Passos se Ainda Não Aparecer

1. Verifique os logs de erro do PHP
2. Verifique as permissões dos arquivos
3. Verifique se há conflitos com outros plugins
4. Tente desativar outros plugins temporariamente
5. Verifique a versão do PHP (deve ser 8.2+)

## Estrutura Correta

O plugin deve estar em:
```
wp-content/plugins/ts-ml-integration/
├── ts-ml-integration.php (arquivo principal)
├── includes/
├── admin/
├── public/
└── ...
```

## Verificação Rápida

Execute no terminal (se tiver acesso):
```bash
# Verificar se o arquivo existe
ls wp-content/plugins/ts-ml-integration/ts-ml-integration.php

# Verificar permissões
chmod 644 wp-content/plugins/ts-ml-integration/ts-ml-integration.php
chmod 755 wp-content/plugins/ts-ml-integration/
```
