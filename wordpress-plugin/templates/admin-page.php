<?php
/**
 * Admin Page Template - CTT Integration
 */

if (!defined('ABSPATH')) {
    exit;
}

$settings = get_option('jspm_ctt_settings', array());
?>

<div class="wrap jspm-ctt-admin-page">
    <div class="jspm-ctt-header">
        <h1>
            <span class="dashicons dashicons-printer" style="font-size: 32px; vertical-align: middle;"></span>
            CTT Correio Azul - Gestione Spedizioni
        </h1>
        <p>Seleziona gli ordini, configura peso/formato/destinazione e genera file CTT + stampa etichette automaticamente</p>
    </div>

    <div class="jspm-ctt-controls">
        <div class="jspm-ctt-control-group">
            <label for="jspm-ctt-order-status">Stato Ordini:</label>
            <select id="jspm-ctt-order-status">
                <option value="processing">In lavorazione</option>
                <option value="pending">In attesa</option>
                <option value="on-hold">In attesa</option>
                <option value="completed">Completati</option>
                <option value="any">Tutti</option>
            </select>
            
            <button type="button" class="button" onclick="location.reload()">
                <span class="dashicons dashicons-update"></span> Aggiorna
            </button>
        </div>

        <div class="jspm-ctt-control-group">
            <label>Modalità Stampa:</label>
            <div class="jspm-ctt-print-mode">
                <label>
                    <input type="radio" name="print_mode" value="color" checked>
                    <span class="dashicons dashicons-art" style="color: #0066cc;"></span> Colori
                </label>
                <label>
                    <input type="radio" name="print_mode" value="bw">
                    <span class="dashicons dashicons-format-image"></span> Bianco e Nero
                </label>
            </div>
        </div>

        <div class="jspm-ctt-selected-count">
            0 ordini selezionati
        </div>
    </div>

    <div class="jspm-ctt-orders-table">
        <table>
            <thead>
                <tr>
                    <th class="order-checkbox">
                        <input type="checkbox" id="jspm-ctt-select-all">
                    </th>
                    <th>Ordine</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Totale</th>
                    <th>Stato</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        Caricamento ordini...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="jspm-ctt-actions">
        <button type="button" class="jspm-ctt-btn jspm-ctt-btn-primary jspm-ctt-generate-file" disabled>
            <span class="dashicons dashicons-download"></span>
            Genera File CTT
        </button>

        <button type="button" class="jspm-ctt-btn jspm-ctt-btn-success jspm-ctt-print-labels" disabled>
            <span class="dashicons dashicons-printer"></span>
            Stampa Etichette
        </button>

        <button type="button" class="jspm-ctt-btn jspm-ctt-btn-primary jspm-ctt-generate-and-print" disabled>
            <span class="dashicons dashicons-yes"></span>
            Genera e Stampa
        </button>

        <a href="<?php echo admin_url('admin.php?page=jspm-ctt-settings'); ?>" class="jspm-ctt-btn jspm-ctt-btn-secondary">
            <span class="dashicons dashicons-admin-settings"></span>
            Impostazioni
        </a>
    </div>

    <div class="jspm-ctt-info" style="margin-top: 30px; padding: 20px; background: #f9f9f9; border-left: 4px solid #0066cc;">
        <h3>Come utilizzare:</h3>
        <ol style="margin: 15px 0; padding-left: 20px;">
            <li><strong>Seleziona gli ordini</strong> dalla tabella sopra (usa la checkbox in alto per selezionare tutti)</li>
            <li><strong>Scegli la modalità di stampa</strong>: Colori (standard) o Bianco e Nero (risparmio inchiostro)</li>
            <li><strong>Clicca su uno dei pulsanti</strong> (Genera File, Stampa o Genera e Stampa)</li>
            <li><strong>Configura ogni ordine</strong>: peso (obbligatorio), formato (documento/pacco), destinazione (PT/estero), prodotto CTT</li>
            <li><strong>Usa "Applica a tutti"</strong> per settare valori comuni rapidamente</li>
            <li><strong>Conferma</strong> e il sistema genererà file + stamperà etichette</li>
        </ol>
        
        <h4 style="margin-top: 20px;">Opzioni di configurazione:</h4>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li><strong>Peso:</strong> Obbligatorio (es: 0.5 per 500g). Se non configurato nei prodotti WooCommerce, inseriscilo qui</li>
            <li><strong>Formato:</strong> 📄 Documento (buste) o 📦 Pacco (scatole)</li>
            <li><strong>Destinazione:</strong> 🇵🇹 Nazionale (PT) o Internazionale (ES, FR, IT, DE, UK, US...)</li>
            <li><strong>Prodotto CTT:</strong> C01 Correio Azul (default), C02 Correio Normal, C13 Registado</li>
        </ul>
        
        <h4 style="margin-top: 20px;">Requisiti:</h4>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li>JSPrintManager Client App installata e in esecuzione sul computer</li>
            <li>Stampante configurata e disponibile</li>
            <li>Dati CTT configurati nelle <a href="<?php echo admin_url('admin.php?page=jspm-ctt-settings'); ?>">Impostazioni</a></li>
        </ul>

        <?php if (empty($settings['client_number']) || empty($settings['contract_number'])): ?>
        <div style="margin-top: 15px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107;">
            <strong>⚠️ Attenzione:</strong> Configura i dati CTT nelle <a href="<?php echo admin_url('admin.php?page=jspm-ctt-settings'); ?>">Impostazioni</a> prima di generare i file.
        </div>
        <?php endif; ?>
    </div>
</div>
