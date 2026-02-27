# Toy Sport - E-commerce WordPress/WooCommerce

## 📋 Visão Geral do Projeto

Este projeto consiste na recriação completa do site **toysport.com.br** utilizando WordPress com WooCommerce, incluindo integrações com Mercado Livre, Inteligência Artificial e WhatsApp (Wuzapi).

## 🎯 Objetivos

- Recriar o site toysport.com.br com WordPress + WooCommerce
- Sincronização automática de produtos com Mercado Livre
- Integração com Inteligência Artificial para atendimento
- Integração com WhatsApp via Wuzapi
- Estrutura completa de páginas e categorias

## 📁 Estrutura do Projeto

```
ToySport/
├── README.md                          # Este arquivo
├── INSTALACAO.md                      # Guia completo de instalação
├── docker-start.md                    # Guia rápido Docker
├── docker-compose.yml                 # Configuração Docker
├── Dockerfile                         # Dockerfile do WordPress
├── php.ini                            # Configuração PHP
├── .env.example                       # Exemplo de variáveis de ambiente
├── docs/                              # Documentação do projeto
│   ├── requisitos.md                  # Especificações técnicas
│   ├── estrutura-paginas.md           # Estrutura de páginas do site
│   ├── categorias-produtos.md         # Categorias e produtos
│   ├── integracoes.md                 # Detalhes das integrações
│   └── instalacao.md                  # Guia detalhado de instalação
├── wordpress/                         # Arquivos WordPress
│   └── wp-content/
│       └── themes/
│           └── toysport/              # Tema customizado moderno
│               ├── assets/
│               │   ├── css/           # Estilos customizados
│               │   └── js/            # JavaScript
│               ├── woocommerce/       # Templates WooCommerce
│               └── template-parts/    # Partes do template
└── scripts/                           # Scripts de instalação
    ├── setup-plugins.php              # Configuração inicial
    ├── import-products.php            # Importação de produtos
    ├── products-sample.csv            # Exemplo de produtos
    └── install.ps1                    # Script Windows
```

## 🚀 Instalação Rápida com Docker (Recomendado)

### Pré-requisitos

- **Docker Desktop** instalado
- **Docker Compose** instalado

### Instalação em 3 Passos

1. **Clone ou baixe este projeto**
   ```bash
   git clone <url> ToySport
   cd ToySport
   ```

2. **Inicie o ambiente Docker**
   ```bash
   docker-compose up -d
   ```

3. **Acesse o WordPress**
   - **Site:** http://localhost:8089
   - **phpMyAdmin:** http://localhost:8088
   - **Usuário do banco:** `toysport`
   - **Senha do banco:** `toysport123`

### Próximos Passos

1. Acesse http://localhost:8089 e siga o assistente de instalação do WordPress
2. Instale e ative o WooCommerce
3. Ative o tema "Toy Sport"
4. Configure as integrações (veja `docs/integracoes.md`)

📖 **Para instruções detalhadas, consulte `INSTALACAO.md` e `docker-start.md`**

## 🔌 Plugins Principais

- **WooCommerce** - E-commerce principal
- **WooCommerce Mercado Livre** - Integração com Mercado Livre
- **Wuzapi** - Integração WhatsApp
- **Inteligência Artificial** - Atendimento automatizado
- **Yoast SEO** - Otimização para buscadores
- **WP Rocket** - Cache e performance

## 📚 Documentação

Consulte a pasta `docs/` para documentação completa:
- Requisitos do sistema
- Estrutura de páginas
- Categorias e produtos
- Configuração de integrações
- Guia de instalação

## 🤝 Suporte

Para dúvidas ou suporte, consulte a documentação ou entre em contato com a equipe de desenvolvimento.

---

**Desenvolvido por CONEXT** | 2026
