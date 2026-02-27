# Estrutura de Páginas - Toy Sport

## 📄 Páginas Principais

### Página Inicial (Home)
- **URL:** `/` ou `/home`
- **Conteúdo:**
  - Banner principal (slider ou imagem estática)
  - Seção "Destaques" (produtos em destaque)
  - Seção "Categorias" (grid de categorias principais)
  - Seção "Lançamentos"
  - Seção "Mais Vendidos"
  - Seção "Benefícios" (frete grátis, parcelamento, etc.)
  - Seção "Newsletter" (opcional)

### Páginas Institucionais

#### Sobre Nós
- **URL:** `/sobre-nos`
- **Conteúdo:**
  - História da empresa
  - Missão e valores
  - Equipe (opcional)
  - Localização física

#### Contato / Fale Conosco
- **URL:** `/contato` ou `/fale-conosco`
- **Conteúdo:**
  - Formulário de contato
  - Informações de contato (telefone, email, WhatsApp)
  - Endereço físico
  - Mapa (Google Maps)
  - Horário de funcionamento

### Páginas de Ajuda

#### Política de Devolução e Trocas
- **URL:** `/politica-devolucao-trocas`
- **Conteúdo:**
  - Prazo para devolução
  - Condições de devolução
  - Processo de troca
  - Política de reembolso

#### Rastreamento de Pedidos
- **URL:** `/rastreamento-pedidos`
- **Conteúdo:**
  - Formulário para rastrear pedido
  - Integração com sistema de rastreamento

#### Minha Conta
- **URL:** `/minha-conta`
- **Funcionalidades:**
  - Login/Registro
  - Pedidos
  - Endereços
  - Detalhes da conta
  - Favoritos/Wishlist

### Páginas de Produtos

#### Catálogo / Todos os Produtos
- **URL:** `/produtos` ou `/loja`
- **Funcionalidades:**
  - Grid de produtos
  - Filtros (categoria, preço, marca)
  - Ordenação (preço, popularidade, novo)
  - Paginação
  - Busca

#### Página Individual de Produto
- **URL:** `/produto/[slug-do-produto]`
- **Conteúdo:**
  - Galeria de imagens
  - Título e descrição
  - Preço (à vista e parcelado)
  - Opções de variação (cor, tamanho, etc.)
  - Botão "Adicionar ao Carrinho"
  - Descrição detalhada
  - Avaliações e comentários
  - Produtos relacionados
  - Informações de frete
  - Compartilhamento social

### Páginas de Categorias

#### Categoria: Adultos
- **URL:** `/categoria/adultos`
- **Subcategorias:**
  - Barcos (`/categoria/barcos`)
  - Bicicleta Elétrica (`/categoria/bicicleta-eletrica`)
  - Brinquedos Agro (`/categoria/brinquedos-agro`)
  - Caixa de Som (`/categoria/caixa-de-som`)
  - Carrinho de Controle Remoto (`/categoria/carrinho-controle-remoto`)
  - Quadriciclo Elétrico (`/categoria/quadriciclo-eletrico`)
  - Miniaturas (`/categoria/miniaturas`)
  - Patinete Elétrico (`/categoria/patinete-eletrico`)
  - Pelúcias (`/categoria/pelucias`)

#### Categoria: Bebês
- **URL:** `/categoria/bebes`

#### Categoria: Drones
- **URL:** `/categoria/drones`

#### Categoria: Meninas
- **URL:** `/categoria/meninas`
- **Subcategorias:**
  - Bonecas (`/categoria/bonecas`)
  - Miçangas (`/categoria/micangas`)
  - Ursos (`/categoria/ursos`)

#### Categoria: Meninos
- **URL:** `/categoria/meninos`
- **Subcategorias:**
  - Carrinhos de Controle (`/categoria/carrinhos-controle`)

### Páginas de Processo de Compra

#### Carrinho
- **URL:** `/carrinho`
- **Funcionalidades:**
  - Lista de produtos adicionados
  - Quantidade (editar/remover)
  - Cálculo de frete
  - Cupom de desconto
  - Resumo do pedido
  - Botão "Finalizar Compra"

#### Checkout
- **URL:** `/finalizar-compra`
- **Funcionalidades:**
  - Informações de entrega
  - Métodos de envio (frete)
  - Métodos de pagamento
  - Revisão do pedido
  - Finalização

#### Confirmação de Pedido
- **URL:** `/pedido-recebido/[order-id]`
- **Conteúdo:**
  - Número do pedido
  - Detalhes do pedido
  - Informações de pagamento
  - Endereço de entrega
  - Botão de rastreamento
  - Dados para contato

### Páginas Auxiliares

#### Busca
- **URL:** `/busca?q=[termo]`
- **Funcionalidades:**
  - Resultados de busca
  - Filtros de resultados

#### Favoritos / Wishlist
- **URL:** `/favoritos` ou `/lista-desejos`
- **Funcionalidades:**
  - Lista de produtos favoritos
  - Adicionar/remover
  - Compartilhar lista

#### Política de Privacidade
- **URL:** `/politica-privacidade`
- **Conteúdo obrigatório LGPD**

#### Termos e Condições
- **URL:** `/termos-condicoes`

## 🎨 Estrutura de Layout

### Header (Cabeçalho)
- Logo da Toy Sport
- Menu de navegação principal
- Busca
- Conta do usuário
- Carrinho (com contador)
- WhatsApp fixo (botão flutuante)

### Footer (Rodapé)
- **Seção Institucional:**
  - Links para páginas institucionais
  - Sobre nós
  - Contato
- **Seção Ajuda:**
  - Política de devolução
  - Rastreamento
  - Minha conta
- **Seção Pagamento:**
  - Ícones de métodos de pagamento
  - Bandeiras de cartão
  - Pix, Boleto
- **Seção Redes Sociais:**
  - Facebook
  - Instagram
  - WhatsApp
- **Informações Legais:**
  - Política de privacidade
  - Termos e condições
  - Copyright
- **Destaques:**
  - "Receba em casa - Enviamos para todo Brasil"
  - "Devolução - 7 dias após recebimento"
  - "Parcele em até 10x - Toda loja em até 10x sem juros"
  - "Site 100% seguro - Loja com espaço físico localizada em Xanxerê-SC"

## 📱 Estrutura Mobile

- Menu hambúrguer
- Busca mobile
- Carrinho mobile
- Botão WhatsApp fixo
- Navegação simplificada
- Cards de produto otimizados

## 🔗 URLs e Permalinks

### Estrutura Recomendada
- Produtos: `/produto/[nome-produto]`
- Categorias: `/categoria/[nome-categoria]`
- Páginas: `/[slug-pagina]`

### Configuração WordPress
- Settings > Permalinks > Post name (Custom Structure: `/%postname%/`)

---

**Última atualização:** Janeiro 2026
