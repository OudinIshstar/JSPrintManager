<?php
/**
 * Plugin Name: JSPrintManager CTT Integration
 * Plugin URI: https://cct.pt
 * Description: Integrazione WooCommerce con CTT Correio Azul e JSPrintManager per stampa automatica etichette
 * Version: 1.1.0
 * Author: CCT.PT
 * Text Domain: jspm-ctt
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 8.0
 * WC requires at least: 5.0
 * WC tested up to: 9.5
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('JSPM_CTT_VERSION', '1.1.0');
define('JSPM_CTT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('JSPM_CTT_PLUGIN_URL', plugin_dir_url(__FILE__));

class JSPM_CTT_Integration {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // AJAX actions
        add_action('wp_ajax_jspm_ctt_generate_file', array($this, 'ajax_generate_ctt_file'));
        add_action('wp_ajax_jspm_ctt_get_orders', array($this, 'ajax_get_orders'));
        add_action('wp_ajax_jspm_ctt_update_settings', array($this, 'ajax_update_settings'));
        add_action('wp_ajax_jspm_ctt_save_order_data', array($this, 'ajax_save_order_data'));
        add_action('wp_ajax_jspm_ctt_get_order_data', array($this, 'ajax_get_order_data'));
        add_action('wp_ajax_jspm_ctt_get_label_html', array($this, 'ajax_get_label_html'));
        
        // Add bulk action to orders page
        add_filter('bulk_actions-edit-shop_order', array($this, 'add_bulk_actions'));
        add_filter('handle_bulk_actions-edit-shop_order', array($this, 'handle_bulk_actions'), 10, 3);
        
        // Add meta box to order page
        add_action('add_meta_boxes', array($this, 'add_order_meta_box'));
    }
    
    public function init() {
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', function() {
                echo '<div class="error"><p><strong>JSPrintManager CTT Integration</strong> richiede WooCommerce attivo!</p></div>';
            });
            return;
        }
        
        load_plugin_textdomain('jspm-ctt', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function enqueue_admin_scripts($hook) {
        // Carica su pagina plugin o su pagine ordini WooCommerce
        $is_plugin_page = ('toplevel_page_jspm-ctt-integration' === $hook || 'ctt-correio_page_jspm-ctt-settings' === $hook);
        $is_order_page = ('post.php' === $hook || 'post-new.php' === $hook) && get_post_type() === 'shop_order';
        $is_orders_list = 'edit.php' === $hook && isset($_GET['post_type']) && $_GET['post_type'] === 'shop_order';
        
        if (!$is_plugin_page && !$is_order_page && !$is_orders_list) {
            return;
        }
        
        wp_enqueue_style('jspm-ctt-admin', JSPM_CTT_PLUGIN_URL . 'assets/css/admin.css', array(), JSPM_CTT_VERSION);
        wp_enqueue_style('jspm-ctt-modal', JSPM_CTT_PLUGIN_URL . 'assets/css/modal.css', array(), JSPM_CTT_VERSION);
        wp_enqueue_script('jspm-ctt-admin', JSPM_CTT_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), JSPM_CTT_VERSION, true);
        
        // Enqueue JSPrintManager
        wp_enqueue_script('jspm-manager', JSPM_CTT_PLUGIN_URL . 'assets/js/JSPrintManager.js', array(), JSPM_CTT_VERSION, true);
        
        wp_localize_script('jspm-ctt-admin', 'jspmCttData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('jspm_ctt_nonce'),
            'i18n' => array(
                'generating' => __('Generazione file in corso...', 'jspm-ctt'),
                'printing' => __('Stampa in corso...', 'jspm-ctt'),
                'success' => __('Operazione completata!', 'jspm-ctt'),
                'error' => __('Errore durante l\'operazione', 'jspm-ctt'),
            )
        ));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            __('CTT Integration', 'jspm-ctt'),
            __('CTT Correio', 'jspm-ctt'),
            'manage_woocommerce',
            'jspm-ctt-integration',
            array($this, 'render_admin_page'),
            'dashicons-printer',
            56
        );
        
        add_submenu_page(
            'jspm-ctt-integration',
            __('Impostazioni', 'jspm-ctt'),
            __('Impostazioni', 'jspm-ctt'),
            'manage_woocommerce',
            'jspm-ctt-settings',
            array($this, 'render_settings_page')
        );
    }
    
    public function add_bulk_actions($actions) {
        $actions['jspm_ctt_generate'] = __('Genera file CTT e stampa', 'jspm-ctt');
        return $actions;
    }
    
    public function handle_bulk_actions($redirect_to, $action, $post_ids) {
        if ($action !== 'jspm_ctt_generate') {
            return $redirect_to;
        }
        
        $processed = 0;
        foreach ($post_ids as $post_id) {
            $this->process_order($post_id);
            $processed++;
        }
        
        $redirect_to = add_query_arg('jspm_ctt_processed', $processed, $redirect_to);
        return $redirect_to;
    }
    
    public function add_order_meta_box() {
        add_meta_box(
            'jspm_ctt_order_actions',
            __('CTT Correio Azul', 'jspm-ctt'),
            array($this, 'render_order_meta_box'),
            'shop_order',
            'side',
            'high'
        );
    }
    
    public function render_order_meta_box($post) {
        $order = wc_get_order($post->ID);
        ?>
        <div id="jspm-ctt-order-actions">
            <button type="button" class="button button-primary jspm-ctt-print-label" data-order-id="<?php echo $order->get_id(); ?>">
                <span class="dashicons dashicons-printer"></span>
                <?php _e('Stampa Etichetta CTT', 'jspm-ctt'); ?>
            </button>
            <button type="button" class="button jspm-ctt-download-file" data-order-id="<?php echo $order->get_id(); ?>">
                <span class="dashicons dashicons-download"></span>
                <?php _e('Scarica File CTT', 'jspm-ctt'); ?>
            </button>
            <div class="jspm-ctt-status" style="margin-top: 10px;">
                <?php
                $tracking = get_post_meta($order->get_id(), '_ctt_tracking_number', true);
                if ($tracking) {
                    echo '<p><strong>' . __('Codice tracking:', 'jspm-ctt') . '</strong> ' . esc_html($tracking) . '</p>';
                }
                ?>
            </div>
        </div>
        <?php
    }
    
    public function render_admin_page() {
        include JSPM_CTT_PLUGIN_DIR . 'templates/admin-page.php';
    }
    
    public function render_settings_page() {
        include JSPM_CTT_PLUGIN_DIR . 'templates/settings-page.php';
    }
    
    public function ajax_get_orders() {
        check_ajax_referer('jspm_ctt_nonce', 'nonce');
        
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'processing';
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 50;
        
        $orders = wc_get_orders(array(
            'limit' => $limit,
            'status' => $status,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
        
        $data = array();
        foreach ($orders as $order) {
            $data[] = array(
                'id' => $order->get_id(),
                'number' => $order->get_order_number(),
                'date' => $order->get_date_created()->format('d/m/Y H:i'),
                'customer' => $order->get_formatted_billing_full_name(),
                'total' => $order->get_formatted_order_total(),
                'status' => $order->get_status(),
            );
        }
        
        wp_send_json_success($data);
    }
    
    public function ajax_generate_ctt_file() {
        check_ajax_referer('jspm_ctt_nonce', 'nonce');
        
        if (!isset($_POST['order_ids']) || !is_array($_POST['order_ids'])) {
            wp_send_json_error(__('Nessun ordine selezionato', 'jspm-ctt'));
        }
        
        $order_ids = array_map('intval', $_POST['order_ids']);
        $print_mode = isset($_POST['print_mode']) ? sanitize_text_field($_POST['print_mode']) : 'color';
        
        // Ricevi dati personalizzati per ogni ordine
        $orders_custom_data = isset($_POST['orders_data']) ? $_POST['orders_data'] : array();
        
        try {
            $generator = new JSPM_CTT_File_Generator();
            $file_data = $generator->generate($order_ids, $orders_custom_data);
            
            // Save file temporarily
            $upload_dir = wp_upload_dir();
            $file_path = $upload_dir['path'] . '/ctt_import_' . date('YmdHis') . '.csv';
            file_put_contents($file_path, $file_data['csv']);
            
            wp_send_json_success(array(
                'file_url' => $upload_dir['url'] . '/' . basename($file_path),
                'file_path' => $file_path,
                'rows' => $file_data['rows'],
                'print_data' => $file_data['print_data'],
            ));
            
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
    
    public function ajax_update_settings() {
        check_ajax_referer('jspm_ctt_nonce', 'nonce');
        
        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(__('Permessi insufficienti', 'jspm-ctt'));
        }
        
        $settings = isset($_POST['settings']) ? $_POST['settings'] : array();
        
        update_option('jspm_ctt_settings', $settings);
        
        wp_send_json_success(__('Impostazioni salvate', 'jspm-ctt'));
    }
    
    public function ajax_save_order_data() {
        check_ajax_referer('jspm_ctt_nonce', 'nonce');
        
        if (!isset($_POST['order_id'])) {
            wp_send_json_error(__('Order ID mancante', 'jspm-ctt'));
        }
        
        $order_id = intval($_POST['order_id']);
        $weight = isset($_POST['weight']) ? sanitize_text_field($_POST['weight']) : '';
        $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '1';
        $destination = isset($_POST['destination']) ? sanitize_text_field($_POST['destination']) : 'PT';
        $product = isset($_POST['product']) ? sanitize_text_field($_POST['product']) : 'C01';
        
        update_post_meta($order_id, '_ctt_weight', $weight);
        update_post_meta($order_id, '_ctt_format', $format);
        update_post_meta($order_id, '_ctt_destination', $destination);
        update_post_meta($order_id, '_ctt_product', $product);
        
        wp_send_json_success(__('Dati salvati', 'jspm-ctt'));
    }
    
    public function ajax_get_order_data() {
        check_ajax_referer('jspm_ctt_nonce', 'nonce');
        
        if (!isset($_POST['order_ids'])) {
            wp_send_json_error(__('Order IDs mancanti', 'jspm-ctt'));
        }
        
        $order_ids = array_map('intval', $_POST['order_ids']);
        $orders_data = array();
        
        foreach ($order_ids as $order_id) {
            $orders_data[$order_id] = array(
                'weight' => get_post_meta($order_id, '_ctt_weight', true),
                'format' => get_post_meta($order_id, '_ctt_format', true) ?: '1',
                'destination' => get_post_meta($order_id, '_ctt_destination', true) ?: 'PT',
                'product' => get_post_meta($order_id, '_ctt_product', true) ?: 'C01',
            );
        }
        
        wp_send_json_success($orders_data);
    }
    
    public function ajax_get_label_html() {
        check_ajax_referer('jspm_ctt_nonce', 'nonce');
        
        if (!isset($_POST['order_id'])) {
            wp_send_json_error(__('Order ID mancante', 'jspm-ctt'));
        }
        
        $order_id = intval($_POST['order_id']);
        $print_mode = isset($_POST['print_mode']) ? sanitize_text_field($_POST['print_mode']) : 'color';
        
        try {
            $printer = new JSPM_CTT_Label_Printer();
            $html = $printer->generate_label_html($order_id, $print_mode);
            
            wp_send_json_success(array('html' => $html));
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
    
    private function process_order($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return false;
        }
        
        // Mark as processed
        update_post_meta($order_id, '_ctt_processed', current_time('mysql'));
        
        return true;
    }
}

// Initialize plugin
JSPM_CTT_Integration::get_instance();

// Include additional classes
require_once JSPM_CTT_PLUGIN_DIR . 'includes/class-file-generator.php';
require_once JSPM_CTT_PLUGIN_DIR . 'includes/class-label-printer.php';
