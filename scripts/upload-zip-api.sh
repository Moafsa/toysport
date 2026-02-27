#!/bin/bash
# Script para fazer upload de ZIP para servidor da API
# Uso: ./upload-zip-api.sh versao usuario servidor [caminho-local]

VERSION=$1
USERNAME=$2
SERVER=$3
LOCAL_PATH=$4
PLUGIN_NAME="ts-ml-integration"
REMOTE_PATH="/var/www/html/downloads"

# Validação
if [ -z "$VERSION" ] || [ -z "$USERNAME" ] || [ -z "$SERVER" ]; then
    echo "ERRO: Uso: $0 <versao> <usuario> <servidor> [caminho-local]"
    echo "Exemplo: $0 1.1.0 usuario api.seusite.com.br"
    exit 1
fi

# Caminho padrão do ZIP local
if [ -z "$LOCAL_PATH" ]; then
    LOCAL_PATH="releases/${PLUGIN_NAME}-${VERSION}.zip"
fi

# Verificar se ZIP existe
if [ ! -f "$LOCAL_PATH" ]; then
    echo "ERRO: Arquivo não encontrado: $LOCAL_PATH"
    echo ""
    echo "Criar ZIP primeiro:"
    echo "  ./criar-zip-plugin.sh $PLUGIN_NAME $VERSION"
    exit 1
fi

ZIP_NAME="${PLUGIN_NAME}-${VERSION}.zip"
REMOTE_FILE="${REMOTE_PATH}/${ZIP_NAME}"

echo "========================================"
echo "Upload ZIP para Servidor da API"
echo "========================================"
echo "Versão: $VERSION"
echo "Arquivo: $LOCAL_PATH"
echo "Servidor: $USERNAME@$SERVER"
echo "Destino: $REMOTE_FILE"
echo ""

# Upload via SCP
echo "Fazendo upload..."
scp "$LOCAL_PATH" "$USERNAME@$SERVER:$REMOTE_FILE"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Upload concluído!"
    echo ""
    
    # Verificar no servidor
    echo "Verificando no servidor..."
    ssh "$USERNAME@$SERVER" << EOF
        if [ -f "$REMOTE_FILE" ]; then
            SIZE=\$(du -h "$REMOTE_FILE" | cut -f1)
            PERMS=\$(stat -c "%a" "$REMOTE_FILE")
            echo "✅ Arquivo encontrado: $REMOTE_FILE"
            echo "   Tamanho: \$SIZE"
            echo "   Permissões: \$PERMS"
            
            # Ajustar permissões se necessário
            if [ "\$PERMS" != "644" ]; then
                chmod 644 "$REMOTE_FILE"
                echo "   Permissões ajustadas para 644"
            fi
        else
            echo "❌ Arquivo não encontrado após upload!"
            exit 1
        fi
        
        # Listar todos os ZIPs
        echo ""
        echo "ZIPs disponíveis no servidor:"
        ls -lh "$REMOTE_PATH"/*.zip 2>/dev/null || echo "Nenhum ZIP encontrado"
EOF
    
    echo ""
    echo "========================================"
    echo "✅ Upload concluído com sucesso!"
    echo "========================================"
    echo ""
    echo "URL de download:"
    echo "https://$(echo $SERVER | cut -d'@' -f2)/downloads/$ZIP_NAME"
    echo ""
    echo "Próximos passos:"
    echo "1. Atualize versão na API: api/update-api.php"
    echo "2. Commit e push: git push"
    echo "3. Teste API: curl -X POST https://$(echo $SERVER | cut -d'@' -f2)/api/update-api.php -d 'action=version_check&version=1.0.0'"
else
    echo ""
    echo "❌ ERRO: Falha no upload"
    exit 1
fi
