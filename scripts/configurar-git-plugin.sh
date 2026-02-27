#!/bin/bash
# Script para configurar Git no plugin após instalação via ZIP
# Uso: ./configurar-git-plugin.sh [usuario] [servidor] [repo-url]

USERNAME=$1
SERVER=$2
REPO_URL=$3
PLUGIN_NAME="ts-ml-integration"
REMOTE_PATH="/var/www/html/wp-content/plugins"

# Validação
if [ -z "$USERNAME" ] || [ -z "$SERVER" ]; then
    echo "ERRO: Uso: $0 <usuario> <servidor> [repo-url]"
    echo "Exemplo: $0 usuario servidor.com https://github.com/usuario/ts-ml-integration.git"
    exit 1
fi

echo "========================================"
echo "Configurar Git no Plugin TS-ML Integration"
echo "========================================"
echo "Plugin: $PLUGIN_NAME"
echo "Servidor: $USERNAME@$SERVER"
echo ""

# Conecta ao servidor e configura Git
ssh "$USERNAME@$SERVER" << EOF
    echo "Conectado ao servidor..."
    
    # Navega até o diretório do plugin
    cd $REMOTE_PATH/$PLUGIN_NAME || {
        echo "ERRO: Plugin não encontrado em $REMOTE_PATH/$PLUGIN_NAME"
        exit 1
    }
    
    # Verifica se já é repositório Git
    if [ -d ".git" ]; then
        echo "AVISO: Já é um repositório Git."
        echo "Remoto atual:"
        git remote -v
        read -p "Deseja reconfigurar? (s/n) " -n 1 -r
        echo
        if [[ ! \$REPLY =~ ^[Ss]$ ]]; then
            echo "Cancelado."
            exit 0
        fi
    fi
    
    # Inicializa Git se necessário
    if [ ! -d ".git" ]; then
        echo "Inicializando repositório Git..."
        git init
    fi
    
    # Configura repositório remoto
    if [ -n "$REPO_URL" ]; then
        echo ""
        echo "Configurando repositório remoto..."
        
        # Remove remoto existente se houver
        git remote remove origin 2>/dev/null
        
        # Adiciona novo remoto
        git remote add origin "$REPO_URL"
        
        echo "Repositório remoto configurado: $REPO_URL"
    else
        echo ""
        echo "AVISO: URL do repositório não fornecida."
        echo "Configure manualmente com:"
        echo "  git remote add origin <url-do-repositorio>"
    fi
    
    # Faz primeiro commit dos arquivos atuais
    echo ""
    echo "Fazendo commit inicial dos arquivos..."
    git add .
    
    # Verifica versão atual do plugin
    if [ -f "ts-ml-integration.php" ]; then
        VERSION=\$(grep -oP "define\('TS_ML_VERSION', '\K[^']+" ts-ml-integration.php | head -1)
        if [ -n "\$VERSION" ]; then
            COMMIT_MSG="Versão \$VERSION instalada via ZIP"
        else
            COMMIT_MSG="Instalação inicial via ZIP"
        fi
    else
        COMMIT_MSG="Instalação inicial via ZIP"
    fi
    
    git commit -m "\$COMMIT_MSG" || {
        echo "AVISO: Nenhuma mudança para commitar ou commit falhou."
    }
    
    # Configura branch principal
    git branch -M main 2>/dev/null || true
    
    # Mostra status
    echo ""
    echo "========================================"
    echo "✅ Configuração concluída!"
    echo "========================================"
    echo ""
    echo "Status do repositório:"
    git status
    echo ""
    echo "Remotos configurados:"
    git remote -v
    echo ""
    echo "Próximos passos:"
    echo "1. Para conectar com repositório remoto:"
    echo "   git pull origin main --allow-unrelated-histories"
    echo ""
    echo "2. Para atualizar no futuro:"
    echo "   cd $REMOTE_PATH/$PLUGIN_NAME"
    echo "   git pull origin main"
    echo ""
    echo "3. Ou use o script:"
    echo "   ./atualizar-plugin-git.sh [versao] $USERNAME $SERVER"
EOF

if [ $? -eq 0 ]; then
    echo ""
    echo "Git configurado com sucesso!"
else
    echo ""
    echo "ERRO: Falha na configuração"
    exit 1
fi
