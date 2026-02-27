#!/bin/bash
# Script para criar ZIP de plugin WordPress para atualização
# Uso: ./criar-zip-plugin.sh nome-do-plugin versao

PLUGIN_NAME=$1
VERSION=$2
PLUGIN_DIR="wordpress/wp-content/plugins/$PLUGIN_NAME"
OUTPUT_DIR="releases"

# Validação
if [ -z "$PLUGIN_NAME" ] || [ -z "$VERSION" ]; then
    echo "ERRO: Uso: $0 <nome-do-plugin> <versao>"
    echo "Exemplo: $0 meu-plugin 1.2.4"
    exit 1
fi

# Verifica se plugin existe
if [ ! -d "$PLUGIN_DIR" ]; then
    echo "ERRO: Plugin não encontrado em $PLUGIN_DIR"
    exit 1
fi

# Cria diretório de releases se não existir
mkdir -p "$OUTPUT_DIR"

# Nome do arquivo ZIP
ZIP_NAME="${PLUGIN_NAME}-${VERSION}.zip"
ZIP_PATH="${OUTPUT_DIR}/${ZIP_NAME}"

echo "========================================"
echo "Criando ZIP do Plugin"
echo "========================================"
echo "Plugin: $PLUGIN_NAME"
echo "Versão: $VERSION"
echo ""

# Remove ZIP anterior se existir
if [ -f "$ZIP_PATH" ]; then
    echo "Removendo ZIP anterior..."
    rm "$ZIP_PATH"
fi

# Cria ZIP excluindo arquivos desnecessários
echo "Criando arquivo ZIP..."
cd "$PLUGIN_DIR" || exit 1

zip -r "../../../../$ZIP_PATH" . \
    -x "*.git*" \
    -x "*.DS_Store" \
    -x "node_modules/*" \
    -x "*.log" \
    -x ".env" \
    -x ".env.local" \
    -x "*.zip" \
    -x ".idea/*" \
    -x ".vscode/*"

cd - || exit 1

# Verifica se ZIP foi criado
if [ -f "$ZIP_PATH" ]; then
    SIZE=$(du -h "$ZIP_PATH" | cut -f1)
    echo ""
    echo "========================================"
    echo "ZIP criado com sucesso!"
    echo "========================================"
    echo "Arquivo: $ZIP_PATH"
    echo "Tamanho: $SIZE"
    echo ""
    echo "Próximos passos:"
    echo "1. Faça upload do arquivo para o servidor"
    echo "2. Acesse WordPress Admin > Plugins > Adicionar Novo > Enviar Plugin"
    echo "3. Ou use WP-CLI: wp plugin install $ZIP_PATH --force --activate"
else
    echo "ERRO: Falha ao criar ZIP"
    exit 1
fi
