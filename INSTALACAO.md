# 🚀 Guia de Instalação Completo - Toy Sport

## Pré-requisitos

- Docker Desktop instalado
- Docker Compose instalado
- Git (opcional)

## Passo a Passo

### 1. Clonar/Baixar o Projeto

```bash
git clone <url-do-repositorio> ToySport
cd ToySport
```

### 2. Configurar Docker

O arquivo `docker-compose.yml` já está configurado. Basta iniciar:

```bash
docker-compose up -d
```

Isso irá criar e iniciar:
- WordPress (porta 8089)
- MySQL 8.0 (banco de dados)
- phpMyAdmin (porta 8088)

### 3. Acessar o WordPress

Abra seu navegador em: **http://localhost:8089**

### 4. Instalar o WordPress

Siga o assistente de instalação:

- **Título do site:** Toy Sport
- **Usuário:** escolha um nome de usuário
- **Senha:** escolha uma senha forte
- **Email:** seu email

### 5. Instalar WooCommerce

1. No painel WordPress, vá em **Plugins > Adicionar Novo**
2. Busque "WooCommerce"
3. Clique em **Instalar** e depois **Ativar**
4. Siga o assistente de configuração:
   - Endereço da loja
   - Moeda: Real Brasileiro (R$)
   - Métodos de pagamento
   - Métodos de envio

### 6. Ativar o Tema

1. Vá em **Aparência > Temas**
2. O tema "Toy Sport" já deve estar disponível
3. Clique em **Ativar**

### 7. Configurar o Tema

1. Vá em **Aparência > Personalizar**
2. Configure:
   - **Logo:** Faça upload do logo
   - **Número do WhatsApp:** Configure o número
   - **Endereço da Loja:** Para Schema.org
   - **Cores:** Personalize as cores (opcional)

### 8. Criar Páginas Essenciais

Execute o script de configuração:

```bash
php scripts/setup-plugins.php
```

Ou crie manualmente em **Páginas > Adicionar Nova**:

- Home
- Sobre Nós
- Contato
- Política de Devolução
- Rastreamento
- Política de Privacidade
- Termos e Condições

### 9. Configurar Menus

1. Vá em **Aparência > Menus**
2. Crie um novo menu "Menu Principal"
3. Adicione:
   - Páginas
   - Categorias de produtos
   - Links customizados
4. Atribua ao local "Menu Principal"

### 10. Criar Categorias de Produtos

1. Vá em **Produtos > Categorias**
2. Crie as categorias:
   - Adultos (com subcategorias)
   - Bebês
   - Drones
   - Meninas (com subcategorias)
   - Meninos (com subcategorias)

### 11. Importar Produtos (Opcional)

Você pode importar produtos usando o script:

```bash
php scripts/import-products.php scripts/products-sample.csv
```

Ou criar manualmente em **Produtos > Adicionar Novo**

### 12. Instalar Plugins Essenciais

#### Core
- **WooCommerce** (já instalado)
- **WooCommerce Mercado Livre** - Para integração
- **Contact Form 7** - Para formulários

#### SEO
- **Yoast SEO** ou **Rank Math**
- O tema já inclui SEO otimizado

#### Performance
- **WP Rocket** ou **W3 Total Cache**
- **Smush** - Otimização de imagens

#### Segurança
- **Wordfence Security**
- **UpdraftPlus** - Backup

### 13. Configurar Integrações

#### Mercado Livre
1. Acesse: https://developers.mercadolivre.com.br/
2. Crie uma aplicação
3. Obtenha App ID e Client Secret
4. Configure no plugin do Mercado Livre

#### Wuzapi (WhatsApp)
1. Acesse: https://wuzapi.com.br/
2. Crie conta e instância
3. Obtenha API Key e Instance ID
4. Configure no tema (Personalizar > Informações de Contato)

#### Inteligência Artificial
1. Acesse: https://platform.openai.com/
2. Crie conta e gere API Key
3. Configure no plugin de IA

### 14. Testar

- ✅ Teste a navegação
- ✅ Teste a busca
- ✅ Teste o carrinho
- ✅ Teste o checkout
- ✅ Teste o WhatsApp button
- ✅ Teste em mobile

## Comandos Úteis

```bash
# Ver logs
docker-compose logs -f

# Parar containers
docker-compose stop

# Reiniciar containers
docker-compose restart

# Acessar shell do WordPress
docker-compose exec wordpress bash

# Acessar phpMyAdmin
# http://localhost:8088
# Usuário: toysport
# Senha: toysport123
```

## Próximos Passos

1. Personalizar cores do tema (se necessário)
2. Adicionar mais produtos
3. Configurar métodos de pagamento
4. Configurar métodos de envio
5. Configurar Google Analytics
6. Otimizar imagens
7. Configurar backup automático

## Suporte

Para problemas ou dúvidas:
- Consulte a documentação em `docs/`
- Verifique os logs do Docker
- Entre em contato com o suporte

---

**Desenvolvido por CONEXT** | 2026
