# Integrações - Toy Sport

## 🔗 Integração com Mercado Livre

### Objetivo
Sincronização automática de produtos entre WooCommerce e Mercado Livre. Quando um produto for cadastrado no site, ele deve aparecer automaticamente no Mercado Livre.

### Plugin Recomendado
- **WooCommerce Mercado Livre Integration** (Plugin oficial ou customizado)
- **Alternativa:** WooCommerce Brazil (inclui integração básica)

### Configuração Necessária

#### 1. Credenciais do Mercado Livre
```
App ID: [Seu App ID]
Client Secret: [Seu Client Secret]
Access Token: [Token de acesso]
Refresh Token: [Token de renovação]
Site ID: MLB (Mercado Livre Brasil)
```

#### 2. Onde Obter as Credenciais
1. Acesse: https://developers.mercadolivre.com.br/
2. Crie uma aplicação
3. Obtenha o App ID e Client Secret
4. Configure as URLs de callback
5. Gere os tokens de acesso

#### 3. Configurações do Plugin

**Sincronização Automática:**
- ✅ Sincronizar produtos ao criar
- ✅ Sincronizar produtos ao atualizar
- ✅ Atualizar estoque automaticamente
- ✅ Atualizar preços automaticamente
- ✅ Importar pedidos do Mercado Livre

**Mapeamento de Categorias:**
- Mapear categorias WooCommerce → Categorias ML
- Configurar atributos obrigatórios do ML

**Configurações de Envio:**
- Métodos de envio aceitos
- Custo de frete
- Prazo de entrega

#### 4. Funcionalidades Implementadas

**Ao Criar/Atualizar Produto no WooCommerce:**
1. Verifica se produto já existe no ML
2. Se não existe, cria novo anúncio
3. Se existe, atualiza informações
4. Faz upload de imagens
5. Configura categoria e atributos
6. Define preço e estoque
7. Publica o anúncio

**Atualização de Estoque:**
- Webhook do ML → Atualiza WooCommerce
- WooCommerce → Atualiza ML via API

**Importação de Pedidos:**
- Verifica novos pedidos no ML periodicamente
- Cria pedidos no WooCommerce
- Atualiza status de envio

### Código de Exemplo (Hook WordPress)

```php
// Função para sincronizar produto com Mercado Livre
add_action('woocommerce_new_product', 'sync_product_to_mercadolivre');
add_action('woocommerce_update_product', 'sync_product_to_mercadolivre');

function sync_product_to_mercadolivre($product_id) {
    // Obter dados do produto
    $product = wc_get_product($product_id);
    
    // Preparar dados para ML API
    $ml_data = prepare_ml_product_data($product);
    
    // Enviar para Mercado Livre via API
    send_to_mercadolivre($ml_data);
}

function prepare_ml_product_data($product) {
    return [
        'title' => $product->get_name(),
        'category_id' => map_wc_category_to_ml($product->get_category_ids()),
        'price' => $product->get_price(),
        'currency_id' => 'BRL',
        'available_quantity' => $product->get_stock_quantity(),
        'buying_mode' => 'buy_it_now',
        'listing_type_id' => 'gold_special',
        'condition' => 'new',
        'description' => $product->get_description(),
        'pictures' => get_product_images_urls($product),
        'attributes' => get_ml_attributes($product),
        'shipping' => [
            'mode' => 'me2',
            'free_shipping' => false
        ]
    ];
}
```

## 💬 Integração WhatsApp (Wuzapi)

### Objetivo
Integrar WhatsApp para atendimento automatizado, notificações e suporte ao cliente.

### Configuração Wuzapi

#### 1. Credenciais
```
API Key: [Sua chave da API Wuzapi]
Instance ID: [ID da instância]
API URL: https://api.wuzapi.com.br
```

#### 2. Onde Obter
1. Acesse: https://wuzapi.com.br/
2. Crie uma conta
3. Crie uma instância
4. Obtenha a API Key e Instance ID

#### 3. Funcionalidades Implementadas

**Envio de Mensagens:**
- Notificação de pedido confirmado
- Notificação de envio
- Notificação de entrega
- Mensagens de marketing (opcional)

**Recebimento de Mensagens:**
- Webhook para receber mensagens
- Processamento de mensagens
- Integração com IA para respostas

**Bot WhatsApp Fixo:**
- Botão flutuante no site
- Abre conversa direta
- Mensagem pré-configurada

### Código de Exemplo

