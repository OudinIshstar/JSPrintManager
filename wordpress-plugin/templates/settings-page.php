<?php
/**
 * Settings Page Template - CTT Integration
 */

if (!defined('ABSPATH')) {
    exit;
}

$settings = get_option('jspm_ctt_settings', array(
    'client_number' => '',
    'contract_number' => '',
    'product' => 'C01',
    'sender_name' => '',
    'sender_address' => '',
    'sender_postal_code' => '',
    'sender_city' => '',
    'sender_email' => '',
    'sender_phone' => '',
));
?>

<div class="wrap jspm-ctt-admin-page">
    <div class="jspm-ctt-header">
        <h1>
            <span class="dashicons dashicons-admin-settings" style="font-size: 32px; vertical-align: middle;"></span>
            Impostazioni CTT Correio Azul
        </h1>
        <p>Configura i dati del tuo account CTT e le informazioni del mittente</p>
    </div>

    <div class="jspm-ctt-settings">
        <div class="jspm-ctt-settings-section">
            <h2>Dati Account CTT</h2>
            <p style="margin-bottom: 20px; color: #666;">
                Questi dati sono necessari per generare i file di importazione CTT. Li trovi nell'area clienti CTT Empresas.
            </p>

            <div class="jspm-ctt-form-group">
                <label for="ctt_client_number">
                    Numero Cliente (Remetente) *
                </label>
                <input 
                    type="text" 
                    id="ctt_client_number" 
                    name="ctt_client_number" 
                    value="<?php echo esc_attr($settings['client_number']); ?>"
                    placeholder="888888888"
                    maxlength="9"
                    required
                >
                <small>Numero di 9 cifre fornito da CTT. Per modalità pre-attiva usa: 888888888</small>
            </div>

            <div class="jspm-ctt-form-group">
                <label for="ctt_contract_number">
                    Numero Contratto (Remetente) *
                </label>
                <input 
                    type="text" 
                    id="ctt_contract_number" 
                    name="ctt_contract_number" 
                    value="<?php echo esc_attr($settings['contract_number']); ?>"
                    placeholder="123456789"
                    maxlength="9"
                    required
                >
                <small>Numero di contratto di 9 cifre fornito da CTT</small>
            </div>

            <div class="jspm-ctt-form-group">
                <label for="ctt_product">
                    Prodotto CTT
                </label>
                <select id="ctt_product" name="ctt_product">
                    <option value="C01" <?php selected($settings['product'], 'C01'); ?>>C01 - Correio Azul</option>
                    <option value="C02" <?php selected($settings['product'], 'C02'); ?>>C02 - Correio Normal</option>
                    <option value="C13" <?php selected($settings['product'], 'C13'); ?>>C13 - Correio Registado</option>
                    <option value="C14" <?php selected($settings['product'], 'C14'); ?>>C14 - Correio Registado Simples</option>
                </select>
                <small>Seleziona il tipo di servizio postale da utilizzare (predefinito: Correio Azul)</small>
            </div>
        </div>

        <div class="jspm-ctt-settings-section">
            <h2>Dati Mittente (Remetente)</h2>
            <p style="margin-bottom: 20px; color: #666;">
                Questi dati verranno utilizzati per compilare le etichette e il file di importazione CTT.
            </p>

            <div class="jspm-ctt-form-group">
                <label for="ctt_sender_name">
                    Nome / Ragione Sociale *
                </label>
                <input 
                    type="text" 
                    id="ctt_sender_name" 
                    name="ctt_sender_name" 
                    value="<?php echo esc_attr($settings['sender_name']); ?>"
                    placeholder="Nome azienda o nome completo"
                    maxlength="50"
                    required
                >
            </div>

            <div class="jspm-ctt-form-group">
                <label for="ctt_sender_address">
                    Indirizzo Completo *
                </label>
                <input 
                    type="text" 
                    id="ctt_sender_address" 
                    name="ctt_sender_address" 
                    value="<?php echo esc_attr($settings['sender_address']); ?>"
                    placeholder="Via, numero civico, piano, ecc."
                    maxlength="100"
                    required
                >
            </div>

            <div class="jspm-ctt-form-group">
                <label for="ctt_sender_postal_code">
                    Codice Postale *
                </label>
                <input 
                    type="text" 
                    id="ctt_sender_postal_code" 
                    name="ctt_sender_postal_code" 
                    value="<?php echo esc_attr($settings['sender_postal_code']); ?>"
                    placeholder="1000-100"
                    maxlength="10"
                    required
                >
                <small>Formato: 1000-100</small>
            </div>

            <div class="jspm-ctt-form-group">
                <label for="ctt_sender_city">
                    Città *
                </label>
                <input 
                    type="text" 
                    id="ctt_sender_city" 
                    name="ctt_sender_city" 
                    value="<?php echo esc_attr($settings['sender_city']); ?>"
                    placeholder="Lisboa"
                    maxlength="50"
                    required
                >
            </div>

            <div class="jspm-ctt-form-group">
                <label for="ctt_sender_email">
                    Email
                </label>
                <input 
                    type="email" 
                    id="ctt_sender_email" 
                    name="ctt_sender_email" 
                    value="<?php echo esc_attr($settings['sender_email']); ?>"
                    placeholder="info@esempio.pt"
                    maxlength="100"
                >
            </div>

            <div class="jspm-ctt-form-group">
                <label for="ctt_sender_phone">
                    Telefono
                </label>
                <input 
                    type="tel" 
                    id="ctt_sender_phone" 
                    name="ctt_sender_phone" 
                    value="<?php echo esc_attr($settings['sender_phone']); ?>"
                    placeholder="+351 21 123 4567"
                    maxlength="26"
                >
            </div>
        </div>

        <div class="jspm-ctt-actions">
            <button type="button" class="jspm-ctt-btn jspm-ctt-btn-primary jspm-ctt-save-settings">
                <span class="dashicons dashicons-yes"></span>
                Salva Impostazioni
            </button>

            <a href="<?php echo admin_url('admin.php?page=jspm-ctt-integration'); ?>" class="jspm-ctt-btn jspm-ctt-btn-secondary">
                <span class="dashicons dashicons-arrow-left-alt"></span>
                Torna alla Gestione Spedizioni
            </a>
        </div>
    </div>

    <div class="jspm-ctt-info" style="margin-top: 30px; padding: 20px; background: #e7f3ff; border-left: 4px solid #0066cc;">
        <h3>Informazioni su Correio Azul (C01)</h3>
        <p style="margin: 10px 0;">
            <strong>Correio Azul</strong> è il servizio postale standard di CTT per spedizioni nazionali in Portogallo.
        </p>
        <ul style="margin: 10px 0 10px 20px;">
            <li>Consegna entro 2-3 giorni lavorativi</li>
            <li>Tracking incluso con codice che inizia con "LA"</li>
            <li>Peso massimo: 20 kg</li>
            <li>Dimensioni massime: 100 x 50 x 75 cm</li>
        </ul>
        
        <h4 style="margin-top: 20px;">Dove trovare i dati CTT:</h4>
        <ol style="margin: 10px 0 0 20px;">
            <li>Accedi a <a href="https://www.ctt.pt/empresas" target="_blank">CTT Empresas</a></li>
            <li>Vai su "Área de Cliente"</li>
            <li>Nella sezione "Dados da Conta" troverai il Numero Cliente e Numero Contratto</li>
        </ol>
    </div>
</div>
