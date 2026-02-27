# 🔧 Solução para Erro: WooCommerce Blocks Development Mode

## 📋 Problema Identificado

Após migrar o WordPress para produção, aparece o erro:

> "O modo de desenvolvimento dos blocos woocommerce exige que os arquivos sejam construídos. No diretório raiz, execute nvm use para garantir que a versão do seu nó esteja alinhada, execute pnpm install para instalar dependências, pnpm --filter="@woocommerce/plugin-woocommerce" build para criar os arquivos ou pnpm --filter="@woocommerce/plugin-woocommerce" watch:build para criar os arquivos e assistir a alterações."

### Causa

Este erro ocorre quando o WooCommerce Blocks está em **modo de desenvolvimento**, que requer:
- Node.js instalado
- pnpm instalado
- Compilação dos arquivos JavaScript/CSS

Em produção, **NÃO** devemos usar o modo de desenvolvimento!

## ✅ Solução Aplicada

### 1. Adicionado ao `wp-config.php`

Adicionamos as seguintes constantes para desabilitar o modo de desenvolvimento:

```php
// Desabilitar modo de desenvolvimento dos blocos WooCommerce (corrige erro após migração)
define('WC_BLOCKS_IS_FEATURE_PLUGIN', false);
define('WC_BLOCKS_PHASE', '');
```

### 2. Onde Adicionar no Servidor Online

**IMPORTANTE:** Você precisa adicionar essas linhas ao arquivo `wp-config.php` no servidor online (`toysport.conext.click`).

1. **Via FTP/cPanel File Manager:**
   - Acesse a raiz do WordPress
   - Edite o arquivo `wp-config.php`
   - Adicione as linhas acima ANTES da linha `/* That's all, stop editing! Happy publishing. */`

2. **Ou via SSH:**
   ```bash
   nano /caminho/para/wordpress/wp-config.php
   # Adicione as linhas e salve (Ctrl+O, Enter, Ctrl+X)
   ```

## 🔄 Outras Soluções (Se a Primeira Não Funcionar)

### Opção 1: Limpar Cache do WordPress

1. Desative plugins de cache (WP Rocket, W3 Total Cache, etc.)
2. Limpe o cache do navegador
3. Acesse o painel WordPress novamente

### Opção 2: Reinstalar WooCommerce

Se o problema persistir, tente:

1. **Backup primeiro!** (banco de dados e arquivos)
2. Vá em **Plugins > Plugins Instalados**
3. **Desative** o WooCommerce
4. **Delete** o WooCommerce (NÃO se preocupe, seus dados estão no banco)
5. **Instale** o WooCommerce novamente via **Plugins > Adicionar Novo**
6. **Ative** o plugin
7. Verifique se o erro desapareceu

### Opção 3: Verificar Arquivos de Build

Os arquivos de build do WooCommerce devem estar em:
```
wp-content/plugins/woocommerce/packages/woocommerce-blocks/build/
```

Se esta pasta estiver vazia ou faltando arquivos, o plugin pode estar corrompido. Reinstale o WooCommerce.

### Opção 4: Desabilitar Blocos WooCommerce (Temporariamente)

Se você não usa os blocos Gutenberg do WooCommerce:

1. Vá em **Plugins > Plugins Instalados**
2. Desative o plugin **"WooCommerce Blocks"** (se estiver instalado separadamente)
3. Ou instale o plugin **"Classic Widgets"** para usar widgets clássicos

## 🚀 Verificação

Após aplicar a solução:

1. ✅ Limpe o cache do navegador (Ctrl+Shift+Delete)
2. ✅ Acesse o painel WordPress: `https://toysport.conext.click/wp-admin`
3. ✅ Verifique se o erro desapareceu
4. ✅ Teste se o WooCommerce está funcionando normalmente

## 📝 Notas Importantes

- **Modo de desenvolvimento** é apenas para desenvolvimento local
- **Produção** sempre deve usar os arquivos compilados/built
- A solução proposta desabilita o modo de desenvolvimento, forçando o uso dos arquivos de produção

## 🔍 Se o Problema Persistir

1. Verifique os logs de erro do WordPress:
   - `wp-content/debug.log` (se WP_DEBUG estiver ativo)

2. Verifique os logs do servidor:
   - Via cPanel > Error Logs
   - Ou logs do Apache/Nginx

3. Entre em contato com o suporte do hosting se necessário

---
**Última atualização:** Dezembro 2024
