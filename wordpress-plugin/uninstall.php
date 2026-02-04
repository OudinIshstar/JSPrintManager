<?php
/**
 * Uninstall JSPrintManager CTT Integration
 *
 * Removes all plugin data when uninstalled
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('jspm_ctt_settings');

// Delete all order meta data
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_ctt_%'");
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_jspm_ctt_%'");

// Clear any cached data
wp_cache_flush();
