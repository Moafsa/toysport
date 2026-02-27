<?php
define('WP_USE_THEMES', false);
if (file_exists('/var/www/html/wp-load.php')) {
    require_once('/var/www/html/wp-load.php');
    echo "WP Loaded Successfully\n";
} else {
    echo "wp-load.php not found\n";
}
