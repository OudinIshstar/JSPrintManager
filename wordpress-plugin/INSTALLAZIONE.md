# 🎉 PLUGIN PRONTO PER L'INSTALLAZIONE

Il plugin **JSPrintManager CTT Integration v1.1.0** è pronto per essere installato in WordPress/WooCommerce!

## 📦 Pacchetto Creato

```
dist/jspm-ctt-integration-1.1.0.zip (79 KB)
```

## 📋 Installazione in WordPress

### Metodo 1: Upload tramite Admin Panel (RACCOMANDATO)

1. **Scarica il file ZIP**
   - Il file si trova in: `wordpress-plugin/dist/jspm-ctt-integration-1.1.0.zip`

2. **Accedi a WordPress**
   - Vai su: **Admin Panel > Plugin > Aggiungi Nuovo**

3. **Carica il Plugin**
   - Clicca su: **"Carica Plugin"** (in alto)
   - Clicca su: **"Scegli File"**
   - Seleziona: `jspm-ctt-integration-1.1.0.zip`
   - Clicca su: **"Installa Ora"**

4. **Attiva il Plugin**
   - Clicca su: **"Attiva Plugin"**

5. **Configura il Plugin**
   - Vai su: **CTT Correio > Impostazioni**
   - Inserisci i tuoi dati CTT (numero cliente, contratto, mittente)
   - Salva

### Metodo 2: Upload via FTP/SFTP

1. **Estrai il file ZIP** sul tuo computer

2. **Carica via FTP**
   - Carica la cartella `jspm-ctt-integration` in:
   - `/wp-content/plugins/`

3. **Attiva** tramite Admin Panel > Plugin

## ✅ Verifica Post-Installazione

Dopo l'attivazione, verifica che:

- [ ] Nel menu laterale appaia: **"CTT Correio"** con icona stampante
- [ ] In **Plugins** il plugin sia attivo e senza errori
- [ ] Nella pagina **CTT Correio** vedi l'interfaccia di gestione ordini
- [ ] In **CTT Correio > Impostazioni** puoi configurare i dati

## 🔧 Configurazione Iniziale

### 1. Dati CTT (OBBLIGATORIO)

Vai in **CTT Correio > Impostazioni** e inserisci:

- **Numero Cliente CTT**: Es. 123456
- **Numero Contratto**: Es. 789012
- **Codice Cliente**: Es. 123456
- **Stampante Predefinita**: Seleziona dalla lista

### 2. Dati Mittente (OBBLIGATORIO)

- Nome azienda
- Indirizzo completo
- CAP, Città
- Telefono
- Email

### 3. Installa JSPrintManager

Per la stampa automatica:

1. Scarica: https://www.neodynamic.com/downloads/jspm/
2. Installa sul computer
3. Avvia l'applicazione (deve restare in esecuzione)

## 🚀 Utilizzo

### Workflow Completo

1. **Vai in CTT Correio**
   - Dal menu laterale di WordPress

2. **Seleziona gli ordini**
   - Spunta gli ordini da processare
   - O usa "Seleziona tutti"

3. **Scegli modalità stampa**
   - Colori (standard)
   - Bianco e nero (risparmio)

4. **Clicca azione**
   - "Genera File CTT" → solo file .txt
   - "Stampa Etichette" → solo stampa
   - "Genera e Stampa" → completo (RACCOMANDATO)

5. **Configura ogni ordine**
   - **Peso**: obbligatorio (es: 0.5 per 500g)
   - **Formato**: 📄 Documento o 📦 Pacco
   - **Destinazione**: 🇵🇹 PT o Internazionale
   - **Prodotto CTT**: C01 (Correio Azul), C02, C13, ecc.

6. **Usa "Applica a tutti"**
   - Per settare gli stessi valori su tutti gli ordini

7. **Conferma**
   - Il sistema genera + stampa automaticamente

## 📁 Contenuto del Plugin

```
jspm-ctt-integration/
├── jspm-ctt-integration.php  ← File principale
├── readme.txt                ← Info WordPress.org
├── uninstall.php            ← Pulizia dati
├── index.php                ← Protezione directory
├── assets/
│   ├── css/
│   │   ├── admin.css        ← Stili admin
│   │   └── modal.css        ← Stili modal
│   └── js/
│       ├── admin.js         ← JavaScript principale
│       └── JSPrintManager.js ← Libreria stampa
├── includes/
│   ├── class-file-generator.php  ← Generazione file CTT
│   └── class-label-printer.php   ← Gestione stampa
└── templates/
    ├── admin-page.php       ← Pagina gestione ordini
    └── settings-page.php    ← Pagina impostazioni
```

## 🔍 Troubleshooting

### Plugin non visibile dopo attivazione

- Verifica che WooCommerce sia attivo
- Controlla i permessi utente (serve `manage_woocommerce`)

### Stampa non funziona

- Verifica JSPrintManager Client App sia in esecuzione
- Controlla la console browser (F12) per errori
- Verifica stampante configurata nelle Impostazioni

### Ordini non caricano

- Verifica presenza ordini WooCommerce nello stato selezionato
- Prova a cambiare filtro stato ordini
- Controlla console browser per errori AJAX

### Peso non presente

- Configura peso nei prodotti WooCommerce
- Oppure inserisci manualmente nel modal

## 📊 Requisiti Tecnici

### Server
- ✅ WordPress 5.8+
- ✅ WooCommerce 5.0+
- ✅ PHP 8.0+ (testato fino a 8.3)
- ✅ MySQL 5.7+

### Browser
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Edge 90+
- ✅ Safari 14+

### Client
- ✅ JSPrintManager Client App
- ✅ Stampante configurata
- ✅ Connessione internet

## 🆕 Changelog v1.1.0

### Correzioni
- ✅ Corretto percorso JSPrintManager.js
- ✅ Aggiunto handler `jspm_ctt_get_label_html`
- ✅ Migliorata condizione caricamento script
- ✅ Fix supporto pagine ordini singoli

### Miglioramenti
- ✅ Gestione errori potenziata
- ✅ Codice JavaScript ottimizzato
- ✅ Aggiunto uninstall.php
- ✅ Aggiunti file index.php protezione
- ✅ Documentazione completa

## 📞 Supporto

Per supporto o segnalare bug:
- Email: support@cct.pt
- Repository: neodynamic/JSPrintManager

## 📝 Note Importanti

⚠️ **ATTENZIONE:**
- Il plugin richiede account CTT Correio Azul attivo
- Senza JSPrintManager la stampa automatica non funziona (ma puoi scaricare i file)
- Configura SEMPRE peso, formato, destinazione prima di generare
- Testa su ambiente di staging prima di usare in produzione

✅ **PRONTO PER L'USO!**

Il plugin è stato testato, verificato e pacchettizzato correttamente.
Installa con fiducia!

---

**JSPrintManager CTT Integration v1.1.0**  
Developed by CCT.PT  
Powered by JSPrintManager (Neodynamic)
