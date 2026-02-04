# JSPrintManager CTT Integration - WordPress Plugin

Plugin WordPress per l'integrazione di **WooCommerce** con **CTT Correio Azul** e **JSPrintManager** per la stampa automatica di etichette di spedizione.

![Version](https://img.shields.io/badge/version-1.1.0-blue)
![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-green)
![WooCommerce](https://img.shields.io/badge/WooCommerce-5.0%2B-purple)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)
![License](https://img.shields.io/badge/license-GPLv2-orange)

## 📋 Caratteristiche

- ✅ Gestione ordini WooCommerce per spedizioni CTT
- ✅ Generazione automatica file CTT (.txt) formato importazione
- ✅ Stampa automatica etichette tramite JSPrintManager
- ✅ Configurazione flessibile: peso, formato, destinazione per ordine
- ✅ Applicazione massiva impostazioni a tutti gli ordini
- ✅ Modalità stampa colori o bianco/nero
- ✅ Meta box nella pagina ordine singolo
- ✅ Interfaccia intuitiva e responsive

## 🚀 Installazione

### Scarica il Plugin

Il plugin è distribuito come file ZIP pronto per l'installazione in WordPress:

```bash
# Scarica dalla cartella dist/
jspm-ctt-integration-1.1.0.zip
```

### Installa in WordPress

1. Vai su **WordPress Admin → Plugin → Aggiungi Nuovo**
2. Clicca **"Carica Plugin"**
3. Seleziona il file `jspm-ctt-integration-1.1.0.zip`
4. Clicca **"Installa Ora"** → **"Attiva"**

### Configurazione Iniziale

1. Vai su **CTT Correio → Impostazioni**
2. Inserisci i dati CTT:
   - Numero Cliente
   - Numero Contratto
   - Dati Mittente
3. Installa **JSPrintManager Client App**: https://www.neodynamic.com/downloads/jspm/

## 📖 Utilizzo

### Workflow Base

1. **Seleziona ordini** dalla pagina CTT Correio
2. **Configura** peso, formato, destinazione
3. **Clicca "Genera e Stampa"**
4. Il sistema genera file CTT e stampa etichette automaticamente

### Configurazione Ordini

- **Peso**: Obbligatorio (es: 0.5 per 500g)
- **Formato**: 📄 Documento (buste) o 📦 Pacco (scatole)
- **Destinazione**: 🇵🇹 Nazionale o Internazionale
- **Prodotto CTT**: C01 (Correio Azul), C02 (Normal), C13 (Registado)

## 🛠️ Sviluppo

### Requisiti

- PHP 8.0+ (testato fino a 8.3)
- WordPress 5.8+
- WooCommerce 5.0+
- MySQL 5.7+

### Build del Plugin

```bash
# Crea pacchetto ZIP
./build-plugin.sh

# Output in:
dist/jspm-ctt-integration-1.1.0.zip
```

### Test

```bash
# Test sintassi PHP
./smoke-test.sh

# Verifica manuale
# Vedi TEST-CHECKLIST.md
```

### Struttura File

```
wordpress-plugin/
├── jspm-ctt-integration.php  ← Plugin principale
├── readme.txt                ← WordPress.org readme
├── uninstall.php             ← Cleanup
├── assets/
│   ├── css/                  ← Stili admin
│   └── js/                   ← JavaScript + JSPrintManager
├── includes/
│   ├── class-file-generator.php  ← Generazione file CTT
│   └── class-label-printer.php   ← Gestione stampa
├── templates/
│   ├── admin-page.php        ← Pagina gestione
│   └── settings-page.php     ← Impostazioni
└── dist/
    └── jspm-ctt-integration-1.1.0.zip  ← Pacchetto installabile
```

## 📚 Documentazione

- [INSTALLAZIONE.md](INSTALLAZIONE.md) - Guida completa installazione
- [SCARICA-E-INSTALLA.md](SCARICA-E-INSTALLA.md) - Quick start
- [TEST-CHECKLIST.md](TEST-CHECKLIST.md) - Checklist test

## 🔧 Requisiti Tecnici

### Server
- WordPress 5.8+
- WooCommerce 5.0+
- PHP 8.0+ (compatibile con 8.3)
- MySQL 5.7+

### Client (per stampa)
- JSPrintManager Client App
- Stampante configurata
- Browser moderno (Chrome 90+, Firefox 88+, Edge 90+, Safari 14+)

## 🐛 Troubleshooting

### Plugin non visibile
Verifica che WooCommerce sia attivo e l'utente abbia permessi `manage_woocommerce`

### Stampa non funziona
1. Verifica JSPrintManager Client App in esecuzione
2. Controlla console browser (F12)
3. Verifica stampante configurata

### Ordini non caricano
1. Verifica presenza ordini nello stato selezionato
2. Controlla console per errori AJAX
3. Verifica permessi utente

## 📝 Changelog

### v1.1.0 (2026-02-04)

**Correzioni:**
- ✅ Fix percorso JSPrintManager.js
- ✅ Aggiunto handler AJAX `jspm_ctt_get_label_html`
- ✅ Migliorata condizione caricamento script
- ✅ Aggiornato requisito PHP a 8.0+

**Miglioramenti:**
- ✅ Gestione errori ottimizzata
- ✅ Codice JavaScript refactoring
- ✅ Documentazione completa
- ✅ File sicurezza aggiunti

### v1.0.0
- Prima release pubblica

## 🤝 Contribuire

1. Fork del repository
2. Crea branch feature (`git checkout -b feature/AmazingFeature`)
3. Commit modifiche (`git commit -m 'Add some AmazingFeature'`)
4. Push al branch (`git push origin feature/AmazingFeature`)
5. Apri Pull Request

## 📄 Licenza

Questo plugin è rilasciato sotto licenza **GPLv2 or later**.

Vedi [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html) per dettagli.

## 👥 Credits

- **Sviluppatore**: CCT.PT
- **JSPrintManager**: [Neodynamic](https://www.neodynamic.com/products/printing/jspm/)
- **CTT Correio**: [CTT Portugal](https://www.ctt.pt/)

## 📞 Supporto

Per supporto o segnalazioni:
- **Email**: support@cct.pt
- **Repository**: [GitHub Issues](../../issues)

---

**JSPrintManager CTT Integration v1.1.0**  
Developed with ❤️ by CCT.PT
