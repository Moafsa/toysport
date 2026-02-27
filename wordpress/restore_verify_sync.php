<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

// 1. Activate Plugin
$plugin = 'ts-ml-integration/ts-ml-integration.php';
$activation_output = "";
if (!is_plugin_active($plugin)) {
    $activation_output .= "Plugin not active. Activating...\n";
    $result = activate_plugin($plugin);
    if (is_wp_error($result)) {
        die("Error activating: " . $result->get_error_message());
    }
    $activation_output .= "Plugin activated.\n";
} else {
    $activation_output .= "Plugin already active.\n";
}

echo $activation_output;

// 2. Load Plugin Dependencies (Manually if needed, as hooks passed)
if (!class_exists('TS_ML_Product_Sync')) {
    include_once(WP_CONTENT_DIR . '/plugins/ts-ml-integration/ts-ml-integration.php');
    if (function_exists('ts_ml_load_dependencies')) {
        ts_ml_load_dependencies();
    }
}

// 3. Check Account
global $wpdb;
$table_accounts = $wpdb->prefix . 'ts_ml_accounts';
$account = $wpdb->get_row("SELECT * FROM $table_accounts WHERE is_active = 1 LIMIT 1");

if (!$account) {
    die("No active Mercado Livre account found in DB. Cannot sync.\n");
}
echo "Found active account ID: " . $account->id . "\n";

// 4. Trigger Sync for LANCHA TRAXXAS (ID 490)
if (class_exists('TS_ML_Product_Sync')) {
    echo "Attempting to sync Product 490 (LANCHA TRAXXAS) from ML...\n";
    // Force 'ml_to_woo' to overwrite generic content
    $result = TS_ML_Product_Sync::instance()->sync_product(490, $account->id, 'ml_to_woo');

    if ($result) {
        echo "Sync method returned TRUE.\n";
    } else {
        echo "Sync method returned FALSE (Check logs).\n";
    }

    // 5. Check Content
    $post = get_post(490);
    echo "\n--- NEW CONTENT PREVIEW ---\n";
    echo substr(strip_tags($post->post_content), 0, 300) . "...\n";

    // Check Excerpt
    echo "\n--- NEW EXCERPT PREVIEW ---\n";
    echo $post->post_excerpt . "\n";

} else {
    die("Class TS_ML_Product_Sync not loaded.\n");
}
