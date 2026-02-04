# Plugin WordPress: JSPrintManager CTT Integration

Plugin completo per l'integrazione di WooCommerce con CTT Correio Azul e JSPrintManager per la stampa automatica di etichette di spedizione.

## 🚀 Funzionalità

### ✅ Integrazione CTT Correio Azul
- **Generazione automatica file CSV** nel formato richiesto da CTT
- **Supporto Correio Azul (C01)** - servizio standard con tracking
- **Campi compilati automaticamente** da ordini WooCommerce
- **Codici tracking automatici** formato: LA123456789PT

### ✅ Stampa Etichette con JSPrintManager
- **Stampa diretta** senza dialog - niente più stampe duplicate!
- **Modalità Colori** (standard con logo CTT azzurro)
- **Modalità Bianco/Nero** (risparmio inchiostro)
- **Design professionale** con barcode tracking
- **Supporto stampanti termiche** (ZPL per Zebra)

### ✅ Gestione Ordini WooCommerce
- **Selezione multipla ordini** dalla dashboard
- **Filtro per stato** (in lavorazione, completati, ecc.)
- **Azioni bulk** su ordini selezionati
- **Meta box** in ogni ordine per stampa singola

## 📦 Installazione

### 1. Copia i file del plugin

```bash
cp -r wordpress-plugin /percorso/wordpress/wp-content/plugins/jspm-ctt-integration
```

### 2. Copia JSPrintManager.js

```bash
cp scripts/JSPrintManager.js /percorso/wordpress/wp-content/plugins/jspm-ctt-integration/assets/js/
```

### 3. Attiva il plugin

1. Vai su WordPress Admin > Plugin
2. Cerca "JSPrintManager CTT Integration"
3. Clicca "Attiva"

### 4. Configura le impostazioni

1. Vai su **CTT Correio** > **Impostazioni**
2. Inserisci:
   - Numero Cliente CTT (9 cifre)
   - Numero Contratto CTT (9 cifre)
   - Dati mittente (nome, indirizzo, codice postale, città)
3. Salva

## 🎯 Come Usare

### Metodo 1: Dashboard CTT Correio

1. Vai su **CTT Correio** nel menu WordPress
2. Seleziona gli ordini dalla tabella
3. Scegli modalità stampa (Colori o Bianco/Nero)
4. Clicca **"Genera e Stampa"**

Il sistema:
- ✅ Genera file CSV CTT
- ✅ Scarica automaticamente il file
- ✅ Stampa tutte le etichette
- ✅ Salva codici tracking negli ordini

### Metodo 2: Dalla pagina ordine

1. Apri un ordine WooCommerce
2. Nel box "CTT Correio Azul" a destra:
   - **Stampa Etichetta CTT** - stampa singola etichetta
   - **Scarica File CTT** - genera file per questo ordine

### Metodo 3: Azione Bulk

1. Vai su WooCommerce > Ordini
2. Seleziona più ordini
3. Dal menu "Azioni di gruppo" scegli **"Genera file CTT e stampa"**
4. Clicca "Applica"

## 📋 Formato File CTT

Il plugin genera file CSV compatibili con il portale CTT Empresas secondo le specifiche ufficiali:

### Campi compilati automaticamente:

| Campo | Sorgente | Esempio |
|-------|----------|---------|
| Codice envio | Generato auto | LA000012345PT |
| Nº cliente | Impostazioni plugin | 888888888 |
| Nº contratto | Impostazioni plugin | 123456789 |
| Nome destinatario | WooCommerce Billing | João Silva |
| Morada | WooCommerce Address | Rua Example 123 |
| Código Postal | WooCommerce Postcode | 1000-100 |
| Localidade | WooCommerce City | Lisboa |
| Email | WooCommerce Email | cliente@email.pt |
| Telemóvel | WooCommerce Phone | 00351961234567 |
| Peso (Kg) | Peso prodotti WC | 0,500 |
| Produto | Fisso | C01 (Correio Azul) |
| Referência cliente | Numero ordine | ORD-12345 |

### Importazione su CTT

