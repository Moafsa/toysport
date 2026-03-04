<?php
/**
 * Admin class
 *
 * @package TS_ML_Integration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin class
 */
class TS_ML_Admin
{

    /**
     * Instance
     *
     * @var TS_ML_Admin
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return TS_ML_Admin
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu()
    {
        add_menu_page(
            __('Mercado Livre', 'ts-ml-integration'),
            __('Mercado Livre', 'ts-ml-integration'),
            'manage_woocommerce',
            'ts-ml-settings',
            array($this, 'render_settings_page'),
            'dashicons-admin-generic',
            56
        );

        add_submenu_page(
            'ts-ml-settings',
            __('Configurações', 'ts-ml-integration'),
            __('Configurações', 'ts-ml-integration'),
            'manage_woocommerce',
            'ts-ml-settings',
            array($this, 'render_settings_page')
        );

        add_submenu_page(
            'ts-ml-settings',
            __('Mapear Categorias', 'ts-ml-integration'),
            __('Mapear Categorias', 'ts-ml-integration'),
            'manage_woocommerce',
            'ts-ml-category-mapping',
            array($this, 'render_category_mapping_page')
        );

        add_submenu_page(
            'ts-ml-settings',
            __('Mapear Atributos', 'ts-ml-integration'),
            __('Mapear Atributos', 'ts-ml-integration'),
            'manage_woocommerce',
            'ts-ml-attribute-mapping',
            array($this, 'render_attribute_mapping_page')
        );

        add_submenu_page(
            'ts-ml-settings',
            __('Produtos', 'ts-ml-integration'),
            __('Produtos', 'ts-ml-integration'),
            'manage_woocommerce',
            'ts-ml-products',
            array($this, 'render_products_page')
        );

        add_submenu_page(
            'ts-ml-settings',
            __('Pedidos', 'ts-ml-integration'),
            __('Pedidos', 'ts-ml-integration'),
            'manage_woocommerce',
            'ts-ml-orders',
            array($this, 'render_orders_page')
        );

        add_submenu_page(
            'ts-ml-settings',
            __('Mensagens', 'ts-ml-integration'),
            __('Mensagens', 'ts-ml-integration'),
            'manage_woocommerce',
            'ts-ml-messages',
            array($this, 'render_messages_page')
        );

        add_submenu_page(
            'ts-ml-settings',
            __('Relatórios', 'ts-ml-integration'),
            __('Relatórios', 'ts-ml-integration'),
            'manage_woocommerce',
            'ts-ml-reports',
            array($this, 'render_reports_page')
        );

        add_submenu_page(
            'ts-ml-settings',
            __('Importar do ML', 'ts-ml-integration'),
            __('Importar do ML', 'ts-ml-integration'),
            'manage_woocommerce',
            'ts-ml-import',
            array($this, 'render_import_page')
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook)
    {
        if (strpos($hook, 'ts-ml') === false) {
            return;
        }

        wp_enqueue_style(
            'ts-ml-admin',
            TS_ML_PLUGIN_URL . 'admin/assets/css/admin.css',
            array(),
            TS_ML_VERSION
        );

        wp_enqueue_script(
            'ts-ml-admin',
            TS_ML_PLUGIN_URL . 'admin/assets/js/admin.js',
            array('jquery'),
            TS_ML_VERSION,
            true
        );
    }

    /**
     * Render settings page
     */
    public function render_settings_page()
    {
        // Check if file exists before including
        $settings_file = TS_ML_PLUGIN_DIR . 'admin/views/settings.php';
        if (!file_exists($settings_file)) {
            wp_die(__('Arquivo de configurações não encontrado.', 'ts-ml-integration'));
        }

        // Include with error handling
        try {
            include $settings_file;
        } catch (Exception $e) {
            ?>
            <div class="wrap">
                <h1>
                    <?php esc_html_e('Configurações - Mercado Livre Integration', 'ts-ml-integration'); ?>
                </h1>
                <div class="error">
                    <p><strong>
                            <?php esc_html_e('Erro:', 'ts-ml-integration'); ?>
                        </strong>
                        <?php esc_html_e('Ocorreu um erro ao carregar a página de configurações.', 'ts-ml-integration'); ?>
                    </p>
                    <?php if (defined('WP_DEBUG') && WP_DEBUG) { ?>
                        <p>
                            <?php echo esc_html($e->getMessage()); ?>
                        </p>
                    <?php } ?>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Render products page
     */
    public function render_products_page()
    {
        include TS_ML_PLUGIN_DIR . 'admin/views/products.php';
    }

    /**
     * Render orders page
     */
    public function render_orders_page()
    {
        include TS_ML_PLUGIN_DIR . 'admin/views/orders.php';
    }

    /**
     * Render messages page
     */
    public function render_messages_page()
    {
        include TS_ML_PLUGIN_DIR . 'admin/views/messages.php';
    }

    /**
     * Render reports page
     */
    public function render_reports_page()
    {
        include TS_ML_PLUGIN_DIR . 'admin/views/reports.php';
    }

    /**
     * Render category mapping page
     */
    public function render_category_mapping_page()
    {
        include TS_ML_PLUGIN_DIR . 'admin/views/category-mapping.php';
    }

    /**
     * Render attribute mapping page
     */
    public function render_attribute_mapping_page()
    {
        include TS_ML_PLUGIN_DIR . 'admin/views/attribute-mapping.php';
    }
}
