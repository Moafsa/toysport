# 📋 Resumo Rápido: Como Atualizar Plugins WordPress

## 🎯 Métodos de Atualização

### 1️⃣ **Atualização Automática (Recomendado)**

Configure o sistema de atualização automática no seu plugin:

```php
// No arquivo principal do plugin, adicione:
Update URI: https://seusite.com/updates/meu-plugin
```

**Vantagens:**
- ✅ WordPress detecta automaticamente novas versões
- ✅ Usuário clica em "Atualizar" no painel admin
- ✅ Processo seguro e controlado
- ✅ Mantém dados e configurações

**Como funciona:**
1. Plugin verifica API de atualizações
2. WordPress mostra notificação de atualização disponível
3. Usuário clica em "Atualizar"
4. WordPress baixa e instala automaticamente
5. Hooks de atualização são executados

---

### 2️⃣ **Atualização Manual via ZIP**

**Passo a passo:**

1. **Criar ZIP da nova versão:**
   ```bash
   # Windows PowerShell
   .\scripts\atualizar-plugin.ps1 -PluginName "meu-plugin" -Version "1.2.4"
   
   # Linux/Mac
   ./scripts/criar-zip-plugin.sh meu-plugin 1.2.4
   ```

2. **Fazer backup no servidor:**
   - Via FTP: Renomeie a pasta do plugin para `meu-plugin-backup`
   - Via SSH: `cp -r meu-plugin meu-plugin-backup`

3. **Upload do ZIP:**
   - WordPress Admin > Plugins > Adicionar Novo > Enviar Plugin
   - WordPress detectará que o plugin existe e perguntará se deseja substituir
   - Clique em "Substituir atual"

4. **OU via WP-CLI:**
   ```bash
   wp plugin install meu-plugin-1.2.4.zip --force --activate
   ```

---

### 3️⃣ **Atualização via Git (Melhor para Desenvolvimento)**

**Configuração inicial:**

1. No servidor, inicialize Git no plugin:
   ```bash
   cd /var/www/html/wp-content/plugins/meu-plugin
   git init
   git remote add origin https://github.com/seu-usuario/meu-plugin.git
   ```

2. **Atualizar plugin:**
   ```bash
   # Via script
   ./scripts/atualizar-plugin.sh meu-plugin 1.2.4 usuario servidor.com
   
   # Ou manualmente
   ssh usuario@servidor.com
   cd /var/www/html/wp-content/plugins/meu-plugin
   git pull origin main
   wp plugin deactivate meu-plugin
   wp plugin activate meu-plugin
   ```

---

## 🔧 Estrutura Mínima do Plugin com Atualização

```php
<?php
/**
 * Plugin Name: Meu Plugin
 * Version: 1.2.3  ← SEMPRE ATUALIZE ESTA VERSÃO
 * Update URI: https://seusite.com/updates/meu-plugin
 */

define('MEU_PLUGIN_VERSION', '1.2.3'); // ← E ESTA TAMBÉM

class Meu_Plugin {
    public function __construct() {
        // Verifica atualizações
        add_action('plugins_loaded', array($this, 'check_version'));
    }
    
    public function check_version() {
        $installed = get_option('meu_plugin_version');
        if ($installed !== MEU_PLUGIN_VERSION) {
            $this->upgrade($installed);
        }
    }
    
    private function upgrade($old_version) {
        // Migrações específicas por versão
        if (version_compare($old_version, '1.1.0', '<')) {
            // Código de migração
        }
        
        // Atualiza versão
        update_option('meu_plugin_version', MEU_PLUGIN_VERSION);
    }
}
```

---

## ✅ Checklist de Atualização

Antes de atualizar:

- [ ] **Backup completo** (banco de dados + arquivos)
- [ ] **Teste em ambiente de desenvolvimento/staging**
- [ ] **Verifique compatibilidade** com WordPress e outros plugins
- [ ] **Documente mudanças** no CHANGELOG.md
- [ ] **Atualize número da versão** no cabeçalho do plugin
- [ ] **Teste hooks de atualização** (migração de dados)

Durante a atualização:

- [ ] **Desative plugin** temporariamente (se necessário)
- [ ] **Faça backup** da versão antiga
- [ ] **Substitua arquivos** ou instale nova versão
- [ ] **Verifique permissões** (644 para arquivos, 755 para pastas)
- [ ] **Reative o plugin**

Após a atualização:

- [ ] **Verifique se dados foram preservados**
- [ ] **Teste funcionalidades principais**
- [ ] **Limpe cache** (WordPress, navegador, CDN)
- [ ] **Verifique logs** de erro
- [ ] **Monitore por alguns dias**

---

## 🚨 Problemas Comuns e Soluções

### Erro: "Plugin não pode ser atualizado"

**Solução:**
- Verifique permissões de arquivos (644/755)
- Verifique se pasta do plugin não está protegida
- Tente desativar e reativar o plugin

### Dados perdidos após atualização

**Solução:**
- Sempre use hooks de atualização (`upgrade()`)
- Faça backup antes de atualizar
- Teste migrações em ambiente de desenvolvimento

### Plugin quebrado após atualização

**Solução:**
- Restaure backup imediatamente
- Verifique logs de erro do WordPress
- Revise código de migração
- Teste em ambiente de desenvolvimento primeiro

### Versão não atualiza no WordPress

**Solução:**
- Limpe cache de atualizações: `delete_transient('update_plugins')`
- Verifique se `Update URI` está correto no cabeçalho
- Verifique se API de atualizações está funcionando

---

## 📚 Arquivos de Referência

- **Guia Completo:** `GUIA_VERSIONAMENTO_PLUGINS.md`
- **Exemplo Prático:** `EXEMPLO_PLUGIN_ATUALIZACAO.php`
- **Scripts:** `scripts/atualizar-plugin.ps1` e `scripts/atualizar-plugin.sh`

---

## 💡 Dicas Importantes

1. **Sempre teste primeiro** em ambiente de desenvolvimento
2. **Use versionamento semântico:** MAJOR.MINOR.PATCH (ex: 1.2.3)
3. **Documente todas as mudanças** no CHANGELOG.md
4. **Mantenha backups** das últimas 3 versões
5. **Use Git** para controle de versão do código
6. **Implemente hooks de atualização** para migrar dados automaticamente
7. **Monitore logs** após cada atualização

---

## 🔗 Links Úteis

- [WordPress Plugin Handbook - Updates](https://developer.wordpress.org/plugins/plugin-basics/updating-a-plugin/)
- [Semantic Versioning](https://semver.org/)
- [WP-CLI Plugin Commands](https://wp-cli.org/commands/plugin/)

---

**Última atualização:** Janeiro 2026