```php
// Função para enviar mensagem via Wuzapi
function send_wuzapi_message($phone, $message) {
    $api_key = get_option('wuzapi_api_key');
    $instance_id = get_option('wuzapi_instance_id');
    
    $url = "https://api.wuzapi.com.br/v1/messages/send";
    
    $data = [
        'instance' => $instance_id,
        'to' => $phone,
        'message' => $message
    ];
    
    $response = wp_remote_post($url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json'
        ],
        'body' => json_encode($data)
    ]);
    
    return json_decode(wp_remote_retrieve_body($response), true);
}

// Notificar cliente ao criar pedido
add_action('woocommerce_new_order', 'notify_order_via_wuzapi');

function notify_order_via_wuzapi($order_id) {
    $order = wc_get_order($order_id);
    $phone = $order->get_billing_phone();
    $message = "Olá! Seu pedido #{$order_id} foi confirmado. Obrigado pela compra!";
    
    send_wuzapi_message($phone, $message);
}
```

## 🤖 Integração Inteligência Artificial

### Objetivo
Atendimento automatizado inteligente via chat e WhatsApp, com sugestões de produtos e processamento de pedidos.

### Provedores Recomendados
1. **OpenAI (GPT-4/3.5)** - Recomendado
2. **Google Gemini** - Alternativa
3. **Solução Customizada** - Para necessidades específicas

### Configuração OpenAI

#### 1. Credenciais
```
API Key: [Sua chave da API OpenAI]
Model: gpt-4 ou gpt-3.5-turbo
Organization: [ID da organização, opcional]
```

#### 2. Onde Obter
1. Acesse: https://platform.openai.com/
2. Crie uma conta
3. Gere uma API Key
4. Configure billing (cobrança por uso)

### Funcionalidades Implementadas

**Chatbot no Site:**
- Widget de chat flutuante
- Respostas em tempo real
- Sugestão de produtos
- Assistência na navegação

**Chatbot WhatsApp:**
- Integração com Wuzapi
- Processamento de mensagens
- Respostas contextuais
- Processamento de pedidos

**Recursos da IA:**
- Busca de produtos
- Recomendação baseada em histórico
- Suporte ao cliente 24/7
- Processamento de linguagem natural
- Geração de respostas contextuais

### Código de Exemplo

```php
// Função para processar mensagem com IA
function process_message_with_ai($message, $context = []) {
    $api_key = get_option('openai_api_key');
    
    $url = 'https://api.openai.com/v1/chat/completions';
    
    $system_prompt = "Você é um assistente virtual da Toy Sport, uma loja de brinquedos. 
    Ajude os clientes a encontrar produtos, tirar dúvidas sobre pedidos e fornecer informações sobre a loja.
    Seja amigável, profissional e útil.";
    
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => $system_prompt],
            ['role' => 'user', 'content' => $message]
        ],
        'temperature' => 0.7,
        'max_tokens' => 500
    ];
    
    $response = wp_remote_post($url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json'
        ],
        'body' => json_encode($data)
    ]);
    
    $result = json_decode(wp_remote_retrieve_body($response), true);
    
    return $result['choices'][0]['message']['content'] ?? 'Desculpe, não consegui processar sua mensagem.';
}

// Hook para processar mensagens do WhatsApp
add_action('wuzapi_received_message', 'handle_wuzapi_message_with_ai');

function handle_wuzapi_message_with_ai($data) {
    $message = $data['message'];
    $phone = $data['from'];
    
    // Processar com IA
    $response = process_message_with_ai($message);
    
    // Enviar resposta via Wuzapi
    send_wuzapi_message($phone, $response);
}
```

## 🔄 Fluxo de Integração Completo

### Cenário: Cliente compra produto

1. **Cliente adiciona produto ao carrinho no site**
2. **Cliente finaliza compra**
3. **WooCommerce cria pedido**
4. **Sistema notifica via Wuzapi:** "Pedido confirmado"
5. **Produto sincroniza com Mercado Livre** (se necessário atualizar estoque)
6. **Cliente envia mensagem no WhatsApp:** "Qual o status do meu pedido?"
7. **IA processa mensagem e responde:** "Seu pedido #123 está em separação..."
8. **Quando pedido é enviado:** Notificação automática via Wuzapi
9. **Cliente recebe produto e pergunta no WhatsApp:** "Como faço troca?"
10. **IA responde com informações de troca**

## 📋 Checklist de Configuração

### Mercado Livre
- [ ] Conta no Mercado Livre criada
- [ ] Aplicação criada no ML Developers
- [ ] App ID e Client Secret obtidos
- [ ] Tokens de acesso configurados
- [ ] Plugin instalado e configurado
- [ ] Categorias mapeadas
- [ ] Teste de sincronização realizado

### Wuzapi
- [ ] Conta Wuzapi criada
- [ ] Instância criada
- [ ] API Key e Instance ID obtidos
- [ ] Webhook configurado
- [ ] Plugin customizado criado/instalado
- [ ] Teste de envio/recebimento realizado

### Inteligência Artificial
- [ ] Conta no provedor criada (OpenAI/Gemini)
- [ ] API Key gerada
- [ ] Plugin customizado criado
- [ ] Prompts configurados
- [ ] Integração com Wuzapi configurada
- [ ] Testes de chatbot realizados

---

**Última atualização:** Janeiro 2026
