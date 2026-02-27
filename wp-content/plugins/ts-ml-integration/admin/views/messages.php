<?php
/**
 * Messages page
 *
 * @package TS_ML_Integration
 */

if (!defined('ABSPATH')) {
    exit;
}

// Handle reply
if (isset($_POST['reply_message']) && isset($_POST['message_id']) && check_admin_referer('reply_message_' . $_POST['message_id'])) {
    $message_id = intval($_POST['message_id']);
    $reply_text = sanitize_textarea_field($_POST['reply_text']);
    
    if (!empty($reply_text)) {
        $result = TS_ML_Message_Handler::instance()->send_reply($message_id, $reply_text);
        if ($result) {
            echo '<div class="notice notice-success"><p>' . esc_html__('Resposta enviada com sucesso!', 'ts-ml-integration') . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . esc_html__('Erro ao enviar resposta.', 'ts-ml-integration') . '</p></div>';
        }
    }
}

// Handle mark as read
if (isset($_GET['mark_read']) && check_admin_referer('mark_read_' . $_GET['mark_read'])) {
    global $wpdb;
    $table_messages = $wpdb->prefix . 'ts_ml_messages';
    $wpdb->update(
        $table_messages,
        array('status' => 'read'),
        array('id' => intval($_GET['mark_read'])),
        array('%s'),
        array('%d')
    );
    wp_redirect(remove_query_arg(array('mark_read', '_wpnonce')));
    exit;
}

// Get accounts
global $wpdb;
$table_accounts = $wpdb->prefix . 'ts_ml_accounts';
$accounts = $wpdb->get_results("SELECT * FROM $table_accounts WHERE is_active = 1");

// Get selected account
$selected_account = isset($_GET['account_id']) ? intval($_GET['account_id']) : (!empty($accounts) ? $accounts[0]->id : 0);

// Get filter
$status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';

// Get messages
$table_messages = $wpdb->prefix . 'ts_ml_messages';
$where = "account_id = %d";
$params = array($selected_account);

if ($status_filter !== 'all') {
    $where .= " AND status = %s";
    $params[] = $status_filter;
}

$messages = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM $table_messages WHERE $where ORDER BY created_at DESC LIMIT 50",
    $params
));
?>

