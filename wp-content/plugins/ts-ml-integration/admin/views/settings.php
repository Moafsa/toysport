<?php
/**
 * Settings page
 *
 * @package TS_ML_Integration
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php esc_html_e('Configurações - Mercado Livre Integration', 'ts-ml-integration'); ?></h1>
    
    <div class="ts-ml-settings">
        <h2><?php esc_html_e('Contas do Mercado Livre', 'ts-ml-integration'); ?></h2>
        
        <p><?php esc_html_e('Configure suas contas do Mercado Livre para começar a sincronizar produtos, pedidos e mensagens.', 'ts-ml-integration'); ?></p>
        
        <div class="ts-ml-accounts-list">
            <?php
            global $wpdb;
            $table_accounts = $wpdb->prefix . 'ts_ml_accounts';
            $accounts = $wpdb->get_results("SELECT * FROM $table_accounts ORDER BY created_at DESC");
            
            if (empty($accounts)) {
                ?>
                <p><?php esc_html_e('Nenhuma conta configurada. Adicione uma conta para começar.', 'ts-ml-integration'); ?></p>
                <?php
            } else {
                foreach ($accounts as $account) {
                    ?>
                    <div class="ts-ml-account-card">
                        <h3><?php echo esc_html($account->account_name); ?></h3>
                        <p><strong><?php esc_html_e('País:', 'ts-ml-integration'); ?></strong> <?php echo esc_html($account->country); ?></p>
                        <p><strong><?php esc_html_e('Status:', 'ts-ml-integration'); ?></strong> 
                            <?php echo $account->is_active ? esc_html__('Ativa', 'ts-ml-integration') : esc_html__('Inativa', 'ts-ml-integration'); ?>
                        </p>
                        <?php if (empty($account->access_token)) { ?>
                            <a href="<?php echo esc_url(TS_ML_API_Handler::instance()->get_oauth_url($account->id, $account->country)); ?>" class="button button-primary">
                                <?php esc_html_e('Conectar Conta', 'ts-ml-integration'); ?>
                            </a>
                        <?php } else { ?>
                            <p><strong><?php esc_html_e('Conectada', 'ts-ml-integration'); ?></strong></p>
                        <?php } ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <h2><?php esc_html_e('Adicionar Nova Conta', 'ts-ml-integration'); ?></h2>
        <form method="post" action="">
            <?php wp_nonce_field('ts_ml_add_account'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="account_name"><?php esc_html_e('Nome da Conta', 'ts-ml-integration'); ?></label></th>
                    <td><input type="text" name="account_name" id="account_name" class="regular-text" required /></td>
                </tr>
                <tr>
                    <th><label for="country"><?php esc_html_e('País', 'ts-ml-integration'); ?></label></th>
                    <td>
                        <select name="country" id="country">
                            <option value="BR">Brasil</option>
                            <option value="AR">Argentina</option>
                            <option value="MX">México</option>
                            <option value="CL">Chile</option>
                            <option value="CO">Colômbia</option>
                        </select>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="add_account" class="button button-primary" value="<?php esc_attr_e('Adicionar Conta', 'ts-ml-integration'); ?>" />
            </p>
        </form>
        
        <?php
        // Handle form submission
        if (isset($_POST['add_account']) && check_admin_referer('ts_ml_add_account')) {
            global $wpdb;
            $table_accounts = $wpdb->prefix . 'ts_ml_accounts';
            
            $wpdb->insert(
                $table_accounts,
                array(
                    'account_name' => sanitize_text_field($_POST['account_name']),
                    'user_id' => get_current_user_id(),
                    'country' => sanitize_text_field($_POST['country']),
                    'is_active' => 1,
                ),
                array('%s', '%d', '%s', '%d')
            );
            
            echo '<div class="notice notice-success"><p>' . esc_html__('Conta adicionada com sucesso!', 'ts-ml-integration') . '</p></div>';
        }
        
        // Handle settings save
        if (isset($_POST['save_settings']) && check_admin_referer('ts_ml_save_settings')) {
            update_option('ts_ml_auto_sync', isset($_POST['auto_sync']) ? 'yes' : 'no');
            update_option('ts_ml_auto_sync_stock', isset($_POST['auto_sync_stock']) ? 'yes' : 'no');
            update_option('ts_ml_auto_sync_prices', isset($_POST['auto_sync_prices']) ? 'yes' : 'no');
            update_option('ts_ml_auto_sync_orders', isset($_POST['auto_sync_orders']) ? 'yes' : 'no');
            update_option('ts_ml_ai_enabled', isset($_POST['ai_enabled']) ? 'yes' : 'no');
            update_option('ts_ml_ai_api_key', sanitize_text_field($_POST['ai_api_key'] ?? ''));
            update_option('ts_ml_debug_mode', isset($_POST['debug_mode']) ? 'yes' : 'no');
            update_option('ts_ml_sync_frequency', sanitize_text_field($_POST['sync_frequency'] ?? 'hourly'));
            
            echo '<div class="notice notice-success"><p>' . esc_html__('Configurações salvas com sucesso!', 'ts-ml-integration') . '</p></div>';
        }
        ?>
        
        <!-- Advanced Settings -->
        <h2><?php esc_html_e('Configurações Avançadas', 'ts-ml-integration'); ?></h2>
        
        <form method="post" action="">
            <?php wp_nonce_field('ts_ml_save_settings'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Sincronização Automática', 'ts-ml-integration'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="auto_sync" value="1" <?php checked(get_option('ts_ml_auto_sync'), 'yes'); ?> />
                            <?php esc_html_e('Ativar sincronização automática de produtos', 'ts-ml-integration'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Sincroniza produtos automaticamente via cron jobs.', 'ts-ml-integration'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php esc_html_e('Sincronização de Estoque', 'ts-ml-integration'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="auto_sync_stock" value="1" <?php checked(get_option('ts_ml_auto_sync_stock'), 'yes'); ?> />
                            <?php esc_html_e('Sincronizar estoque automaticamente', 'ts-ml-integration'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Atualiza o estoque no Mercado Livre quando alterado no WooCommerce.', 'ts-ml-integration'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php esc_html_e('Sincronização de Preços', 'ts-ml-integration'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="auto_sync_prices" value="1" <?php checked(get_option('ts_ml_auto_sync_prices'), 'yes'); ?> />
                            <?php esc_html_e('Sincronizar preços automaticamente', 'ts-ml-integration'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Atualiza os preços no Mercado Livre quando alterados no WooCommerce.', 'ts-ml-integration'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php esc_html_e('Sincronização de Pedidos', 'ts-ml-integration'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="auto_sync_orders" value="1" <?php checked(get_option('ts_ml_auto_sync_orders'), 'yes'); ?> />
                            <?php esc_html_e('Sincronizar pedidos automaticamente', 'ts-ml-integration'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Importa pedidos do Mercado Livre automaticamente.', 'ts-ml-integration'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php esc_html_e('Frequência de Sincronização', 'ts-ml-integration'); ?></th>
                    <td>
                        <select name="sync_frequency">
                            <option value="hourly" <?php selected(get_option('ts_ml_sync_frequency'), 'hourly'); ?>><?php esc_html_e('A cada hora', 'ts-ml-integration'); ?></option>
                            <option value="twicedaily" <?php selected(get_option('ts_ml_sync_frequency'), 'twicedaily'); ?>><?php esc_html_e('Duas vezes por dia', 'ts-ml-integration'); ?></option>
                            <option value="daily" <?php selected(get_option('ts_ml_sync_frequency'), 'daily'); ?>><?php esc_html_e('Uma vez por dia', 'ts-ml-integration'); ?></option>
                        </select>
                        <p class="description"><?php esc_html_e('Frequência das sincronizações automáticas via cron.', 'ts-ml-integration'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php esc_html_e('Integração com ChatGPT', 'ts-ml-integration'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="ai_enabled" value="1" <?php checked(get_option('ts_ml_ai_enabled'), 'yes'); ?> />
                            <?php esc_html_e('Ativar respostas automáticas com IA', 'ts-ml-integration'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Use ChatGPT para gerar respostas automáticas às mensagens do Mercado Livre.', 'ts-ml-integration'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="ai_api_key"><?php esc_html_e('Chave API OpenAI', 'ts-ml-integration'); ?></label>
                    </th>
                    <td>
                        <input type="password" name="ai_api_key" id="ai_api_key" value="<?php echo esc_attr(get_option('ts_ml_ai_api_key')); ?>" class="regular-text" />
                        <p class="description"><?php esc_html_e('Chave API do OpenAI para usar ChatGPT. Obtenha em', 'ts-ml-integration'); ?> 
                           <a href="https://platform.openai.com/api-keys" target="_blank">platform.openai.com</a>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php esc_html_e('Modo Debug', 'ts-ml-integration'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="debug_mode" value="1" <?php checked(get_option('ts_ml_debug_mode'), 'yes'); ?> />
                            <?php esc_html_e('Ativar modo debug', 'ts-ml-integration'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Ativa logs detalhados para depuração. Os logs são salvos em', 'ts-ml-integration'); ?> 
                           <code><?php echo esc_html(wp_upload_dir()['basedir'] . '/ts-ml-logs/'); ?></code>
                        </p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="save_settings" class="button button-primary" value="<?php esc_attr_e('Salvar Configurações', 'ts-ml-integration'); ?>" />
            </p>
        </form>
        
        <!-- API Credentials Section -->
        <h2><?php esc_html_e('Credenciais da API do Mercado Livre', 'ts-ml-integration'); ?></h2>
        <p><?php esc_html_e('Para usar este plugin, você precisa criar uma aplicação no Mercado Livre Developers e obter suas credenciais.', 'ts-ml-integration'); ?></p>
        <p>
            <a href="https://developers.mercadolivre.com.br/" target="_blank" class="button">
                <?php esc_html_e('Criar Aplicação no Mercado Livre', 'ts-ml-integration'); ?>
            </a>
        </p>
        
        <form method="post" action="">
            <?php wp_nonce_field('ts_ml_save_api_credentials'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="app_id_br"><?php esc_html_e('App ID (Brasil)', 'ts-ml-integration'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="app_id_br" id="app_id_br" value="<?php echo esc_attr(get_option('ts_ml_app_id_BR')); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="app_secret_br"><?php esc_html_e('Secret Key (Brasil)', 'ts-ml-integration'); ?></label>
                    </th>
                    <td>
                        <input type="password" name="app_secret_br" id="app_secret_br" value="<?php echo esc_attr(get_option('ts_ml_app_secret_BR')); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>
            
            <?php
            if (isset($_POST['save_api_credentials']) && check_admin_referer('ts_ml_save_api_credentials')) {
                update_option('ts_ml_app_id_BR', sanitize_text_field($_POST['app_id_br'] ?? ''));
                update_option('ts_ml_app_secret_BR', sanitize_text_field($_POST['app_secret_br'] ?? ''));
                echo '<div class="notice notice-success"><p>' . esc_html__('Credenciais salvas com sucesso!', 'ts-ml-integration') . '</p></div>';
            }
            ?>
            
            <p class="submit">
                <input type="submit" name="save_api_credentials" class="button button-primary" value="<?php esc_attr_e('Salvar Credenciais', 'ts-ml-integration'); ?>" />
            </p>
        </form>
    </div>
</div>
