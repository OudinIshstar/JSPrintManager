<?php
/**
 * CTT File Generator
 * Genera file di importazione nel formato CTT Correio Azul
 */

if (!defined('ABSPATH')) {
    exit;
}

class JSPM_CTT_File_Generator {
    
    private $settings;
    
    public function __construct() {
        $this->settings = get_option('jspm_ctt_settings', array(
            'client_number' => '',
            'contract_number' => '',
            'product' => 'C01', // Correio Azul
            'sender_name' => '',
            'sender_address' => '',
            'sender_postal_code' => '',
            'sender_city' => '',
            'sender_email' => '',
            'sender_phone' => '',
        ));
    }
    
    /**
     * Genera file CTT per gli ordini selezionati
     */
    public function generate($order_ids, $orders_custom_data = array()) {
        if (empty($order_ids)) {
            throw new Exception(__('Nessun ordine da processare', 'jspm-ctt'));
        }
        
        $rows = array();
        $print_data = array();
        
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                continue;
            }
            
            // Usa dati personalizzati se forniti, altrimenti usa meta salvati
            $custom_data = isset($orders_custom_data[$order_id]) ? $orders_custom_data[$order_id] : array(
                'weight' => get_post_meta($order_id, '_ctt_weight', true),
                'format' => get_post_meta($order_id, '_ctt_format', true) ?: '1',
                'destination' => get_post_meta($order_id, '_ctt_destination', true) ?: 'PT',
                'product' => get_post_meta($order_id, '_ctt_product', true) ?: 'C01',
            );
            
