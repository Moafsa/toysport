<?php
/**
 * Plugin Name: Disable WordPress Updates
 * Description: Desabilita verificações de atualização para evitar erros de conexão em desenvolvimento
 * Version: 1.0
 */

// Desabilitar verificações de atualização de temas
add_filter('pre_site_transient_update_themes', '__return_null');
add_filter('site_transient_update_themes', '__return_null');

// Desabilitar verificações de atualização de plugins
add_filter('pre_site_transient_update_plugins', '__return_null');
add_filter('site_transient_update_plugins', '__return_null');

// Desabilitar verificações de atualização do core
add_filter('pre_site_transient_update_core', '__return_null');
add_filter('site_transient_update_core', '__return_null');

// Remover avisos de atualização no admin
remove_action('admin_notices', 'update_nag', 3);
remove_action('network_admin_notices', 'update_nag', 3);
