# 🔄 Guia Completo: Versionamento e Atualização de Plugins WordPress

## 📋 Índice

1. [Estrutura Básica de um Plugin com Versionamento](#estrutura-básica)
2. [Headers do Plugin (Cabeçalhos)](#headers-do-plugin)
3. [Sistema de Atualização Automática](#atualização-automática)
4. [Hooks de Atualização (Upgrade Hooks)](#hooks-de-atualização)
5. [Atualização Manual via FTP/SFTP](#atualização-manual)
6. [Atualização via ZIP](#atualização-via-zip)
7. [Migração de Dados entre Versões](#migração-de-dados)
8. [Boas Práticas](#boas-práticas)

---

## 🏗️ Estrutura Básica de um Plugin com Versionamento

### Estrutura de Diretórios Recomendada

```
meu-plugin/
├── meu-plugin.php          # Arquivo principal
├── readme.txt              # Para WordPress.org (opcional)
├── uninstall.php           # Script de desinstalação
├── includes/
│   ├── class-plugin-core.php
│   ├── class-database.php
│   └── class-updater.php   # Classe de atualização
├── admin/
│   └── class-admin.php
├── assets/
│   ├── css/
│   └── js/
└── languages/
    └── meu-plugin-pt_BR.po
```

---

## 📝 Headers do Plugin (Cabeçalhos)

### Arquivo Principal do Plugin (`meu-plugin.php`)

```php
<?php
/**
 * Plugin Name: Meu Plugin Customizado
 * Plugin URI: https://seusite.com/meu-plugin
 * Description: Descrição do seu plugin
 * Version: 1.2.3
 * Author: Seu Nome
 * Author URI: https://seusite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: meu-plugin
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Update URI: https://seusite.com/updates/meu-plugin
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Define constantes do plugin
define('MEU_PLUGIN_VERSION', '1.2.3');
define('MEU_PLUGIN_FILE', __FILE__);
define('MEU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MEU_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MEU_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Classe principal do plugin
class Meu_Plugin {
    
    private static $instance = null;
    private $version;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->version = MEU_PLUGIN_VERSION;
        $this->init();
    }
    
    private function init() {
        // Verifica atualizações na ativação
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Hook de atualização
        add_action('plugins_loaded', array($this, 'check_version'));
        
        // Carrega arquivos necessários
        $this->load_dependencies();
    }
    
    private function load_dependencies() {
        require_once MEU_PLUGIN_DIR . 'includes/class-updater.php';
        require_once MEU_PLUGIN_DIR . 'includes/class-plugin-core.php';
    }
    
    /**
     * Ativação do plugin
     */
    public function activate() {
        // Salva versão atual
        add_option('meu_plugin_version', MEU_PLUGIN_VERSION);
        
        // Cria tabelas do banco de dados
        $this->create_tables();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Verifica se precisa atualizar
     */
    public function check_version() {
        $installed_version = get_option('meu_plugin_version');
        
        if ($installed_version !== MEU_PLUGIN_VERSION) {
            $this->upgrade($installed_version);
        }
    }
    
    /**
     * Processa atualizações
     */
    private function upgrade($old_version) {
        // Versão 1.0.0 -> 1.1.0
        if (version_compare($old_version, '1.1.0', '<')) {
            $this->upgrade_to_1_1_0();
        }
        
        // Versão 1.1.0 -> 1.2.0
        if (version_compare($old_version, '1.2.0', '<')) {
            $this->upgrade_to_1_2_0();
        }
        
        // Versão 1.2.0 -> 1.2.3
        if (version_compare($old_version, '1.2.3', '<')) {
            $this->upgrade_to_1_2_3();
        }
        
        // Atualiza versão no banco
        update_option('meu_plugin_version', MEU_PLUGIN_VERSION);
        
        // Log da atualização
        $this->log_upgrade($old_version, MEU_PLUGIN_VERSION);
    }
    
    /**
     * Upgrade para versão 1.1.0
     */
    private function upgrade_to_1_1_0() {
        // Exemplo: Adicionar nova coluna na tabela
        global $wpdb;
        $table_name = $wpdb->prefix . 'meu_plugin_tabela';
        
        $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN nova_coluna VARCHAR(255) DEFAULT ''");
        
        // Migrar dados antigos
        $this->migrate_data_to_1_1_0();
    }
    
    /**
     * Upgrade para versão 1.2.0
     */
    private function upgrade_to_1_2_0() {
        // Exemplo: Criar nova tabela
        $this->create_new_table();
        
        // Migrar configurações antigas
        $old_settings = get_option('meu_plugin_settings_old');
        if ($old_settings) {
            $new_settings = $this->convert_settings_format($old_settings);
            update_option('meu_plugin_settings', $new_settings);
            delete_option('meu_plugin_settings_old');
        }
    }
    
    /**
     * Upgrade para versão 1.2.3
     */
    private function upgrade_to_1_2_3() {
        // Correções de bugs, sem mudanças estruturais
        // Limpar cache, atualizar transients, etc.
        delete_transient('meu_plugin_cache');
    }
    
    /**
     * Cria tabelas do banco de dados
     */
    private function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'meu_plugin_tabela';
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            nome varchar(255) NOT NULL,
            valor text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Cria nova tabela (exemplo para upgrade)
     */
    private function create_new_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'meu_plugin_nova_tabela';
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            relacionado_id bigint(20) NOT NULL,
            dados text,
            PRIMARY KEY (id),
            KEY relacionado_id (relacionado_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Migra dados para nova versão
     */
    private function migrate_data_to_1_1_0() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'meu_plugin_tabela';
        
        // Exemplo: Preencher nova coluna com valores padrão
        $wpdb->query("UPDATE {$table_name} SET nova_coluna = 'padrao' WHERE nova_coluna IS NULL OR nova_coluna = ''");
    }
    
    /**
     * Converte formato de configurações antigas para novo
     */
    private function convert_settings_format($old_settings) {
        $new_settings = array();
        
        // Converter formato antigo para novo
        if (isset($old_settings['campo_antigo'])) {
            $new_settings['campo_novo'] = $old_settings['campo_antigo'];
        }
        
        return $new_settings;
    }
    
    /**
     * Registra log de atualização
     */
    private function log_upgrade($old_version, $new_version) {
        $log = get_option('meu_plugin_upgrade_log', array());
        $log[] = array(
            'from' => $old_version,
            'to' => $new_version,
            'date' => current_time('mysql')
        );
        
        // Manter apenas últimos 10 logs
        $log = array_slice($log, -10);
        update_option('meu_plugin_upgrade_log', $log);
    }
}

// Inicializa o plugin
Meu_Plugin::get_instance();
```

---

## 🔄 Sistema de Atualização Automática

### Classe de Atualização (`includes/class-updater.php`)

```php
<?php
/**
 * Classe para atualizações automáticas do plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Meu_Plugin_Updater {
    
    private $api_url = 'https://seusite.com/wp-json/meu-plugin/v1/updates';
    private $plugin_slug = 'meu-plugin';
    private $plugin_file;
    
    public function __construct($plugin_file) {
        $this->plugin_file = $plugin_file;
        $this->init();
    }
    
    private function init() {
        // Hook para verificar atualizações
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        
        // Hook para informações do plugin
        add_filter('plugins_api', array($this, 'plugin_info'), 10, 3);
        
        // Hook após atualização
        add_action('upgrader_process_complete', array($this, 'after_update'), 10, 2);
    }
    
    /**
     * Verifica se há atualizações disponíveis
     */
    public function check_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        $plugin_info = $this->get_remote_version();
        
        if ($plugin_info && version_compare(MEU_PLUGIN_VERSION, $plugin_info->version, '<')) {
            $obj = new stdClass();
            $obj->slug = $this->plugin_slug;
            $obj->new_version = $plugin_info->version;
            $obj->url = $plugin_info->homepage;
            $obj->package = $plugin_info->download_url;
            $obj->plugin = $this->plugin_file;
            
            $transient->response[$this->plugin_file] = $obj;
        }
        
        return $transient;
    }
    
    /**
     * Busca informações da versão remota
     */
    private function get_remote_version() {
        $cache_key = 'meu_plugin_remote_version';
        $version_info = get_transient($cache_key);
        
        if (false === $version_info) {
            $response = wp_remote_get($this->api_url, array(
                'timeout' => 10,
                'headers' => array(
                    'Accept' => 'application/json'
                )
            ));
            
            if (is_wp_error($response)) {
                return false;
            }
            
            $body = wp_remote_retrieve_body($response);
            $version_info = json_decode($body);
            
            if ($version_info) {
                set_transient($cache_key, $version_info, 12 * HOUR_IN_SECONDS);
            }
        }
        
        return $version_info;
    }
    
    /**
     * Retorna informações do plugin para tela de detalhes
     */
    public function plugin_info($false, $action, $args) {
        if ($args->slug !== $this->plugin_slug) {
            return $false;
        }
        
        $plugin_info = $this->get_remote_version();
        
        if (!$plugin_info) {
            return $false;
        }
        
        $args = new stdClass();
        $args->name = 'Meu Plugin Customizado';
        $args->slug = $this->plugin_slug;
        $args->version = $plugin_info->version;
        $args->author = 'Seu Nome';
        $args->homepage = $plugin_info->homepage;
        $args->download_link = $plugin_info->download_url;
        $args->sections = array(
            'description' => $plugin_info->description,
            'changelog' => $plugin_info->changelog
        );
        
        return $args;
    }
    
    /**
     * Ações após atualização
     */
    public function after_update($upgrader_object, $options) {
        if ($options['action'] === 'update' && $options['type'] === 'plugin') {
            if (isset($options['plugins']) && in_array($this->plugin_file, $options['plugins'])) {
                // Limpar cache
                delete_transient('meu_plugin_remote_version');
                
                // Executar hooks de atualização
                do_action('meu_plugin_updated', MEU_PLUGIN_VERSION);
            }
        }
    }
}

// Inicializa o updater
new Meu_Plugin_Updater(MEU_PLUGIN_BASENAME);
```

### API REST para Atualizações (no servidor)

```php
<?php
/**
 * Endpoint REST para atualizações
 * Adicione isso no seu tema functions.php ou em um plugin de API
 */

add_action('rest_api_init', function() {
    register_rest_route('meu-plugin/v1', '/updates', array(
        'methods' => 'GET',
        'callback' => 'meu_plugin_get_update_info',
        'permission_callback' => '__return_true'
    ));
});

function meu_plugin_get_update_info() {
    return array(
        'version' => '1.2.4', // Nova versão disponível
        'homepage' => 'https://seusite.com/meu-plugin',
        'download_url' => 'https://seusite.com/downloads/meu-plugin-1.2.4.zip',
        'description' => 'Descrição da nova versão',
        'changelog' => '
            <h4>Versão 1.2.4</h4>
            <ul>
                <li>Correção de bugs</li>
                <li>Melhorias de performance</li>
                <li>Novas funcionalidades</li>
            </ul>
        '
    );
}
```

---

## 📤 Atualização Manual via FTP/SFTP

### Método 1: Substituição de Arquivos (Recomendado)

1. **Backup primeiro!**
   ```bash
   # Backup do plugin atual
   cp -r wp-content/plugins/meu-plugin wp-content/plugins/meu-plugin-backup
   ```

2. **Upload dos novos arquivos**
   - Conecte via FTP/SFTP
   - Navegue até `wp-content/plugins/meu-plugin/`
   - Faça upload dos novos arquivos (substitua os antigos)
   - **NÃO delete a pasta**, apenas substitua os arquivos

3. **Verificar permissões**
   - Arquivos: 644
   - Pastas: 755

4. **Limpar cache**
   - Limpe cache do WordPress (se usar plugin de cache)
   - Limpe cache do navegador

### Método 2: Via WP-CLI (Recomendado para servidores)

```bash
# Conectar ao servidor via SSH
ssh usuario@seusite.com

# Navegar até o diretório do WordPress
cd /var/www/html

# Fazer backup
wp plugin get meu-plugin --format=json > meu-plugin-backup.json
cp -r wp-content/plugins/meu-plugin wp-content/plugins/meu-plugin-backup

# Desativar plugin temporariamente
wp plugin deactivate meu-plugin

# Remover plugin antigo
rm -rf wp-content/plugins/meu-plugin

# Upload da nova versão (via SCP ou git)
# scp -r novo-plugin usuario@seusite.com:/var/www/html/wp-content/plugins/meu-plugin

# Ativar plugin
wp plugin activate meu-plugin

# Verificar versão
wp plugin list | grep meu-plugin
```

---

## 📦 Atualização via ZIP

### Criando ZIP para Atualização

```bash
# No diretório do plugin
cd meu-plugin
zip -r ../meu-plugin-1.2.4.zip . -x "*.git*" -x "*.DS_Store" -x "node_modules/*"
```

### Atualizando via Painel WordPress

1. **Via Painel Admin:**
   - Vá em **Plugins > Adicionar Novo > Enviar Plugin**
   - Selecione o arquivo ZIP
   - Clique em **Instalar Agora**
   - WordPress detectará que o plugin já existe e perguntará se deseja substituir
   - Clique em **Substituir atual**

2. **Via WP-CLI:**
   ```bash
   wp plugin install meu-plugin-1.2.4.zip --force --activate
   ```

### Script PHP para Atualização Automática via ZIP

```php
<?php
/**
 * Script para atualizar plugin via ZIP
 * Execute via WP-CLI: wp eval-file update-plugin.php
 */

$plugin_zip = '/caminho/para/meu-plugin-1.2.4.zip';
$plugin_slug = 'meu-plugin/meu-plugin.php';

// Verificar se plugin está ativo
if (!is_plugin_active($plugin_zip)) {
    echo "Plugin não está ativo. Ativando...\n";
    activate_plugin($plugin_slug);
}

// Desativar plugin
deactivate_plugins($plugin_slug);
echo "Plugin desativado.\n";

// Instalar nova versão
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

$upgrader = new Plugin_Upgrader();
$result = $upgrader->install($plugin_zip, array('overwrite_package' => true));

if ($result) {
    echo "Plugin atualizado com sucesso!\n";
    
    // Reativar plugin
    activate_plugin($plugin_slug);
    echo "Plugin reativado.\n";
} else {
    echo "Erro ao atualizar plugin.\n";
}
```

---

## 🔄 Migração de Dados entre Versões

### Exemplo Completo de Migração

```php
<?php
/**
 * Sistema de migração de dados
 */

class Meu_Plugin_Migrator {
    
    /**
     * Executa todas as migrações necessárias
     */
    public static function migrate($from_version, $to_version) {
        $migrations = self::get_migrations($from_version, $to_version);
        
        foreach ($migrations as $migration) {
            try {
                call_user_func(array(__CLASS__, $migration));
                self::log_migration($migration, 'success');
            } catch (Exception $e) {
                self::log_migration($migration, 'error', $e->getMessage());
                throw $e;
            }
        }
    }
    
    /**
     * Retorna lista de migrações a executar
     */
    private static function get_migrations($from, $to) {
        $all_migrations = array(
            '1.0.0' => array('migrate_1_0_0_to_1_1_0'),
            '1.1.0' => array('migrate_1_1_0_to_1_2_0'),
            '1.2.0' => array('migrate_1_2_0_to_1_2_3'),
        );
        
        $migrations_to_run = array();
        
        foreach ($all_migrations as $version => $migrations) {
            if (version_compare($from, $version, '<') && version_compare($to, $version, '>=')) {
                $migrations_to_run = array_merge($migrations_to_run, $migrations);
            }
        }
        
        return $migrations_to_run;
    }
    
    /**
     * Migração 1.0.0 -> 1.1.0
     */
    private static function migrate_1_0_0_to_1_1_0() {
        global $wpdb;
        
        // Exemplo: Adicionar coluna
        $table = $wpdb->prefix . 'meu_plugin_tabela';
        $wpdb->query("ALTER TABLE {$table} ADD COLUMN nova_coluna VARCHAR(255)");
        
        // Migrar dados
        $items = $wpdb->get_results("SELECT * FROM {$table}");
        foreach ($items as $item) {
            // Converter dados antigos para novo formato
            $new_value = self::convert_old_data($item->valor_antigo);
            $wpdb->update($table, array('nova_coluna' => $new_value), array('id' => $item->id));
        }
    }
    
    /**
     * Migração 1.1.0 -> 1.2.0
     */
    private static function migrate_1_1_0_to_1_2_0() {
        // Criar nova tabela
        self::create_new_table();
        
        // Migrar configurações
        $old_settings = get_option('meu_plugin_settings');
        $new_settings = self::convert_settings($old_settings);
        update_option('meu_plugin_settings_v2', $new_settings);
    }
    
    /**
     * Migração 1.2.0 -> 1.2.3
     */
    private static function migrate_1_2_0_to_1_2_3() {
        // Limpar dados obsoletos
        delete_option('meu_plugin_old_option');
        delete_transient('meu_plugin_old_cache');
        
        // Atualizar estrutura de dados
        self::update_data_structure();
    }
    
    /**
     * Log de migrações
     */
    private static function log_migration($migration, $status, $message = '') {
        $log = get_option('meu_plugin_migration_log', array());
        $log[] = array(
            'migration' => $migration,
            'status' => $status,
            'message' => $message,
            'date' => current_time('mysql')
        );
        update_option('meu_plugin_migration_log', $log);
    }
}
```

---

## ✅ Boas Práticas

### 1. Versionamento Semântico

Use [Semantic Versioning](https://semver.org/):
- **MAJOR** (1.0.0): Mudanças incompatíveis
- **MINOR** (0.1.0): Novas funcionalidades compatíveis
- **PATCH** (0.0.1): Correções de bugs compatíveis

### 2. Sempre Faça Backup

```php
// Antes de atualizar, criar backup
function meu_plugin_backup_before_update() {
    $backup = array(
        'version' => get_option('meu_plugin_version'),
        'settings' => get_option('meu_plugin_settings'),
        'data' => meu_plugin_export_data(),
        'date' => current_time('mysql')
    );
    
    update_option('meu_plugin_backup_' . time(), $backup);
}
```

### 3. Teste em Ambiente de Staging

Sempre teste atualizações em ambiente de desenvolvimento/staging antes de produção.

### 4. Documente Mudanças

Mantenha um arquivo `CHANGELOG.md`:

```markdown
# Changelog

## [1.2.3] - 2024-01-20
### Fixed
- Correção de bug na função X
- Melhoria de performance

## [1.2.0] - 2024-01-15
### Added
- Nova funcionalidade Y
- Suporte para API Z

### Changed
- Migração de dados automática
```

### 5. Use Hooks de Atualização

```php
// Hook antes da atualização
do_action('meu_plugin_before_update', $old_version, $new_version);

// Hook após atualização
do_action('meu_plugin_after_update', $old_version, $new_version);
```

### 6. Validação de Dados

Sempre valide dados após migração:

```php
function validate_migration() {
    global $wpdb;
    
    // Verificar se tabela existe
    $table = $wpdb->prefix . 'meu_plugin_tabela';
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");
    
    if (!$exists) {
        throw new Exception('Tabela não existe após migração!');
    }
    
    // Verificar integridade dos dados
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
    if ($count === 0 && $this->should_have_data()) {
        throw new Exception('Dados perdidos na migração!');
    }
}
```

---

## 🚀 Workflow Recomendado

### Desenvolvimento Local

1. Desenvolva e teste localmente
2. Commit no Git com mensagem descritiva
3. Crie tag de versão: `git tag -a v1.2.4 -m "Release 1.2.4"`
4. Push: `git push origin v1.2.4`

### Deploy em Produção

1. **Opção A - Atualização Automática:**
   - Configure API de atualizações
   - WordPress detectará automaticamente
   - Usuário clica em "Atualizar" no painel

2. **Opção B - Atualização Manual:**
   - Crie ZIP da nova versão
   - Faça backup do plugin atual
   - Upload via FTP ou painel WordPress
   - Execute hooks de atualização

3. **Opção C - Via Git (Recomendado):**
   ```bash
   ssh usuario@servidor
   cd /var/www/html/wp-content/plugins/meu-plugin
   git pull origin main
   wp plugin deactivate meu-plugin
   wp plugin activate meu-plugin
   ```

---

## 📚 Recursos Adicionais

- [WordPress Plugin Handbook - Updates](https://developer.wordpress.org/plugins/plugin-basics/updating-a-plugin/)
- [Semantic Versioning](https://semver.org/)
- [WP-CLI Plugin Commands](https://wp-cli.org/commands/plugin/)

---

**Última atualização:** Janeiro 2026
