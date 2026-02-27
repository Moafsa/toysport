# Guia de Instalação - Toy Sport

## 📋 Pré-requisitos

Antes de começar, certifique-se de ter:
- PHP 7.4 ou superior
- MySQL 5.7 ou superior / MariaDB 10.3 ou superior
- Servidor web (Apache ou Nginx)
- Composer (opcional)
- Git (opcional)

## 🚀 Instalação Passo a Passo

### 1. Configurar Servidor Local (Desenvolvimento)

#### Opção A: XAMPP (Windows/Mac/Linux)
1. Baixe e instale o XAMPP: https://www.apachefriends.org/
2. Inicie o Apache e MySQL
3. Acesse: http://localhost/phpmyadmin

#### Opção B: Local by Flywheel (Windows/Mac)
1. Baixe e instale: https://localwp.com/
2. Crie um novo site
3. Configure PHP e MySQL

#### Opção C: Docker
```bash
docker-compose up -d
```

### 2. Instalar WordPress

#### Download WordPress
1. Baixe a versão mais recente: https://wordpress.org/download/
2. Extraia os arquivos na pasta do seu servidor
   - XAMPP: `C:\xampp\htdocs\toysport`
   - Local: será criado automaticamente

#### Criar Banco de Dados
1. Acesse phpMyAdmin: http://localhost/phpmyadmin
2. Crie um novo banco de dados: `toysport_db`
3. Crie um usuário e senha para o banco

#### Configurar wp-config.php
1. Copie `wp-config-sample.php` para `wp-config.php`
2. Edite `wp-config.php` e configure:
```php
define('DB_NAME', 'toysport_db');
define('DB_USER', 'seu_usuario');
define('DB_PASSWORD', 'sua_senha');
define('DB_HOST', 'localhost');
```

#### Instalar WordPress
1. Acesse: http://localhost/toysport (ou seu domínio)
2. Siga o assistente de instalação
3. Configure:
   - Título do site: Toy Sport
   - Usuário administrador
   - Senha forte
   - Email

### 3. Instalar WooCommerce

1. No painel WordPress, vá em **Plugins > Adicionar Novo**
2. Busque por "WooCommerce"
3. Clique em **Instalar** e depois **Ativar**
4. Siga o assistente de configuração:
   - Endereço da loja
   - Moeda: Real Brasileiro (R$)
   - Tipos de produtos
   - Métodos de pagamento
   - Métodos de envio

### 4. Instalar Tema

#### Opção A: Tema Customizado
1. Copie o tema para: `wp-content/themes/toysport-theme/`
2. No WordPress, vá em **Aparência > Temas**
3. Ative o tema "Toy Sport"

#### Opção B: Tema Premium
1. Instale um tema WooCommerce compatível
2. Customize conforme o design do site original

### 5. Instalar Plugins Essenciais

#### Plugins Core
1. **WooCommerce** (já instalado)
2. **WooCommerce Mercado Livre Integration**
3. **Wuzapi Integration** (plugin customizado)
4. **AI Chatbot** (plugin customizado)

#### Plugins de Pagamento
5. **WooCommerce Mercado Pago** ou **PagSeguro**
6. **Pix para WooCommerce**
7. **Boleto para WooCommerce**

#### Plugins de Frete
8. **WooCommerce Correios**
9. **WooCommerce Shipping** (opcional)

#### Plugins de SEO e Performance
10. **Yoast SEO** ou **Rank Math**
11. **WP Rocket** ou **W3 Total Cache**
12. **Smush** - Otimização de imagens

#### Plugins de Segurança
13. **Wordfence Security**
14. **UpdraftPlus** - Backup

#### Plugins de Utilidades
15. **Contact Form 7**
16. **WooCommerce Wishlist**

### 6. Configurar Integrações

#### Mercado Livre
1. Acesse: https://developers.mercadolivre.com.br/
2. Crie uma aplicação
3. Obtenha App ID e Client Secret
4. No WordPress, vá em **WooCommerce > Configurações > Integrações > Mercado Livre**
5. Configure as credenciais
6. Teste a conexão

#### Wuzapi
1. Acesse: https://wuzapi.com.br/
2. Crie uma conta e instância
3. Obtenha API Key e Instance ID
4. No WordPress, configure no plugin Wuzapi
5. Configure webhook se necessário

#### Inteligência Artificial
1. Acesse: https://platform.openai.com/ (ou Google Gemini)
2. Crie conta e gere API Key
3. Configure no plugin de IA
4. Teste o chatbot

### 7. Configurar Páginas

1. Vá em **Páginas > Adicionar Nova**
2. Crie as páginas principais:
   - Home
   - Sobre Nós
   - Contato
   - Política de Devolução
   - Rastreamento
   - Política de Privacidade
   - Termos e Condições

3. Configure os menus em **Aparência > Menus**

### 8. Configurar Categorias

1. Vá em **Produtos > Categorias**
2. Crie as categorias principais:
   - Adultos (com subcategorias)
   - Bebês
   - Drones
   - Meninas (com subcategorias)
   - Meninos (com subcategorias)

### 9. Importar Produtos

#### Opção A: Manual
1. Vá em **Produtos > Adicionar Novo**
2. Cadastre cada produto manualmente
3. Configure preços, estoque, imagens
4. Publique

#### Opção B: Importação em Lote
1. Vá em **Produtos > Importar**
2. Use o arquivo CSV de exemplo
3. Faça upload e configure mapeamento
4. Importe

#### Opção C: Script de Importação
```bash
php scripts/import-products.php
```

### 10. Configurar SSL/HTTPS

1. Instale certificado SSL (Let's Encrypt recomendado)
2. No WordPress, vá em **Configurações > Geral**
3. Altere URLs para HTTPS
4. Use plugin "Really Simple SSL" para forçar HTTPS

### 11. Otimização Final

#### Configurar Cache
1. Configure WP Rocket ou W3 Total Cache
2. Ative compressão de arquivos
3. Configure cache de páginas

#### Otimizar Imagens
1. Instale Smush
2. Otimize imagens existentes
3. Configure otimização automática

#### Configurar Backup
1. Configure UpdraftPlus
2. Configure backup automático diário
3. Configure armazenamento (Google Drive, Dropbox, etc.)

## ✅ Checklist de Instalação

- [ ] Servidor configurado
- [ ] WordPress instalado
- [ ] Banco de dados criado
- [ ] WooCommerce instalado e configurado
- [ ] Tema instalado e ativado
- [ ] Plugins essenciais instalados
- [ ] Integrações configuradas
- [ ] Páginas criadas
- [ ] Menus configurados
- [ ] Categorias criadas
- [ ] Produtos cadastrados
- [ ] SSL configurado
- [ ] Backup configurado
- [ ] Testes realizados

## 🔧 Troubleshooting

### Problema: Erro de conexão com banco de dados
**Solução:** Verifique credenciais no `wp-config.php`

### Problema: Erro 500
**Solução:** Verifique permissões de arquivos (755 para pastas, 644 para arquivos)

### Problema: Plugin não ativa
**Solução:** Verifique versão do PHP e compatibilidade do plugin

### Problema: Imagens não carregam
**Solução:** Verifique permissões da pasta `wp-content/uploads/`

## 📚 Próximos Passos

Após a instalação:
1. Configure produtos
2. Configure métodos de pagamento
3. Configure métodos de envio
4. Teste o checkout
5. Teste as integrações
6. Configure SEO
7. Configure analytics

## 🆘 Suporte

Para problemas ou dúvidas:
- Consulte a documentação
- Verifique logs de erro
- Entre em contato com o suporte

---

**Última atualização:** Janeiro 2026
