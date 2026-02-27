#!/bin/bash
# Script para atualizar plugin TS-ML Integration via Git
# Uso: ./atualizar-plugin-git.sh [versao] [usuario] [servidor]

VERSION=$1
USERNAME=$2
SERVER=$3
PLUGIN_NAME="ts-ml-integration"
REMOTE_PATH="/var/www/html/wp-content/plugins"

# Validação
if [ -z "$USERNAME" ] || [ -z "$SERVER" ]; then
    echo "ERRO: Uso: $0 [versao] <usuario> <servidor>"
    echo "Exemplo: $0 1.1.0 usuario servidor.com"
    exit 1
fi

echo "========================================"
echo "Atualizar Plugin TS-ML Integration via Git"
echo "========================================"
echo "Plugin: $PLUGIN_NAME"
if [ -n "$VERSION" ]; then
    echo "Versão esperada: $VERSION"
fi
echo "Servidor: $USERNAME@$SERVER"
echo ""

# Conecta ao servidor e atualiza
ssh "$USERNAME@$SERVER" << EOF
    echo "Conectado ao servidor..."
    
    # Navega até o diretório do plugin
    cd $REMOTE_PATH/$PLUGIN_NAME || {
        echo "ERRO: Plugin não encontrado em $REMOTE_PATH/$PLUGIN_NAME"
        exit 1
    }
    
    # Verifica se é repositório Git
    if [ ! -d ".git" ]; then
        echo "AVISO: Não é um repositório Git. Inicializando..."
        git init
        git remote add origin https://github.com/seu-usuario/$PLUGIN_NAME.git 2>/dev/null || {
            echo "AVISO: Repositório remoto não configurado."
            echo "Configure manualmente com: git remote add origin <url>"
        }
    fi
    
    # Verifica versão atual instalada (via WP-CLI se disponível)
    if command -v wp &> /dev/null; then
        CURRENT_VERSION=\$(wp option get ts_ml_version --allow-root 2>/dev/null || echo "0.0.0")
        echo "Versão atual instalada: \$CURRENT_VERSION"
    fi
    
    # Faz backup antes de atualizar
    echo ""
    echo "Criando backup..."
    BACKUP_DIR="../${PLUGIN_NAME}-backup-\$(date +%Y%m%d-%H%M%S)"
    cp -r . "\$BACKUP_DIR"
    echo "Backup criado em: \$BACKUP_DIR"
    
    # Verifica status do Git
    echo ""
    echo "Verificando status do Git..."
    git status
    
    # Faz pull da nova versão
    echo ""
    echo "Atualizando via Git..."
    git fetch origin
    
    # Se versão especificada, tenta fazer checkout da tag
    if [ -n "$VERSION" ]; then
        echo "Tentando fazer checkout da versão $VERSION..."
        git checkout "tags/v$VERSION" 2>/dev/null || git checkout "tags/$VERSION" 2>/dev/null || {
            echo "Tag não encontrada, fazendo pull da branch main..."
            git pull origin main
        }
    else
        echo "Fazendo pull da branch main..."
        git pull origin main
    fi
    
    # Verifica se atualização foi bem-sucedida
    if [ \$? -eq 0 ]; then
        echo ""
        echo "✅ Arquivos atualizados com sucesso!"
        
        # Verifica nova versão no código
        if [ -f "ts-ml-integration.php" ]; then
            NEW_VERSION=\$(grep -oP "define\('TS_ML_VERSION', '\K[^']+" ts-ml-integration.php | head -1)
            if [ -n "\$NEW_VERSION" ]; then
                echo "Nova versão no código: \$NEW_VERSION"
            fi
        fi
        
        # O sistema de atualização do plugin detectará automaticamente
        # quando o WordPress recarregar o plugin
        echo ""
        echo "ℹ️  O sistema de atualização do plugin detectará automaticamente"
        echo "   a nova versão quando o WordPress recarregar o plugin."
        echo ""
        echo "   Para forçar atualização imediata, você pode:"
        echo "   1. Desativar e reativar o plugin no WordPress Admin"
        echo "   2. Ou executar: wp plugin deactivate $PLUGIN_NAME && wp plugin activate $PLUGIN_NAME"
        
    else
        echo ""
        echo "❌ ERRO: Falha na atualização via Git"
        echo "Restaure o backup se necessário: cp -r \$BACKUP_DIR/* ."
        exit 1
    fi
    
    # Desativa e reativa plugin via WP-CLI (se disponível) para forçar atualização
    if command -v wp &> /dev/null; then
        echo ""
        read -p "Deseja reativar o plugin agora para aplicar atualizações? (s/n) " -n 1 -r
        echo
        if [[ \$REPLY =~ ^[Ss]$ ]]; then
            echo "Reativando plugin..."
            wp plugin deactivate $PLUGIN_NAME --allow-root
            wp plugin activate $PLUGIN_NAME --allow-root
            
            # Verifica versão instalada após reativação
            INSTALLED_VERSION=\$(wp option get ts_ml_version --allow-root 2>/dev/null)
            echo "Versão instalada após atualização: \$INSTALLED_VERSION"
        fi
    else
        echo ""
        echo "AVISO: WP-CLI não encontrado."
        echo "Reative o plugin manualmente no WordPress Admin para aplicar atualizações."
    fi
    
    # Ajusta permissões
    echo ""
    echo "Ajustando permissões..."
    find . -type f -exec chmod 644 {} \;
    find . -type d -exec chmod 755 {} \;
    
    echo ""
    echo "========================================"
    echo "✅ Atualização concluída!"
    echo "========================================"
EOF

if [ $? -eq 0 ]; then
    echo ""
    echo "Plugin atualizado com sucesso via Git!"
    echo ""
    echo "Próximos passos:"
    echo "1. Verifique se a atualização foi aplicada no WordPress Admin"
    echo "2. Verifique os logs em: TS ML > Relatórios"
    echo "3. Teste as funcionalidades principais"
else
    echo ""
    echo "ERRO: Falha na atualização"
    exit 1
fi
