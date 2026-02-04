# Guida Rapida - Plugin CTT per cct.pt

## 🎯 Installazione Veloce

### 1. Carica il plugin in WordPress

```bash
# Copia la cartella wordpress-plugin nel tuo WordPress
cp -r wordpress-plugin /var/www/html/wp-content/plugins/jspm-ctt-integration
```

### 2. Attiva il plugin

WordPress Admin → Plugin → "JSPrintManager CTT Integration" → Attiva

### 3. Configura i dati CTT

**CTT Correio** → **Impostazioni**

Inserisci:
- **Numero Cliente**: 888888888 (o il tuo numero CTT a 9 cifre)
- **Numero Contratto**: 123456789 (il tuo numero contratto CTT)
- **Nome mittente**: Nome tua azienda
- **Indirizzo**: Via, numero, ecc.
- **Codice Postale**: 1000-100
- **Città**: Lisboa (o la tua città)

Clicca **Salva Impostazioni**

## 📦 Uso Quotidiano

### Per stampare etichette da ordini:

1. **CTT Correio** (menu WordPress)
2. Seleziona ordini dalla tabella ☑️
3. Scegli **Bianco/Nero** (risparmia inchiostro) o **Colori**
4. Clicca **"Genera e Stampa"**

✅ Fatto! Il sistema:
- Genera file CTT automaticamente
- Scarica il file CSV
- Stampa le etichette
- Salva i tracking negli ordini

### File generato dove va?

Il file CSV si scarica automaticamente.

**Caricalo su CTT:**
1. Vai su [ctt.pt/empresas](https://www.ctt.pt/empresas) → Area Cliente
2. **Envios** → **Importar Ficheiro**
3. Carica il file CSV
4. Conferma

## 🖨️ Configurare la Stampa

### Prima volta - Installa JSPrintManager:

1. Scarica da: [neodynamic.com/downloads/jspm](https://neodynamic.com/downloads/jspm)
2. Installa sul computer che farà le stampe
3. Avvia l'applicazione (icona nella barra)
4. Configura la stampante predefinita

### Ogni volta che stampi:

1. JSPrintManager deve essere **in esecuzione** (icona in barra)
2. Stampante deve essere **accesa e pronta**
3. Seleziona ordini e clicca "Stampa"

## 🎨 Bianco/Nero vs Colori?

### 🖤 Bianco/Nero
✅ Risparmio inchiostro  
✅ Più veloce  
✅ Perfetto per uso quotidiano  
⚠️ Meno professionale

### 🎨 Colori
✅ Logo CTT azzurro  
✅ Più professionale  
✅ Facile da riconoscere  
⚠️ Usa più inchiostro

**Consiglio**: Usa Bianco/Nero per spedizioni normali, Colori per clienti importanti.

## 🔧 Problemi Comuni

### "JSPrintManager non connesso"
→ Avvia l'app JSPrintManager sul computer

### "Nessuna stampante disponibile"
→ Verifica che la stampante sia accesa e configurata

### "Numero cliente mancante"
→ Vai in Impostazioni e inserisci i dati CTT

### Codice postale errato
→ Il plugin corregge automaticamente. Se continua l'errore, modifica manualmente l'ordine WooCommerce

### File CTT rifiutato
→ Controlla in Impostazioni che Numero Cliente e Contratto siano corretti (9 cifre)

## 📱 Stampa da Ordine Singolo

Apri un ordine WooCommerce → Box "CTT Correio Azul" sulla destra:

- **Stampa Etichetta** = stampa subito questa etichetta
- **Scarica File CTT** = genera file per questo ordine

## 💡 Tips & Tricks

### Stampa veloce ogni mattina:

1. Imposta filtro su "In lavorazione"
2. Clicca "Seleziona tutti" ☑️
3. Bianco/Nero
4. "Genera e Stampa"
5. Carica file su CTT
6. Fatto!

### Evitare duplicati:

Il plugin **NON stampa doppio**. Ogni click stampa una volta.

Se vedi duplicati:
- Problema della stampante (buffer)
- Aspetta che finisca prima di cliccare di nuovo

### Vedere tracking nell'ordine:

Il codice tracking (es. LA000012345PT) viene salvato automaticamente nell'ordine.

Lo vedi nel box "CTT Correio Azul" quando apri l'ordine.

### Personalizzare etichette:

I file sono in: `wp-content/plugins/jspm-ctt-integration/includes/class-label-printer.php`

Puoi modificare:
- Colori (linea 30-32)
- Dimensioni (linea 27)
- Layout HTML (funzione `generate_label_html`)

## 📞 Contatti

**Per problemi CTT**: [ctt.pt/empresas](https://www.ctt.pt/empresas) → Supporto  
**Per problemi plugin**: Controlla WordPress → Strumenti → Salute del sito → Log

## ✅ Checklist Setup Iniziale

- [ ] Plugin installato e attivato
- [ ] JSPrintManager installato sul computer
- [ ] JSPrintManager in esecuzione
- [ ] Stampante configurata e accesa
- [ ] Numero Cliente CTT inserito (9 cifre)
- [ ] Numero Contratto CTT inserito (9 cifre)
- [ ] Dati mittente compilati
- [ ] Test stampa fatto con 1 ordine
- [ ] File CTT caricato su portale CTT con successo

## 🚀 Sei Pronto!

Ora puoi gestire le spedizioni CTT direttamente da WooCommerce!

**Workflow completo:**
1. Cliente fa ordine → WooCommerce
2. Tu selezioni ordini → Plugin CTT
3. Genera file + stampa → Automatico
4. Carica file su CTT → 1 minuto
5. Attacca etichette → Spedisci!

**Tempo risparmiato**: Da 10 minuti per ordine → 30 secondi per tutti gli ordini! 🎉