1. Accedi a [CTT Área de Cliente](https://www.ctt.pt/empresas)
2. Vai su "Envios" > "Importar Ficheiro"
3. Carica il file CSV generato
4. Conferma e crea le spedizioni

## 🖨️ Stampa Etichette

### Formato Etichetta

- **Dimensione**: 10cm x 15cm (standard etichette adesive)
- **Header CTT**: Logo e "CORREIO AZUL"
- **Codice tracking**: Formato grande con barcode
- **Destinatario**: Nome, indirizzo completo, CAP, città, telefono
- **Mittente**: Dati configurati nel plugin
- **Info spedizione**: Peso, data, riferimento ordine

### Modalità di Stampa

#### Colori (Standard)
- Header azzurro CTT
- Codici in grassetto
- Professionale e riconoscibile

#### Bianco/Nero
- Header con bordo nero
- Tutto in bianco/nero
- Risparmio inchiostro
- Perfetto per stampe multiple

### Stampanti Supportate

- **Stampanti laser/inkjet** - tramite HTML/PDF
- **Stampanti termiche Zebra** - tramite comandi ZPL
- **Qualsiasi stampante Windows/Mac/Linux** - con driver installato

## ⚙️ Requisiti

### Server
- WordPress 5.8+
- WooCommerce 5.0+
- PHP 7.4+

### Client (computer per stampa)
- JSPrintManager Client App installata e in esecuzione
- Browser moderno (Chrome, Firefox, Edge, Safari)
- Stampante configurata

### Account CTT
- Account CTT Empresas attivo
- Numero Cliente (9 cifre)
- Numero Contratto (9 cifre)

## 🔧 Configurazione Avanzata

### Personalizzare il prodotto CTT

Nel file `class-file-generator.php` linea 60:

```php
// Cambia C01 con il prodotto desiderato
'product' => 'C01', // C02=Correio Normal, C13=Registado
```

### Personalizzare formato etichetta

Nel file `class-label-printer.php` puoi modificare:
- Dimensioni etichetta (linea 27)
- Colori (linee 30-32)
- Font e stili (sezione `<style>`)

### Aggiungere servizi addizionali CTT

Nel file `class-file-generator.php`, funzione `generate_row()`, dopo la linea 151:

```php
// AB - Serviço adicional 1
$row[] = '4'; // 4 = Contro reembolso

// AC - Dados complementares
$row[] = '50,00'; // Valore a cobrare
```

## 🐛 Risoluzione Problemi

### JSPrintManager non connesso
✅ Verifica che JSPrintManager Client App sia in esecuzione
✅ Controlla la connessione WebSocket (porta 22443)

### File CTT non valido
✅ Verifica di aver configurato Numero Cliente e Contratto
✅ Controlla che gli ordini abbiano indirizzo completo
✅ Verifica formato codice postale (1000-100)

### Stampa non funziona
✅ Controlla che la stampante sia disponibile
✅ Verifica i permessi del browser
✅ Prova con una stampante diversa

### Codici postali non validi
✅ Il plugin formatta automaticamente in formato CTT
✅ Se mancano dati, usa formato manuale: 1000-100

## 📞 Supporto

Per supporto su:
- **CTT Correio Azul**: [ctt.pt/empresas](https://www.ctt.pt/empresas)
- **JSPrintManager**: [neodynamic.com](https://neodynamic.com)
- **Plugin**: Controlla i log WordPress in Strumenti > Salute del sito

## 📝 Note Importanti

### Peso Prodotti
- Configura il peso per ogni prodotto WooCommerce
- Se manca, viene usato peso default di 0,5 kg
- Il peso è in Kg nel file CTT (formato 0,500)

### Codici Tracking
- Formato automatico: **LA** + 9 cifre + **PT**
- Salvati in ogni ordine come meta `_ctt_tracking_number`
- Visibili nel box CTT nella pagina ordine

### Privacy e GDPR
- Dati cliente usati solo per generare spedizioni
- File CSV temporanei cancellati dopo download
- Nessun dato inviato a server esterni

## 🚀 Prossimi Sviluppi

- [ ] Supporto Expresso (EXP01, EXP02)
- [ ] Spedizioni internazionali
- [ ] Servizi addizionali (contro reembolso, valore dichiarato)
- [ ] Integrazione API CTT diretta
- [ ] Tracking automatico stato spedizione
- [ ] Email automatiche con tracking al cliente

## 📄 Licenza

Questo plugin è fornito "as-is" per uso con cct.pt
Basato su JSPrintManager di Neodynamic
