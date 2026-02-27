# Requisitos do Sistema - Toy Sport

## 📋 Visão Geral

Este documento descreve todos os requisitos técnicos e funcionais para o projeto Toy Sport.

## 🖥️ Requisitos de Servidor

### Servidor Web
- **Apache 2.4+** ou **Nginx 1.18+**
- Mod_rewrite habilitado (Apache)
- SSL/TLS (HTTPS obrigatório)

### PHP
- **Versão mínima:** PHP 7.4
- **Versão recomendada:** PHP 8.1 ou superior
- **Extensões necessárias:**
  - mysqli
  - curl
  - gd
  - mbstring
  - xml
  - zip
  - json
  - openssl

### Banco de Dados
- **MySQL 5.7+** ou **MariaDB 10.3+**
- Charset: UTF-8 (utf8mb4)
- Permissões: CREATE, ALTER, SELECT, INSERT, UPDATE, DELETE

### Recursos
- **Memória PHP:** Mínimo 256MB, recomendado 512MB
- **Tempo de execução:** 300 segundos
- **Upload máximo:** 64MB (para imagens de produtos)

## 📦 Requisitos de Software

### WordPress
- **Versão mínima:** 6.0
- **Versão recomendada:** 6.4+

### WooCommerce
- **Versão mínima:** 7.0
- **Versão recomendada:** 8.0+

## 🔌 Plugins Necessários

### Core (Essenciais)
1. **WooCommerce** (e-commerce base)
2. **WooCommerce Mercado Livre** - Sincronização automática
3. **Wuzapi** - Integração WhatsApp
4. **Elementor Pro** ou **Gutenberg** - Editor de páginas

### Integrações
5. **API Mercado Livre** - Plugin customizado ou WooCommerce Mercado Livre
6. **OpenAI / ChatGPT Integration** - Para IA de atendimento
7. **WP Webhook** - Para comunicação entre sistemas

### Pagamentos
8. **WooCommerce Mercado Pago** ou **PagSeguro**
9. **Pix para WooCommerce**
10. **Boleto para WooCommerce**

### Frete
11. **WooCommerce Correios** - Cálculo de frete
12. **WooCommerce Frete na Hora** (opcional)

### SEO e Performance
13. **Yoast SEO** ou **Rank Math**
14. **WP Rocket** ou **W3 Total Cache**
15. **Smush** - Otimização de imagens

### Segurança
16. **Wordfence** ou **iThemes Security**
17. **UpdraftPlus** - Backup automático

### Utilidades
18. **Contact Form 7** ou **WPForms**
19. **WooCommerce Wishlist**
20. **WooCommerce Product Search**

## 🎨 Tema

- Tema customizado baseado no design atual do site
- Responsivo (mobile-first)
- Compatível com WooCommerce
- Suporte a Elementor ou Gutenberg

## 🔗 Integrações Externas

### Mercado Livre
- **API Key:** Obtida no painel do Mercado Livre
- **App ID:** ID da aplicação
- **Secret Key:** Chave secreta
- **Funcionalidades:**
  - Sincronização automática de produtos
  - Atualização de estoque em tempo real
  - Gerenciamento de pedidos

### Wuzapi (WhatsApp)
- **API Key:** Chave de API do Wuzapi
- **Webhook URL:** URL para receber mensagens
- **Funcionalidades:**
  - Envio de mensagens automatizadas
  - Recebimento de mensagens
  - Notificações de pedidos

### Inteligência Artificial
- **Provedor:** OpenAI, Google Gemini, ou solução customizada
- **API Key:** Chave de API do provedor
- **Funcionalidades:**
  - Chatbot para atendimento
  - Respostas automáticas
  - Sugestões de produtos
  - Processamento de pedidos via chat

## 📱 Funcionalidades Principais

### E-commerce
- ✅ Catálogo de produtos completo
- ✅ Carrinho de compras
- ✅ Checkout otimizado
- ✅ Área do cliente
- ✅ Rastreamento de pedidos
- ✅ Cupons e descontos
- ✅ Produtos relacionados
- ✅ Busca avançada

### Mercado Livre
- ✅ Sincronização automática de produtos
- ✅ Upload de imagens
- ✅ Atualização de preços
- ✅ Gestão de estoque
- ✅ Importação de pedidos
- ✅ Atualização de status

### WhatsApp (Wuzapi)
- ✅ Atendimento automatizado
- ✅ Notificações de pedidos
- ✅ Status de entrega
- ✅ Suporte ao cliente
- ✅ Integração com IA

### Inteligência Artificial
- ✅ Chatbot inteligente
- ✅ Recomendação de produtos
- ✅ Atendimento 24/7
- ✅ Processamento de linguagem natural
- ✅ Geração de respostas contextuais

## 🔒 Segurança

- SSL/HTTPS obrigatório
- Atualizações automáticas
- Backup diário
- Firewall
- Proteção contra SQL Injection
- Proteção XSS
- Rate limiting

## 📊 Performance

- Tempo de carregamento < 3 segundos
- Otimização de imagens
- Cache de páginas
- Minificação de CSS/JS
- CDN (opcional, recomendado)

## 📈 Analytics

- Google Analytics 4
- Facebook Pixel
- WooCommerce Analytics
- Relatórios de vendas

## 🧪 Ambiente de Desenvolvimento

### Local
- **XAMPP**, **WAMP**, ou **Local by Flywheel**
- PHP 8.1+
- MySQL 8.0+

### Staging
- Ambiente de testes antes da produção
- Dados de teste
- Domínio separado

### Produção
- Hosting WordPress gerenciado ou VPS
- Backup automático
- Monitoramento 24/7

## ✅ Checklist de Instalação

- [ ] Servidor configurado com PHP 7.4+
- [ ] MySQL/MariaDB instalado e configurado
- [ ] WordPress instalado
- [ ] WooCommerce instalado e configurado
- [ ] Tema instalado
- [ ] Plugins essenciais instalados
- [ ] Integrações configuradas
- [ ] SSL configurado
- [ ] Backup configurado
- [ ] Testes realizados

---

**Última atualização:** Janeiro 2026
