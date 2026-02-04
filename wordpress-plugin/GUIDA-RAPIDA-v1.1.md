# 🚀 Guida Rapida Aggiornata v1.1 - Plugin CTT per cct.pt

## ✨ NUOVO! Funzionalità Aggiunte

### ✅ Configurazione Peso Manuale
Non hai configurato i pesi nei prodotti WooCommerce? Nessun problema!
Ora puoi inserire il peso per ogni ordine al momento della spedizione.

### ✅ Scelta Formato Spedizione
- 📄 **Documento** (buste, lettere) - Formato normalizzato
- 📦 **Pacco** (scatole) - Pacote Postal

### ✅ Destinazioni Nazionali/Internazionali
- 🇵🇹 **Nazionale**: Portogallo (PT)
- 🌍 **Internazionale**: Spagna, Francia, Italia, Germania, UK, USA, etc.

### ✅ Selezione Prodotto CTT
- **C01** - Correio Azul (default, con tracking)
- **C02** - Correio Normal
- **C13** - Correio Registado
- **C14** - Correio Registado Simples

## 📦 Workflow Completo (Nuovo)

### 1. Seleziona Ordini
Vai su **CTT Correio** nel menu WordPress e seleziona gli ordini da spedire ☑️

### 2. Scegli Modalità Stampa
- **Colori** 🎨 - Logo CTT azzurro (professionale)
- **Bianco/Nero** 🖤 - Risparmio inchiostro (consigliato)

### 3. Clicca su un Pulsante
- **Genera File CTT** - Solo file CSV
- **Stampa Etichette** - Solo stampa
- **Genera e Stampa** - Entrambi (consigliato!)

### 4. ⭐ NUOVO: Configura Spedizioni

Si aprirà un **modal di configurazione** per ogni ordine selezionato:

```
┌─────────────────────────────────────────┐
│  Configura Spedizioni (5 ordini)        │
├─────────────────────────────────────────┤
│                                          │
│  Applica a tutti:                        │
│  [Peso 0.5]  [📄 Documento]  [🇵🇹 PT]   │
│  [Correio Azul]  [Applica]               │
│                                          │
│  ┌────────────────────────────────────┐ │
│  │ Ordine #12345  🇵🇹 Nazionale       │ │
│  ├────────────────────────────────────┤ │
│  │ Peso (kg): [0.500] *obbligatorio   │ │
│  │ Formato: [📄 Documento ▼]          │ │
│  │ Destinazione: [🇵🇹 Portogallo ▼]  │ │
│  │ Prodotto: [C01 Correio Azul ▼]    │ │
│  └────────────────────────────────────┘ │
│                                          │
│  [Ordine #12346...]                      │
│  [Ordine #12347...]                      │
│                                          │
│  [Annulla]  [Conferma e Procedi]        │
└─────────────────────────────────────────┘
```

#### Compilazione Veloce con "Applica a tutti":

1. **Stesso peso per tutti?** Inserisci es: `0.5` e clicca "Applica"
2. **Tutti documenti?** Seleziona `📄 Documento` e clicca "Applica"
3. **Tutti nazionali?** Lascia `🇵🇹 PT` (default)
4. **Tutti Correio Azul?** Lascia `C01` (default)

#### Configurazione Individuale:

Per ordini con pesi/destinazioni diverse, modifica ogni campo singolarmente.

### 5. Conferma

Clicca **"Conferma e Procedi"** e il sistema:
1. ✅ Salva le configurazioni
2. ✅ Genera file CSV CTT
3. ✅ Scarica il file automaticamente
4. ✅ Stampa tutte le etichette
5. ✅ Salva tracking in ogni ordine

### 6. Carica su CTT

