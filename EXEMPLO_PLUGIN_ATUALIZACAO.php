<?php
/**
 * EXEMPLO PRÁTICO: Plugin com Sistema de Atualização
 * 
 * Este é um exemplo completo de como implementar versionamento
 * e atualização em um plugin WordPress customizado.
 * 
 * COMO USAR:
 * 1. Copie este arquivo para wp-content/plugins/meu-plugin-exemplo/meu-plugin-exemplo.php
 * 2. Ajuste as constantes e nomes conforme seu plugin
 * 3. Crie a estrutura de pastas conforme o guia
 */

/**
 * Plugin Name: Exemplo Plugin com Atualização
 * Plugin URI: https://seusite.com/exemplo-plugin
 * Description: Exemplo completo de plugin com sistema de versionamento e atualização
 * Version: 1.0.0
 * Author: Seu Nome
 * Author URI: https://seusite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: exemplo-plugin
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Update URI: https://seusite.com/updates/exemplo-plugin
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// ============================================
// CONSTANTES DO PLUGIN
// ============================================

define('EXEMPLO_PLUGIN_VERSION', '1.0.0');
define('EXEMPLO_PLUGIN_FILE', __FILE__);
define('EXEMPLO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EXEMPLO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EXEMPLO_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('EXEMPLO_PLUGIN_SLUG', 'exemplo-plugin');

// ============================================
// CLASSE PRINCIPAL DO PLUGIN
// ============================================

class Exemplo_Plugin {
    
    private static $instance = null;
    private $version;
    
    /**
     * Singleton pattern
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Construtor privado
     */
    private function __construct() {
        $this->version = EXEMPLO_PLUGIN_VERSION;
        $this->init();
    }
    
    /**
     * Inicializa o plugin
     */
    private function init() {
        // Hook de ativação
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Hook de desativação
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Hook de desinstalação (quando plugin é deletado)
        register_uninstall_hook(__FILE__, array('Exemplo_Plugin', 'uninstall'));
        
        // Verifica versão e atualiza se necessário
        add_action('plugins_loaded', array($this, 'check_version'), 5);
        
        // Carrega dependências
        add_action('plugins_loaded', array($this, 'load_dependencies'), 10);
        
        // Inicializa funcionalidades
        add_action('init', array($this, 'init_features'));
    }
    
    /**
     * Carrega arquivos de dependências
     */
    public function load_dependencies() {
        // Carrega classe de atualização se existir
        $updater_file = EXEMPLO_PLUGIN_DIR . 'includes/class-updater.php';
        if (file_exists($updater_file)) {
            require_once $updater_file;
        }
        
        // Carrega outras classes necessárias
        $core_file = EXEMPLO_PLUGIN_DIR . 'includes/class-core.php';
        if (file_exists($core_file)) {
            require_once $core_file;
        }
    }
    
    /**
     * Inicializa funcionalidades do plugin
     */
    public function init_features() {
        // Adicione aqui suas funcionalidades
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    /**
     * Ativação do plugin
     */
    public function activate() {
        // Salva versão atual
        $current_version = get_option('exemplo_plugin_version');
        
        if (!$current_version) {
            // Primeira instalação
            add_option('exemplo_plugin_version', EXEMPLO_PLUGIN_VERSION);
            $this->create_tables();
            $this->set_default_options();
        } else {
            // Plugin já existe, apenas atualiza versão se necessário
            if (version_compare($current_version, EXEMPLO_PLUGIN_VERSION, '<')) {
                $this->upgrade($current_version);
            }
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Log de ativação
        $this->log_event('activated', EXEMPLO_PLUGIN_VERSION);
    }
    
    /**
     * Desativação do plugin
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Log de desativação
        $this->log_event('deactivated', EXEMPLO_PLUGIN_VERSION);
    }
    
    /**
     * Desinstalação do plugin (quando deletado)
     */
    public static function uninstall() {
        // Opcional: Remover dados do banco
        // global $wpdb;
        // $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}exemplo_plugin_tabela");
        
        // Remover opções
        // delete_option('exemplo_plugin_version');
        // delete_option('exemplo_plugin_settings');
        
        // Ou manter dados para caso o plugin seja reinstalado
    }
    
    /**
     * Verifica se precisa atualizar
     */
    public function check_version() {
        $installed_version = get_option('exemplo_plugin_version');
        
        if (!$installed_version) {
            // Plugin não foi instalado corretamente
            $this->activate();
            return;
        }
        
        if ($installed_version !== EXEMPLO_PLUGIN_VERSION) {
            $this->upgrade($installed_version);
        }
    }
    
    /**
     * Processa atualizações
     */
    private function upgrade($old_version) {
        // Hook antes da atualização
        do_action('exemplo_plugin_before_upgrade', $old_version, EXEMPLO_PLUGIN_VERSION);
        
        // Executa migrações específicas por versão
        $this->run_migrations($old_version);
        
        // Atualiza versão no banco
        update_option('exemplo_plugin_version', EXEMPLO_PLUGIN_VERSION);
        
        // Log da atualização
        $this->log_upgrade($old_version, EXEMPLO_PLUGIN_VERSION);
        
        // Hook após atualização
        do_action('exemplo_plugin_after_upgrade', $old_version, EXEMPLO_PLUGIN_VERSION);
        
        // Notificação de sucesso (opcional)
        set_transient('exemplo_plugin_upgraded', true, 30);
    }
    
    /**
     * Executa migrações específicas por versão
     */
    private function run_migrations($old_version) {
        // Exemplo: Migração para versão 1.1.0
        if (version_compare($old_version, '1.1.0', '<')) {
            $this->migrate_to_1_1_0();
        }
        
        // Exemplo: Migração para versão 1.2.0
        if (version_compare($old_version, '1.2.0', '<')) {
            $this->migrate_to_1_2_0();
        }
        
        // Adicione mais migrações conforme necessário
    }
    
    /**
     * Migração para versão 1.1.0
     */
    private function migrate_to_1_1_0() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'exemplo_plugin_tabela';
        
        // Verifica se tabela existe
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
        
        if ($table_exists) {
            // Adiciona nova coluna se não existir
            $column_exists = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE 'nova_coluna'");
            if (empty($column_exists)) {
                $wpdb->query("ALTER TABLE $table_name ADD COLUMN nova_coluna VARCHAR(255) DEFAULT ''");
            }
        }
        
        // Migra dados antigos se necessário
        // $this->migrate_old_data();
    }
    
    /**
     * Migração para versão 1.2.0
     */
    private function migrate_to_1_2_0() {
        // Criar nova tabela
        $this->create_new_table();
        
        // Migrar configurações antigas para novo formato
        $old_settings = get_option('exemplo_plugin_settings');
        if ($old_settings) {
            $new_settings = $this->convert_settings_format($old_settings);
            update_option('exemplo_plugin_settings_v2', $new_settings);
            // Manter backup por segurança
            update_option('exemplo_plugin_settings_backup', $old_settings);
        }
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
     * Cria tabelas do banco de dados
     */
    private function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'exemplo_plugin_tabela';
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            nome varchar(255) NOT NULL,
            valor text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY nome (nome)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Cria nova tabela (para upgrades)
     */
    private function create_new_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'exemplo_plugin_nova_tabela';
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            relacionado_id bigint(20) NOT NULL,
            dados text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY relacionado_id (relacionado_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Define opções padrão
     */
    private function set_default_options() {
        $default_settings = array(
            'campo1' => 'valor_padrao',
            'campo2' => true,
            'campo3' => 100
        );
        
        add_option('exemplo_plugin_settings', $default_settings);
    }
    
    /**
     * Adiciona menu no admin
     */
    public function add_admin_menu() {
        add_options_page(
            'Exemplo Plugin',
            'Exemplo Plugin',
            'manage_options',
            'exemplo-plugin',
            array($this, 'render_admin_page')
        );
    }
    
    /**
     * Registra configurações
     */
    public function register_settings() {
        register_setting('exemplo_plugin_settings', 'exemplo_plugin_settings');
    }
    
    /**
     * Renderiza página de admin
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1>Exemplo Plugin</h1>
            <p>Versão atual: <?php echo EXEMPLO_PLUGIN_VERSION; ?></p>
            <p>Versão instalada: <?php echo get_option('exemplo_plugin_version'); ?></p>
            
            <?php
            // Mostra notificação se foi atualizado recentemente
            if (get_transient('exemplo_plugin_upgraded')) {
                echo '<div class="notice notice-success"><p>Plugin atualizado com sucesso!</p></div>';
                delete_transient('exemplo_plugin_upgraded');
            }
            ?>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('exemplo_plugin_settings');
                $settings = get_option('exemplo_plugin_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th><label>Campo 1</label></th>
                        <td><input type="text" name="exemplo_plugin_settings[campo1]" value="<?php echo esc_attr($settings['campo1']); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Registra log de eventos
     */
    private function log_event($event, $version) {
        $log = get_option('exemplo_plugin_event_log', array());
        $log[] = array(
            'event' => $event,
            'version' => $version,
            'date' => current_time('mysql'),
            'user' => get_current_user_id()
        );
        
        // Manter apenas últimos 50 eventos
        $log = array_slice($log, -50);
        update_option('exemplo_plugin_event_log', $log);
    }
    
    /**
     * Registra log de atualizações
     */
    private function log_upgrade($old_version, $new_version) {
        $log = get_option('exemplo_plugin_upgrade_log', array());
        $log[] = array(
            'from' => $old_version,
            'to' => $new_version,
            'date' => current_time('mysql'),
            'user' => get_current_user_id()
        );
        
        // Manter apenas últimos 10 logs
        $log = array_slice($log, -10);
        update_option('exemplo_plugin_upgrade_log', $log);
        
        // Também registra como evento
        $this->log_event('upgraded', "$old_version -> $new_version");
    }
}

// ============================================
// INICIALIZAÇÃO DO PLUGIN
// ============================================

// Inicializa o plugin
Exemplo_Plugin::get_instance();

// ============================================
// FUNÇÕES AUXILIARES (opcional)
// ============================================

/**
 * Função helper para obter versão do plugin
 */
function exemplo_plugin_get_version() {
    return EXEMPLO_PLUGIN_VERSION;
}

/**
 * Função helper para verificar se plugin está atualizado
 */
function exemplo_plugin_is_updated() {
    $installed = get_option('exemplo_plugin_version');
    return $installed === EXEMPLO_PLUGIN_VERSION;
}

/**
 * Função helper para obter configurações
 */
function exemplo_plugin_get_settings() {
    return get_option('exemplo_plugin_settings', array());
}
