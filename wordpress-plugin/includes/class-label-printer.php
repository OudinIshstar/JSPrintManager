<?php
/**
 * CTT Label Printer
 * Gestisce la stampa delle etichette tramite JSPrintManager
 */

if (!defined('ABSPATH')) {
    exit;
}

class JSPM_CTT_Label_Printer {
    
    private $settings;
    
    public function __construct() {
        $this->settings = get_option('jspm_ctt_settings', array());
    }
    
    /**
     * Genera HTML per etichetta CTT
     */
    public function generate_label_html($order, $print_mode = 'color') {
        if (is_numeric($order)) {
            $order = wc_get_order($order);
        }
        
        if (!$order) {
            return '';
        }
        
        $tracking = get_post_meta($order->get_id(), '_ctt_tracking_number', true);
        $weight = $this->calculate_weight($order);
        
        $bg_color = ($print_mode === 'bw') ? '#ffffff' : '#0066cc';
        $text_color = ($print_mode === 'bw') ? '#000000' : '#ffffff';
        $border_color = ($print_mode === 'bw') ? '#000000' : '#0066cc';
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                @page {
                    size: 10cm 15cm;
                    margin: 0;
                }
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: Arial, sans-serif;
                    font-size: 10pt;
                    <?php if ($print_mode === 'bw'): ?>
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                    color: #000000;
                    <?php endif; ?>
                }
                .label-container {
                    width: 10cm;
                    height: 15cm;
                    padding: 5mm;
                    border: 2px solid <?php echo $border_color; ?>;
                }
                .header {
                    background-color: <?php echo $bg_color; ?>;
                    color: <?php echo $text_color; ?>;
                    padding: 5mm;
                    text-align: center;
                    margin-bottom: 3mm;
                    <?php if ($print_mode === 'bw'): ?>
                    border: 2px solid #000000;
                    <?php endif; ?>
                }
                .header h1 {
                    font-size: 16pt;
                    font-weight: bold;
                    margin-bottom: 2mm;
                }
                .header .service {
                    font-size: 14pt;
                    font-weight: bold;
                }
                .tracking {
                    text-align: center;
                    padding: 3mm;
                    border: 2px solid <?php echo $border_color; ?>;
                    margin-bottom: 3mm;
                }
                .tracking .code {
                    font-size: 18pt;
                    font-weight: bold;
                    letter-spacing: 2px;
                    margin-bottom: 2mm;
                }
                .tracking .barcode {
                    font-family: 'Libre Barcode 128', monospace;
                    font-size: 40pt;
                    margin: 2mm 0;
                }
                .section {
                    margin-bottom: 3mm;
                    padding: 2mm;
                    border: 1px solid #cccccc;
                }
                .section-title {
                    font-weight: bold;
                    font-size: 9pt;
                    color: <?php echo $bg_color; ?>;
                    margin-bottom: 2mm;
                    text-transform: uppercase;
                    <?php if ($print_mode === 'bw'): ?>
                    color: #000000;
                    text-decoration: underline;
                    <?php endif; ?>
                }
                .address-line {
                    margin-bottom: 1mm;
                    line-height: 1.4;
                }
                .postal-code {
                    font-weight: bold;
                    font-size: 12pt;
                }
                .info-row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 1mm;
                }
                .info-label {
                    font-weight: bold;
                }
                .footer {
                    position: absolute;
                    bottom: 5mm;
                    left: 5mm;
                    right: 5mm;
                    text-align: center;
                    font-size: 8pt;
                    border-top: 1px solid #cccccc;
                    padding-top: 2mm;
                }
            </style>
        </head>
        <body>
            <div class="label-container">
                <!-- Header CTT -->
                <div class="header">
                    <h1>CTT CORREIOS</h1>
                    <div class="service">CORREIO AZUL</div>
                </div>
                
                <!-- Codice tracking e barcode -->
                <div class="tracking">
                    <div class="code"><?php echo esc_html($tracking); ?></div>
                    <div class="barcode">*<?php echo esc_html($tracking); ?>*</div>
                </div>
                
                <!-- Destinatario -->
                <div class="section">
                    <div class="section-title">Destinatário</div>
                    <?php if ($order->get_billing_company()): ?>
                        <div class="address-line"><strong><?php echo esc_html($order->get_billing_company()); ?></strong></div>
                    <?php endif; ?>
                    <div class="address-line"><?php echo esc_html($order->get_formatted_billing_full_name()); ?></div>
                    <div class="address-line"><?php echo esc_html($order->get_billing_address_1()); ?></div>
                    <?php if ($order->get_billing_address_2()): ?>
                        <div class="address-line"><?php echo esc_html($order->get_billing_address_2()); ?></div>
                    <?php endif; ?>
                    <div class="address-line postal-code">
                        <?php echo esc_html($order->get_billing_postcode()); ?> <?php echo esc_html($order->get_billing_city()); ?>
                    </div>
                    <?php if ($order->get_billing_phone()): ?>
                        <div class="address-line">Tel: <?php echo esc_html($order->get_billing_phone()); ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Mittente -->
                <div class="section">
                    <div class="section-title">Remetente</div>
                    <div class="address-line"><?php echo esc_html($this->get_setting('sender_name')); ?></div>
                    <div class="address-line"><?php echo esc_html($this->get_setting('sender_address')); ?></div>
                    <div class="address-line postal-code">
                        <?php echo esc_html($this->get_setting('sender_postal_code')); ?> 
                        <?php echo esc_html($this->get_setting('sender_city')); ?>
                    </div>
                </div>
                
                <!-- Informazioni spedizione -->
                <div class="section">
                    <div class="info-row">
                        <span class="info-label">Peso:</span>
                        <span><?php echo esc_html($weight); ?> kg</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Data:</span>
                        <span><?php echo date('d/m/Y'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ref.:</span>
                        <span>ORD-<?php echo esc_html($order->get_order_number()); ?></span>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="footer">
                    www.ctt.pt | <?php echo esc_html($order->get_order_number()); ?>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Genera PDF dell'etichetta
     */
    public function generate_label_pdf($order, $print_mode = 'color') {
        if (!class_exists('TCPDF')) {
            // Se TCPDF non è disponibile, genera HTML
            return $this->generate_label_html($order, $print_mode);
        }
        
        // Implementazione con TCPDF se necessario
        // Per ora usiamo HTML
        return $this->generate_label_html($order, $print_mode);
    }
    
    /**
     * Genera ZPL per stampanti termiche Zebra (opzionale)
     */
    public function generate_label_zpl($order) {
        if (is_numeric($order)) {
            $order = wc_get_order($order);
        }
        
        if (!$order) {
            return '';
        }
        
        $tracking = get_post_meta($order->get_id(), '_ctt_tracking_number', true);
        $weight = $this->calculate_weight($order);
        
        // Comando ZPL per stampante termica
        $zpl = "^XA\n";
        
        // Header CTT
        $zpl .= "^FO50,50^GB700,100,3^FS\n";
        $zpl .= "^FO100,70^A0N,40,40^FDCTT CORREIOS^FS\n";
        $zpl .= "^FO100,110^A0N,30,30^FDCORREIO AZUL^FS\n";
        
        // Tracking code
        $zpl .= "^FO50,180^A0N,35,35^FD" . $tracking . "^FS\n";
        
        // Barcode
        $zpl .= "^FO50,230^BCN,100,Y,N,N^FD" . $tracking . "^FS\n";
        
        // Destinatario
        $zpl .= "^FO50,360^A0N,25,25^FDDestinatario:^FS\n";
        $zpl .= "^FO50,390^A0N,30,30^FD" . substr($order->get_formatted_billing_full_name(), 0, 30) . "^FS\n";
        $zpl .= "^FO50,425^A0N,25,25^FD" . substr($order->get_billing_address_1(), 0, 35) . "^FS\n";
        $zpl .= "^FO50,455^A0N,30,30^FD" . $order->get_billing_postcode() . " " . $order->get_billing_city() . "^FS\n";
        
        // Info spedizione
        $zpl .= "^FO50,520^A0N,20,20^FDPeso: " . $weight . " kg^FS\n";
        $zpl .= "^FO50,545^A0N,20,20^FDData: " . date('d/m/Y') . "^FS\n";
        $zpl .= "^FO50,570^A0N,20,20^FDRef: ORD-" . $order->get_order_number() . "^FS\n";
        
        $zpl .= "^XZ\n";
        
        return $zpl;
    }
    
    /**
     * Calcola peso ordine
     */
    private function calculate_weight($order) {
        $total_weight = 0;
        
        foreach ($order->get_items() as $item) {
            $product = $item->get_product();
            if ($product) {
                $weight = $product->get_weight();
                if ($weight) {
                    $total_weight += floatval($weight) * $item->get_quantity();
                }
            }
        }
        
        if ($total_weight == 0) {
            $total_weight = 0.5;
        }
        
        return number_format($total_weight, 3, '.', '');
    }
    
    /**
     * Ottiene impostazione
     */
    private function get_setting($key) {
        return isset($this->settings[$key]) ? $this->settings[$key] : '';
    }
}