Vai su [ctt.pt/empresas](https://www.ctt.pt/empresas) → **Importar Ficheiro** → Carica il CSV

## 🎯 Esempi Pratici

### Caso 1: Tutti ordini nazionali, stesso peso

1. Seleziona 10 ordini
2. Clicca "Genera e Stampa"
3. Nel modal: "Applica a tutti" → Peso: `0.5` → Formato: `📄 Documento` → Applica
4. Conferma
5. ✅ Fatto in 30 secondi!

### Caso 2: Ordini misti (nazionali e internazionali)

1. Seleziona ordini
2. Clicca "Genera e Stampa"
3. Nel modal:
   - Ordine Italia: Peso `0.5` → Destinazione `🇮🇹 IT`
   - Ordine Portogallo: Peso `1.2` → Destinazione `🇵🇹 PT`
   - Ordine Spagna: Peso `0.8` → Destinazione `🇪🇸 ES`
4. Conferma
5. ✅ File generato con spedizioni nazionali + internazionali

### Caso 3: Pacchi pesanti

1. Seleziona ordini pacchi
2. Clicca "Genera File CTT"
3. "Applica a tutti" → Peso: `2.5` → Formato: `📦 Pacco` → Applica
4. Conferma
5. ✅ File CTT con formato "Pacote Postal"

## 📋 Campi Spiegati

### Peso (Obbligatorio)
- Inserisci peso in **kilogrammi**
- Esempi:
  - `0.100` = 100 grammi
  - `0.500` = 500 grammi (500g)
  - `1.250` = 1 kg e 250 grammi
  - `2.000` = 2 kg

### Formato
- **📄 Documento (1)**: Per buste, lettere fino a 2kg
- **📦 Pacco (3)**: Per scatole, pacchi, oggetti voluminosi
- **Non normalizado (2)**: Formati speciali
- **Solo documenti registrati (4)**: Solo per Correio Registado

### Destinazione
- **🇵🇹 PT**: Portogallo (nazionale)
- **🇪🇸 ES**: Spagna
- **🇫🇷 FR**: Francia
- **🇮🇹 IT**: Italia
- **🇩🇪 DE**: Germania
- **🇬🇧 UK**: Regno Unito
- **🇺🇸 US**: USA
- Altri paesi disponibili nel menu

### Prodotto CTT
- **C01 - Correio Azul**: Standard con tracking (consigliato)
- **C02 - Correio Normal**: Economico senza tracking
- **C13 - Correio Registado**: Registrato con avviso di ricezione
- **C14 - Correio Registado Simples**: Registrato base

## ⚙️ Impostazioni Iniziali

Se è la prima volta:

1. **WordPress Admin** → **CTT Correio** → **Impostazioni**
2. Inserisci:
   - Numero Cliente CTT (9 cifre)
   - Numero Contratto CTT (9 cifre)
   - Nome mittente
   - Indirizzo completo
   - Codice postale (formato: 1000-100)
   - Città
3. **Salva Impostazioni**

## 🔧 Requisiti

### Server
- ✅ WordPress 5.8+
- ✅ WooCommerce 5.0+ (testato fino a 9.5)
- ✅ PHP 7.4+

### Client (Computer per Stampa)
- ✅ JSPrintManager App installata e in esecuzione
- ✅ Browser moderno
- ✅ Stampante configurata

## 💡 Tips & Tricks

### Risparmia Tempo
Usa sempre **"Applica a tutti"** quando gli ordini hanno caratteristiche simili.

### Peso Non Configurato in WooCommerce?
Non serve! Ora lo inserisci direttamente qui al momento della spedizione.

### Stampa Bianco/Nero
Usa sempre B/N per uso quotidiano. Risparmia fino al 70% di inchiostro!

### Tracking Automatico
I codici tracking vengono salvati automaticamente:
- **LA** + 9 cifre + **PT** = Correio Azul
- **RA** + 9 cifre + **PT** = Correio Registado
- **CA** + 9 cifre + **PT** = Encomenda Postal

### Destinazioni Comuni
- Italia → **IT**
- Spagna → **ES**
- Francia → **FR**
- Germania → **DE**

## 🆘 Problemi Comuni

### "Peso obbligatorio per ordine #123"
→ Il peso è sempre obbligatorio. Inserisci un valore (es: 0.5 per 500g)

### Modal non si apre
→ Ricarica la pagina (F5) e riprova

### "Nessun ordine selezionato"
→ Seleziona almeno un ordine dalla tabella prima di cliccare i pulsanti

### File CTT rifiutato da portale
→ Controlla in Impostazioni che Numero Cliente e Contratto siano corretti (9 cifre ciascuno)

## ✅ Checklist Rapida

Prima di usare il plugin:
- [ ] Plugin installato e attivato
- [ ] JSPrintManager installato sul computer
- [ ] JSPrintManager in esecuzione (icona in barra)
- [ ] Stampante accesa e configurata
- [ ] Dati CTT configurati (Cliente + Contratto)
- [ ] Dati mittente completi
- [ ] Test con 1 ordine fatto ✓

## 🚀 Workflow Quotidiano

**Ogni mattina (5 minuti):**

1. WordPress → CTT Correio
2. Seleziona tutti ordini "In lavorazione"
3. Bianco/Nero
4. "Genera e Stampa"
5. "Applica a tutti" → Peso: 0.5 (o quello medio) → Applica
6. Conferma
7. Carica file su CTT
8. Attacca etichette
9. ✅ Spedisci!

**Tempo totale: ~5 minuti per tutti gli ordini del giorno!**

## 📞 Supporto

**Plugin**: Controlla log in WordPress → Strumenti → Salute del sito  
**CTT**: [ctt.pt/empresas](https://www.ctt.pt/empresas) → Supporto  
**JSPrintManager**: [neodynamic.com/support](https://neodynamic.com/support)

---

## 🎉 Novità v1.1

✅ Campo peso manuale per ogni ordine  
✅ Selezione formato documento/pacco  
✅ Supporto spedizioni internazionali  
✅ Prodotto CTT selezionabile  
✅ Modal configurazione intuitivo  
✅ Funzione "Applica a tutti" per velocità  
✅ Compatibilità WooCommerce 9.x  
✅ Salvataggio automatico configurazioni

**Nessun peso configurato in WooCommerce? Nessun problema!** 🎉
