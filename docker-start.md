# 🐳 Guia de Inicialização Docker - Toy Sport

## 🚀 Início Rápido

### 1. Pré-requisitos
- Docker instalado
- Docker Compose instalado

### 2. Iniciar o Ambiente

```bash
# Iniciar todos os serviços
docker-compose up -d

# Ver logs
docker-compose logs -f

# Parar os serviços
docker-compose down
```

### 3. Acessar o WordPress

- **Site:** http://localhost:8089
- **phpMyAdmin:** http://localhost:8088
- **Usuário do Banco:** toysport
- **Senha do Banco:** toysport123

### 4. Instalação do WordPress

1. Acesse http://localhost:8089
2. Siga o assistente de instalação
3. Configure:
   - Título: Toy Sport
   - Usuário administrador
   - Senha forte
   - Email

### 5. Configuração do Banco de Dados

O banco já está configurado automaticamente:
- **Nome do Banco:** toysport_db
- **Usuário:** toysport
- **Senha:** toysport123
- **Host:** db (interno do Docker)

### 6. Instalar Tema

1. Copie o tema para: `wordpress/wp-content/themes/toysport/`
2. No WordPress: **Aparência > Temas**
3. Ative o tema "Toy Sport"

### 7. Instalar WooCommerce

1. **Plugins > Adicionar Novo**
2. Busque "WooCommerce"
3. Instale e ative
4. Siga o assistente de configuração

### 8. Comandos Úteis

```bash
# Parar todos os containers
docker-compose stop

# Reiniciar containers
docker-compose restart

# Ver status
docker-compose ps

# Acessar shell do WordPress
docker-compose exec wordpress bash

# Acessar shell do MySQL
docker-compose exec db mysql -u toysport -ptoysport123 toysport_db

# Limpar tudo (CUIDADO: apaga dados)
docker-compose down -v
```

### 9. Backup

```bash
# Backup do banco de dados
docker-compose exec db mysqldump -u toysport -ptoysport123 toysport_db > backup.sql

# Restaurar backup
docker-compose exec -T db mysql -u toysport -ptoysport123 toysport_db < backup.sql
```

### 10. Problemas Comuns

**Porta 8089 já em uso:**
```yaml
# Edite docker-compose.yml e altere:
ports:
  - "8089:80"  # Mude 8089 para outra porta
```

**Erro de permissões:**
```bash
sudo chown -R $USER:$USER wordpress/
```

**Containers não iniciam:**
```bash
docker-compose down
docker-compose up -d --force-recreate
```

---

**Desenvolvido por CONEXT** | 2026
