# 🚀 PLUGIN PRONTO - SCARICA E INSTALLA

## ✅ Plugin Creato con Successo!

**Nome:** JSPrintManager CTT Integration  
**Versione:** 1.1.0  
**Dimensione:** 79 KB  
**MD5:** `8b12e0cc137979455f9bbcca3e2f0ce4`  
**File:** `jspm-ctt-integration-1.1.0.zip`

---

## 📥 SCARICA IL PLUGIN

Il file ZIP installabile si trova in:

```
wordpress-plugin/dist/jspm-ctt-integration-1.1.0.zip
```

**Scaricalo sul tuo computer prima di procedere con l'installazione in WordPress.**

---

## 🎯 INSTALLAZIONE RAPIDA (3 PASSI)

### 1️⃣ Scarica il file ZIP
```bash
wordpress-plugin/dist/jspm-ctt-integration-1.1.0.zip
```

### 2️⃣ Installa in WordPress
- Vai su: **WordPress Admin → Plugin → Aggiungi Nuovo**
- Clicca: **"Carica Plugin"**
- Scegli: `jspm-ctt-integration-1.1.0.zip`
- Clicca: **"Installa Ora"** → **"Attiva"**

### 3️⃣ Configura
- Vai su: **CTT Correio → Impostazioni**
- Inserisci i tuoi dati CTT
- Salva

**FATTO! Il plugin è pronto all'uso! 🎉**

---

## 📋 CONTENUTO DEL PACCHETTO

Il plugin include tutti i file necessari:

✅ **File Principali:**
- `jspm-ctt-integration.php` - Core del plugin
- `readme.txt` - Documentazione WordPress
- `uninstall.php` - Pulizia dati
- `index.php` - Protezione directory

✅ **Assets (CSS + JavaScript):**
- `assets/css/admin.css` - Stili interfaccia admin
- `assets/css/modal.css` - Stili modal configurazione
- `assets/js/admin.js` - Logica JavaScript principale
- `assets/js/JSPrintManager.js` - Libreria stampa (Neodynamic)

✅ **Classi PHP:**
- `includes/class-file-generator.php` - Generazione file CTT
- `includes/class-label-printer.php` - Gestione stampa etichette

✅ **Templates:**
- `templates/admin-page.php` - Pagina gestione ordini
- `templates/settings-page.php` - Pagina impostazioni

✅ **Protezioni di Sicurezza:**
- File `index.php` in ogni directory per prevenire listing
- Check `ABSPATH` in tutti i file PHP
- Sanitizzazione input/output
- Nonce per AJAX

---

## 🔧 PRIMA CONFIGURAZIONE

Dopo l'installazione, configura questi parametri obbligatori:

### Impostazioni CTT (OBBLIGATORIO)
```
CTT Correio → Impostazioni
```

1. **Numero Cliente CTT**: Es. 123456
2. **Numero Contratto**: Es. 789012  
3. **Codice Cliente**: Es. 123456
4. **Stampante**: Seleziona dalla lista

### Dati Mittente (OBBLIGATORIO)
- Nome azienda
- Indirizzo completo
- CAP, Città, Paese
- Telefono, Email

### JSPrintManager (per stampa automatica)
1. Scarica: https://www.neodynamic.com/downloads/jspm/
2. Installa l'app sul computer
3. Avvia e tieni in esecuzione

---

## 🎮 COME USARE IL PLUGIN

### Workflow Completo:

1. **Apri:** CTT Correio (menu laterale WordPress)

2. **Seleziona ordini:**
   - Spunta gli ordini da processare
   - Oppure "Seleziona tutti"

3. **Scegli modalità:**
   - 🎨 Colori (standard)
   - ⚫ Bianco e Nero (risparmio)

4. **Clicca pulsante:**
   - 📥 "Genera File CTT" → solo file .txt
   - 🖨️ "Stampa Etichette" → solo stampa
   - ✅ "Genera e Stampa" → **COMPLETO (raccomandato)**

5. **Configura nel modal:**
   - ⚖️ **Peso** (obbligatorio): Es. 0.5 per 500g
   - 📄 **Formato**: Documento (buste) o Pacco (scatole)
   - 🌍 **Destinazione**: PT (nazionale) o Estero
   - 📦 **Prodotto CTT**: C01, C02, C13...

6. **Applica valori comuni:**
   - Usa "Applica a tutti" per settare gli stessi valori su tutti gli ordini

7. **Conferma:**
   - Il sistema genera i file e stampa automaticamente!

---

## ✨ FUNZIONALITÀ PRINCIPALI

✅ **Interfaccia Intuitiva**
- Gestisci tutti gli ordini da un'unica schermata
- Filtri per stato ordine
- Selezione multipla o singola