            $row = $this->generate_row($order, $custom_data);
            if ($row) {
                $rows[] = $row;
                $print_data[] = $this->generate_print_data($order, $custom_data);
            }
        }
        
        if (empty($rows)) {
            throw new Exception(__('Nessun ordine valido da processare', 'jspm-ctt'));
        }
        
        $csv = $this->generate_csv($rows);
        
        return array(
            'csv' => $csv,
            'rows' => count($rows),
            'print_data' => $print_data,
        );
    }
    
    /**
     * Genera una riga del file CTT per un ordine
     */
    private function generate_row($order, $custom_data = array()) {
        // Colonne secondo specifiche CTT per file nazionale/internazionale
        $row = array();
        
        // Determina se è internazionale
        $destination = isset($custom_data['destination']) ? $custom_data['destination'] : 'PT';
        $is_international = ($destination !== 'PT');
        
        // Prodotto selezionato
        $product = isset($custom_data['product']) ? $custom_data['product'] : 'C01';
        
        // A - Campo sistema (vuoto)
        $row[] = '';
        
        // B - Codice del envio
        $tracking = $this->generate_tracking_code($order, $product);
        $row[] = $tracking;
        update_post_meta($order->get_id(), '_ctt_tracking_number', $tracking);
        
        // C - Nº de cliente (remetente) - OBBLIGATORIO
        $row[] = $this->get_setting('client_number');
        
        // D - Nº de contrato (remetente) - OBBLIGATORIO
        $row[] = $this->get_setting('contract_number');
        
        // E - Referência frequente (remetente) - Non applicabile per Correio
        $row[] = '';
        
        // F - Referência frequente (destinatário) - Non applicabile
        $row[] = '';
        
        // G - Tipo de Cliente (destinatário)
        $row[] = $order->get_billing_company() ? 'C' : 'P';
        
        // H - Nome (destinatário) - OBBLIGATORIO
        $row[] = $this->sanitize_field($order->get_formatted_billing_full_name(), 50);
        
        // I - Nome de contacto (destinatário)
        $row[] = $this->sanitize_field($order->get_billing_company(), 50);
        
        // J - Morada (destinatário) - OBBLIGATORIO
        $address = trim($order->get_billing_address_1() . ' ' . $order->get_billing_address_2());
        $row[] = $this->sanitize_field($address, 100);
        
        // K - Código Postal (destinatário) - OBBLIGATORIO
        $row[] = $this->format_postal_code($order->get_billing_postcode(), $destination);
        
        // L - Localidade Postal (destinatário) - OBBLIGATORIO
        $row[] = $this->sanitize_field($order->get_billing_city(), 50);
        
        // M - Localidade (destinatário) - OBBLIGATORIO
        $row[] = $this->sanitize_field($order->get_billing_city(), 50);
        
        // N - País (destinatário) - OBBLIGATORIO
        $row[] = $destination;
        
        // O - Email (destinatário) - Condizionale (uno tra email e telefono)
        $row[] = $this->sanitize_field($order->get_billing_email(), 100);
        
        // P - Nº de Telemóvel (destinatário) - Condizionale
        $row[] = $this->format_phone($order->get_billing_phone());
        
        // Q - Nº de Telefone (destinatário)
        $row[] = '';
        
        // R - Ponto de Entrega (destinatário) - Non applicabile per Correio
        $row[] = '';
        
        // S - Peso (bruto) (Kg) - OBBLIGATORIO
        $weight = isset($custom_data['weight']) && !empty($custom_data['weight']) 
            ? floatval($custom_data['weight']) 
            : $this->calculate_weight($order);
        $row[] = number_format($weight, 3, ',', '');
        
        // T - Produto - OBBLIGATORIO
        $row[] = $product;
        
        // U - Referência de cliente
        $row[] = $this->sanitize_field('ORD-' . $order->get_order_number(), 21);
        
        // V - Instruções em caso de entrega não conseguida
        $row[] = 'DRE'; // Devolver ao remetente
        
        // W - Observações para Guia Transporte
        $row[] = '';
        
        // X - Conteúdo - Condizionale per Correio
        $row[] = '1'; // 1 = Bens, 2 = Documentos
        
        // Y - Formato - Condizionale per Correio
        $format = isset($custom_data['format']) ? $custom_data['format'] : '1';
        $row[] = $format;
        
        // Z - Prioridade/ Velocidade - Solo per internazionali
        $row[] = $is_international ? '2' : ''; // 2 = Normal (avião)
        
        // AA - Código tributário
        $row[] = '';
        
        // Servizi addizionali (AB-AM) - Opzionali
        for ($i = 0; $i < 12; $i++) {
            $row[] = '';
        }
        
        return $row;
    }
    
    /**
     * Genera codice tracking in base al prodotto
     */
    private function generate_tracking_code($order, $product = 'C01') {
        // Prefissi per prodotti:
        // C01 Correio Azul = LA
        // C02 Correio Normal = LA
        // C13 Correio Registado = RA
        // C14 Correio Registado Simples = RA
        // E01 Encomenda Postal = CA
        
        $prefix = 'LA'; // default Correio Azul
        
        if (in_array($product, array('C13', 'C14'))) {
            $prefix = 'RA'; // Registado
        } elseif ($product === 'E01') {
            $prefix = 'CA'; // Encomenda
        }
        
        $number = str_pad($order->get_id(), 9, '0', STR_PAD_LEFT);
        return $prefix . $number . 'PT';
    }
    
    /**
     * Calcola peso dell'ordine
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
        
        // Se non c'è peso, usa peso default di 0.5 kg
        if ($total_weight == 0) {
            $total_weight = 0.5;
        }
        
        // Formato: 000,000 (peso in Kg)
        return number_format($total_weight, 3, ',', '');
    }
    
    /**
     * Determina formato spedizione
     */
    private function determine_format($order) {
        // 1 - Normalizado (buste standard)
        // 2 - Não normalizado
        // 3 - Pacote Postal (pacchi)
        
        $total_weight = 0;
        foreach ($order->get_items() as $item) {
            $product = $item->get_product();
            if ($product && $product->get_weight()) {
                $total_weight += floatval($product->get_weight()) * $item->get_quantity();
            }
        }
        
        // Se peso > 2kg, usa pacchetto
        if ($total_weight > 2) {
            return '3'; // Pacote Postal
        }
        
        return '1'; // Normalizado
    }
    
    /**
     * Formatta codice postale
     */
    private function format_postal_code($postcode, $country = 'PT') {
        // Per Portogallo: formato 1000-100
        if ($country === 'PT') {
            $postcode = preg_replace('/[^0-9]/', '', $postcode);
            
            if (strlen($postcode) >= 7) {
                return substr($postcode, 0, 4) . '-' . substr($postcode, 4, 3);
            }
        }
        
        // Per altri paesi, ritorna come fornito (max 10 caratteri)
        return substr($postcode, 0, 10);
    }
    
    /**
     * Formatta numero di telefono
     */
    private function format_phone($phone) {
        // Formato: 00351961222333
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Se inizia con +351, converti in 00351
        if (strpos($phone, '+351') === 0) {
            $phone = '00351' . substr($phone, 4);
        } elseif (strpos($phone, '351') === 0) {
            $phone = '00' . $phone;
        } elseif (strpos($phone, '9') === 0 || strpos($phone, '2') === 0) {
            // Numero locale portoghese
            $phone = '00351' . $phone;
        }
        
        return $this->sanitize_field($phone, 26);
    }
    
    /**
     * Sanitizza campo testo
     */
    private function sanitize_field($value, $max_length = null) {
        $value = strip_tags($value);
        $value = str_replace(array("\r", "\n", "\t", '"'), '', $value);
        $value = trim($value);
        
        if ($max_length && strlen($value) > $max_length) {
            $value = substr($value, 0, $max_length);
        }
        
        return $value;
    }
    
    /**
     * Ottiene impostazione
     */
    private function get_setting($key) {
        return isset($this->settings[$key]) ? $this->settings[$key] : '';
    }
    
    /**
     * Genera CSV dal array di righe
     */
    private function generate_csv($rows) {
        $output = fopen('php://temp', 'r+');
        
        // Header CTT (prime 2 righe)
        fputcsv($output, array('CTT - Importação de Envios'), ';');
        fputcsv($output, array('Versão: 2.0'), ';');
        
        // Dati ordini
        foreach ($rows as $row) {
            fputcsv($output, $row, ';');
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        // Converti in formato Windows (CRLF) per compatibilità CTT
        $csv = str_replace("\n", "\r\n", $csv);
        
        return $csv;
    }
    
    /**
     * Genera dati per stampa etichetta
     */
    private function generate_print_data($order, $custom_data = array()) {
        $weight = isset($custom_data['weight']) && !empty($custom_data['weight']) 
            ? floatval($custom_data['weight']) 
            : $this->calculate_weight($order);
            
        $destination = isset($custom_data['destination']) ? $custom_data['destination'] : 'PT';
        $product = isset($custom_data['product']) ? $custom_data['product'] : 'C01';
        $format = isset($custom_data['format']) ? $custom_data['format'] : '1';
        
        return array(
            'order_id' => $order->get_id(),
            'order_number' => $order->get_order_number(),
            'tracking' => get_post_meta($order->get_id(), '_ctt_tracking_number', true),
            'customer_name' => $order->get_formatted_billing_full_name(),
            'address' => $order->get_billing_address_1() . ' ' . $order->get_billing_address_2(),
            'postal_code' => $order->get_billing_postcode(),
            'city' => $order->get_billing_city(),
            'country' => $destination,
            'weight' => number_format($weight, 3, '.', ''),
            'product' => $product,
            'format' => $format,
            'date' => date('d/m/Y'),
        );
    }
}