<div class="wrap">
    <h1><?php esc_html_e('Mensagens - Mercado Livre', 'ts-ml-integration'); ?></h1>
    
    <div class="ts-ml-messages-page">
        <?php if (empty($accounts)) { ?>
            <div class="notice notice-warning">
                <p><?php esc_html_e('Nenhuma conta do Mercado Livre configurada.', 'ts-ml-integration'); ?></p>
            </div>
        <?php } else { ?>
            
            <!-- Account Selector and Filters -->
            <div style="display: flex; gap: 20px; margin: 20px 0; align-items: center;">
                <div>
                    <label for="account_filter"><strong><?php esc_html_e('Conta:', 'ts-ml-integration'); ?></strong></label>
                    <select id="account_filter" onchange="window.location.href='?page=ts-ml-messages&account_id='+this.value+'&status=<?php echo esc_attr($status_filter); ?>'">
                        <?php foreach ($accounts as $account) { ?>
                            <option value="<?php echo esc_attr($account->id); ?>" <?php selected($selected_account, $account->id); ?>>
                                <?php echo esc_html($account->account_name . ' (' . $account->country . ')'); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                
                <div>
                    <label for="status_filter"><strong><?php esc_html_e('Status:', 'ts-ml-integration'); ?></strong></label>
                    <select id="status_filter" onchange="window.location.href='?page=ts-ml-messages&account_id=<?php echo esc_attr($selected_account); ?>&status='+this.value">
                        <option value="all" <?php selected($status_filter, 'all'); ?>><?php esc_html_e('Todas', 'ts-ml-integration'); ?></option>
                        <option value="unread" <?php selected($status_filter, 'unread'); ?>><?php esc_html_e('Não lidas', 'ts-ml-integration'); ?></option>
                        <option value="read" <?php selected($status_filter, 'read'); ?>><?php esc_html_e('Lidas', 'ts-ml-integration'); ?></option>
                        <option value="replied" <?php selected($status_filter, 'replied'); ?>><?php esc_html_e('Respondidas', 'ts-ml-integration'); ?></option>
                    </select>
                </div>
                
                <div>
                    <a href="?page=ts-ml-messages&account_id=<?php echo esc_attr($selected_account); ?>" class="button">
                        <?php esc_html_e('Atualizar', 'ts-ml-integration'); ?>
                    </a>
                </div>
            </div>
            
            <!-- Messages List -->
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Data', 'ts-ml-integration'); ?></th>
                        <th><?php esc_html_e('Produto', 'ts-ml-integration'); ?></th>
                        <th><?php esc_html_e('Mensagem', 'ts-ml-integration'); ?></th>
                        <th><?php esc_html_e('Status', 'ts-ml-integration'); ?></th>
                        <th><?php esc_html_e('Ações', 'ts-ml-integration'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($messages)) { ?>
                        <?php foreach ($messages as $message) { 
                            $product = $message->product_id ? wc_get_product($message->product_id) : null;
                            ?>
                            <tr>
                                <td>
                                    <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($message->created_at))); ?>
                                </td>
                                <td>
                                    <?php if ($product) { ?>
                                        <a href="<?php echo esc_url(admin_url('post.php?post=' . $product->get_id() . '&action=edit')); ?>">
                                            <?php echo esc_html($product->get_name()); ?>
                                        </a>
                                    <?php } else { ?>
                                        <?php esc_html_e('N/A', 'ts-ml-integration'); ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <div style="max-width: 400px;">
                                        <?php echo esc_html(wp_trim_words($message->message_text, 30)); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-<?php echo esc_attr($message->status); ?>">
                                        <?php 
                                        $status_labels = array(
                                            'unread' => __('Não lida', 'ts-ml-integration'),
                                            'read' => __('Lida', 'ts-ml-integration'),
                                            'replied' => __('Respondida', 'ts-ml-integration'),
                                        );
                                        echo isset($status_labels[$message->status]) ? $status_labels[$message->status] : $message->status;
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="#message-<?php echo esc_attr($message->id); ?>" class="button button-small open-message-modal">
                                        <?php esc_html_e('Ver/Responder', 'ts-ml-integration'); ?>
                                    </a>
                                    <?php if ($message->status === 'unread') { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(add_query_arg('mark_read', $message->id), 'mark_read_' . $message->id)); ?>" class="button button-small">
                                            <?php esc_html_e('Marcar como lida', 'ts-ml-integration'); ?>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            
                            <!-- Message Modal -->
                            <div id="message-<?php echo esc_attr($message->id); ?>" class="ts-ml-message-modal" style="display: none;">
                                <div class="ts-ml-modal-content" style="background: #fff; padding: 30px; max-width: 600px; margin: 50px auto; border: 1px solid #ddd; border-radius: 4px;">
                                    <h2><?php esc_html_e('Mensagem do Mercado Livre', 'ts-ml-integration'); ?></h2>
                                    
                                    <div style="margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 4px;">
                                        <p><strong><?php esc_html_e('Data:', 'ts-ml-integration'); ?></strong> 
                                           <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($message->created_at))); ?>
                                        </p>
                                        <?php if ($product) { ?>
                                            <p><strong><?php esc_html_e('Produto:', 'ts-ml-integration'); ?></strong> 
                                               <a href="<?php echo esc_url(admin_url('post.php?post=' . $product->get_id() . '&action=edit')); ?>">
                                                   <?php echo esc_html($product->get_name()); ?>
                                               </a>
                                            </p>
                                        <?php } ?>
                                    </div>
                                    
                                    <div style="margin: 20px 0;">
                                        <h3><?php esc_html_e('Mensagem:', 'ts-ml-integration'); ?></h3>
                                        <p style="white-space: pre-wrap;"><?php echo esc_html($message->message_text); ?></p>
                                    </div>
                                    
                                    <form method="post" action="">
                                        <?php wp_nonce_field('reply_message_' . $message->id); ?>
                                        <input type="hidden" name="message_id" value="<?php echo esc_attr($message->id); ?>" />
                                        
                                        <h3><?php esc_html_e('Responder:', 'ts-ml-integration'); ?></h3>
                                        <textarea name="reply_text" rows="5" style="width: 100%;" required></textarea>
                                        
                                        <p style="margin-top: 15px;">
                                            <input type="submit" name="reply_message" class="button button-primary" value="<?php esc_attr_e('Enviar Resposta', 'ts-ml-integration'); ?>" />
                                            <button type="button" class="button close-modal" style="margin-left: 10px;"><?php esc_html_e('Fechar', 'ts-ml-integration'); ?></button>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5"><?php esc_html_e('Nenhuma mensagem encontrada.', 'ts-ml-integration'); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.open-message-modal').on('click', function(e) {
        e.preventDefault();
        var modalId = $(this).attr('href');
        $(modalId).fadeIn();
    });
    
    $('.close-modal').on('click', function() {
        $('.ts-ml-message-modal').fadeOut();
    });
    
    $(document).on('click', '.ts-ml-message-modal', function(e) {
        if ($(e.target).hasClass('ts-ml-message-modal')) {
            $(this).fadeOut();
        }
    });
});
</script>

<style>
.ts-ml-message-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 100000;
    overflow-y: auto;
    padding: 20px;
}
</style>