✅ **Configurazione Flessibile**
- Peso personalizzabile per ordine
- Formato: Documento o Pacco
- Destinazione: Nazionale o Internazionale
- Prodotto CTT selezionabile

✅ **Stampa Automatica**
- Integrazione JSPrintManager
- Stampa diretta su stampante locale
- Modalità colori o bianco/nero

✅ **Applicazione Massiva**
- Imposta valori comuni su tutti gli ordini
- Risparmio di tempo
- Meno errori

✅ **Meta Box Ordini**
- Stampa etichetta dalla pagina ordine singolo
- Scarica file CTT per ordine
- Visualizza tracking number

✅ **Bulk Actions**
- Azioni di massa da lista ordini WooCommerce
- Integrazione nativa

---

## 🔍 VERIFICA INSTALLAZIONE

Dopo l'attivazione, verifica:

- [ ] Menu "CTT Correio" visibile (icona stampante)
- [ ] Plugin attivo senza errori
- [ ] Pagina gestione ordini accessibile
- [ ] Impostazioni accessibili e salvabili
- [ ] Console browser senza errori (F12)

Se tutto OK → **Plugin funzionante! ✅**

---

## 🐛 TROUBLESHOOTING

### Menu CTT non visibile
**Soluzione:** Verifica che WooCommerce sia attivo e utente abbia permesso `manage_woocommerce`

### Stampa non funziona
**Soluzione:** 
1. Verifica JSPrintManager Client App in esecuzione
2. Controlla console browser (F12)
3. Verifica stampante in Impostazioni

### Ordini non caricano
**Soluzione:**
1. Verifica presenza ordini nello stato selezionato
2. Cambia filtro stato
3. Controlla console per errori AJAX

### Peso mancante
**Soluzione:**
1. Configura peso prodotti WooCommerce
2. O inserisci manualmente nel modal

---

## 📊 REQUISITI SISTEMA

### Server (WordPress)
- ✅ WordPress 5.8 o superiore
- ✅ WooCommerce 5.0 o superiore
- ✅ PHP 8.0 o superiore (testato fino a 8.3)
- ✅ MySQL 5.7 o superiore

### Browser
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Edge 90+
- ✅ Safari 14+

### Client (per stampa)
- ✅ JSPrintManager Client App
- ✅ Stampante configurata
- ✅ Windows, macOS o Linux

---

## 🔐 SICUREZZA

Il plugin implementa:

✅ Protezione accesso diretto file (`ABSPATH`)  
✅ Sanitizzazione input utente  
✅ Escape output  
✅ Nonce per richieste AJAX  
✅ Capability checks (`manage_woocommerce`)  
✅ File `index.php` in tutte le directory  
✅ Prepared statements per query database  

---

## 📝 NOTE IMPORTANTI

⚠️ **ATTENZIONE:**
- Il plugin richiede **account CTT Correio Azul attivo**
- La stampa automatica richiede **JSPrintManager Client App**
- **Configura sempre** peso, formato e destinazione
- **Testa su staging** prima della produzione

✅ **RACCOMANDAZIONI:**
- Fai backup prima di installare
- Testa con pochi ordini all'inizio
- Configura peso default nei prodotti
- Tieni JSPrintManager sempre in esecuzione
- Verifica stampante funzionante

---

## 🆕 CHANGELOG

### v1.1.0 (Corrente)

**Correzioni:**
- ✅ Fix percorso JSPrintManager.js
- ✅ Aggiunto handler `jspm_ctt_get_label_html`
- ✅ Fix condizione caricamento script
- ✅ Supporto pagine ordini singoli

**Miglioramenti:**
- ✅ Gestione errori migliorata
- ✅ Codice ottimizzato
- ✅ Documentazione completa
- ✅ File protezione aggiunti

---

## 📞 SUPPORTO

**Email:** support@cct.pt  
**Repository:** neodynamic/JSPrintManager  
**Documentazione:** Vedi INSTALLAZIONE.md

---

## 📄 LICENZA

GPLv2 or later  
https://www.gnu.org/licenses/gpl-2.0.html

---

## 🎉 PRONTO!

Il plugin **JSPrintManager CTT Integration v1.1.0** è:

✅ Testato  
✅ Verificato  
✅ Pacchettizzato  
✅ Pronto per l'installazione  

**Scarica `jspm-ctt-integration-1.1.0.zip` e installa in WordPress!**

Buon lavoro! 🚀

---

*JSPrintManager CTT Integration v1.1.0*  
*Developed by CCT.PT*  
*Powered by JSPrintManager (Neodynamic)*
