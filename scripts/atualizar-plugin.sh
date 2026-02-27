#!/bin/bash
# Script para atualizar plugin WordPress via Git/SSH
# Uso: ./atualizar-plugin.sh nome-do-plugin versao usuario servidor

PLUGIN_NAME=$1
VERSION=$2
USERNAME=$3
SERVER=$4
REMOTE_PATH="/var/www/html/wp-content/plugins"

# Validação
if [ -z "$PLUGIN_NAME" ] || [ -z "$VERSION" ] || [ -z "$USERNAME" ] || [ -z "$SERVER" ]; then
    echo "ERRO: Uso: $0 <nome-plugin> <versao> <usuario> <servidor>"
    echo "Exemplo: $0 meu-plugin 1.2.4 usuario servidor.com"
    exit 1
fi

echo "========================================"
echo "Atualizando Plugin WordPress"
echo "========================================"
echo "Plugin: $PLUGIN_NAME"
echo "Versão: $VERSION"
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
    
    # Faz backup
    echo "Criando backup..."
    BACKUP_DIR="../${PLUGIN_NAME}-backup-\$(date +%Y%m%d-%H%M%S)"
    cp -r . "\$BACKUP_DIR"
    echo "Backup criado em: \$BACKUP_DIR"
    
    # Atualiza via Git (se for repositório Git)
    if [ -d ".git" ]; then
        echo "Atualizando via Git..."
        git fetch origin
        git checkout "tags/v$VERSION" 2>/dev/null || git pull origin main
        
        # Verifica se atualização foi bem-sucedida
        if [ \$? -eq 0 ]; then
            echo "Atualização via Git concluída!"
        else
            echo "ERRO: Falha na atualização via Git"
            exit 1
        fi
    else
        echo "AVISO: Não é um repositório Git. Atualize manualmente via FTP."
    fi
    
    # Desativa e reativa plugin via WP-CLI (se disponível)
    if command -v wp &> /dev/null; then
        echo "Reativando plugin via WP-CLI..."
        wp plugin deactivate $PLUGIN_NAME --allow-root
        wp plugin activate $PLUGIN_NAME --allow-root
        
        # Verifica versão instalada
        INSTALLED_VERSION=\$(wp plugin get $PLUGIN_NAME --field=version --allow-root 2>/dev/null)
        echo "Versão instalada: \$INSTALLED_VERSION"
    else
        echo "AVISO: WP-CLI não encontrado. Reative o plugin manualmente no WordPress Admin."
    fi
    
    # Ajusta permissões
    echo "Ajustando permissões..."
    find . -type f -exec chmod 644 {} \;
    find . -type d -exec chmod 755 {} \;
    
    echo ""
    echo "========================================"
    echo "Atualização concluída!"
    echo "========================================"
EOF

if [ $? -eq 0 ]; then
    echo ""
    echo "Plugin atualizado com sucesso!"
else
    echo ""
    echo "ERRO: Falha na atualização"
    exit 1
fi
