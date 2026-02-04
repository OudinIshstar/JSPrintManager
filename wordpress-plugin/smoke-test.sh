#!/bin/bash
# Smoke Test Rapido - JSPrintManager CTT Plugin
# Esegui questo script per verificare che il plugin sia funzionante

echo "🔍 SMOKE TEST - JSPrintManager CTT Integration Plugin"
echo "=================================================="
echo ""

cd "$(dirname "$0")"

# Contatore errori
ERRORS=0

# Test 1: File esistenti
echo "📁 Test 1: Verifica Files Essenziali"
for file in \
    "jspm-ctt-integration.php" \
    "assets/js/JSPrintManager.js" \
    "assets/js/admin.js" \
    "assets/css/admin.css" \
    "assets/css/modal.css" \
    "includes/class-file-generator.php" \
    "includes/class-label-printer.php" \
    "templates/admin-page.php" \
    "templates/settings-page.php"
do
    if [ -f "$file" ]; then
        echo "   ✅ $file"
    else
        echo "   ❌ $file MANCANTE!"
        ERRORS=$((ERRORS+1))
    fi
done
echo ""

# Test 2: Sintassi PHP
echo "🐘 Test 2: Sintassi PHP"
PHP_ERRORS=0
for file in $(find . -name "*.php"); do
    if ! php -l "$file" > /dev/null 2>&1; then
        echo "   ❌ Errore sintassi in $file"
        php -l "$file"
        PHP_ERRORS=$((PHP_ERRORS+1))
        ERRORS=$((ERRORS+1))
    fi
done
if [ $PHP_ERRORS -eq 0 ]; then
    echo "   ✅ Tutti i file PHP hanno sintassi corretta"
fi
echo ""

# Test 3: Percorso JSPrintManager.js
echo "🔗 Test 3: Percorso JSPrintManager.js"
if grep -q "JSPM_CTT_PLUGIN_URL . 'assets/js/JSPrintManager.js'" jspm-ctt-integration.php; then
    echo "   ✅ Percorso JSPrintManager.js corretto"
else
    echo "   ❌ Percorso JSPrintManager.js non trovato o errato!"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Test 4: Handler AJAX
echo "🔌 Test 4: Handler AJAX"
AJAX_HANDLERS=$(grep -c "add_action('wp_ajax_jspm_ctt" jspm-ctt-integration.php)
echo "   📊 Handler AJAX registrati: $AJAX_HANDLERS"

# Verifica ogni handler chiamato da JS
echo "   Verifica corrispondenze:"
for action in "jspm_ctt_get_orders" "jspm_ctt_get_order_data" "jspm_ctt_generate_file" "jspm_ctt_update_settings" "jspm_ctt_get_label_html"; do
    if grep -q "add_action('wp_ajax_${action}'" jspm-ctt-integration.php; then
        echo "      ✅ ${action}"
    else
        echo "      ❌ ${action} MANCANTE!"
        ERRORS=$((ERRORS+1))
    fi
done
echo ""

# Test 5: Metodi implementati
echo "⚙️  Test 5: Metodi AJAX Implementati"
for method in "ajax_get_orders" "ajax_get_order_data" "ajax_generate_ctt_file" "ajax_update_settings" "ajax_get_label_html"; do
    if grep -q "public function ${method}()" jspm-ctt-integration.php; then
        echo "   ✅ ${method}()"
    else
        echo "   ❌ ${method}() NON IMPLEMENTATO!"
        ERRORS=$((ERRORS+1))
    fi
done
echo ""

# Test 6: Classi richieste
echo "🎯 Test 6: Classi Incluse"
if grep -q "require_once.*class-file-generator.php" jspm-ctt-integration.php; then
    echo "   ✅ class-file-generator.php inclusa"
else
    echo "   ❌ class-file-generator.php NON inclusa!"
    ERRORS=$((ERRORS+1))
fi

if grep -q "require_once.*class-label-printer.php" jspm-ctt-integration.php; then
    echo "   ✅ class-label-printer.php inclusa"
else
    echo "   ❌ class-label-printer.php NON inclusa!"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Test 7: Dipendenze JavaScript
echo "📜 Test 7: Dipendenze JavaScript"
if grep -q "wp_enqueue_script('jspm-ctt-admin'.*array('jquery')" jspm-ctt-integration.php; then
    echo "   ✅ jQuery dichiarato come dipendenza"
else
    echo "   ⚠️  jQuery potrebbe non essere dichiarato come dipendenza"
fi

if grep -q "wp_enqueue_script('jspm-manager'" jspm-ctt-integration.php; then
    echo "   ✅ JSPrintManager.js caricato"
else
    echo "   ❌ JSPrintManager.js NON caricato!"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Test 8: Verifica JSPM in JavaScript
echo "🖨️  Test 8: Uso JSPrintManager in JavaScript"
if grep -q "typeof JSPM !== 'undefined'" assets/js/admin.js; then
    echo "   ✅ Controllo esistenza JSPM presente"
else
    echo "   ⚠️  Controllo JSPM potrebbe mancare"
fi

if grep -q "JSPM.JSPrintManager.start()" assets/js/admin.js; then
    echo "   ✅ JSPrintManager.start() chiamato"
else
    echo "   ⚠️  JSPrintManager.start() potrebbe non essere chiamato"
fi
echo ""

# Test 9: Nonce Security
echo "🔒 Test 9: Sicurezza (Nonce)"
NONCE_CHECKS=$(grep -c "check_ajax_referer" jspm-ctt-integration.php)
echo "   📊 Controlli nonce: $NONCE_CHECKS"
if [ $NONCE_CHECKS -ge 5 ]; then
    echo "   ✅ Controlli nonce presenti"
else
    echo "   ⚠️  Pochi controlli nonce ($NONCE_CHECKS), verifica la sicurezza"
fi
echo ""

# Test 10: Plugin Header
echo "📋 Test 10: Plugin Header"
if grep -q "Plugin Name:" jspm-ctt-integration.php; then
    echo "   ✅ Plugin header presente"
    PLUGIN_NAME=$(grep "Plugin Name:" jspm-ctt-integration.php | cut -d: -f2)
    echo "      Nome:${PLUGIN_NAME}"
    PLUGIN_VERSION=$(grep "Version:" jspm-ctt-integration.php | head -1 | cut -d: -f2)
    echo "      Versione:${PLUGIN_VERSION}"
else
    echo "   ❌ Plugin header mancante!"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Risultato finale
echo "=================================================="
if [ $ERRORS -eq 0 ]; then
    echo "✅ TUTTI I TEST SUPERATI!"
    echo ""
    echo "Il plugin è pronto per essere:"
    echo "  1. Copiato nella cartella wp-content/plugins/ di WordPress"
    echo "  2. Attivato dall'interfaccia di amministrazione"
    echo "  3. Configurato in 'CTT Correio' → 'Impostazioni'"
    echo ""
    echo "⚠️  IMPORTANTE: Dopo l'attivazione, verifica la console browser"
    echo "   per assicurarti che non ci siano errori JavaScript."
    exit 0
else
    echo "❌ $ERRORS ERRORI TROVATI!"
    echo ""
    echo "Correggi gli errori prima di usare il plugin."
    exit 1
fi
