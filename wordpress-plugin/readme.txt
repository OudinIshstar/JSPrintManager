=== JSPrintManager CTT Integration ===
Contributors: cctpt
Tags: woocommerce, shipping, printing, ctt, correio azul, portugal, labels
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 8.0
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 5.0
WC tested up to: 9.5

Integrazione WooCommerce con CTT Correio Azul e JSPrintManager per stampa automatica etichette di spedizione.

== Description ==

Plugin per gestire le spedizioni CTT Correio Azul direttamente da WooCommerce. Permette di:

* Selezionare ordini da elaborare
* Configurare peso, formato, destinazione per ogni ordine
* Generare file CTT (.txt) con i dati delle spedizioni
* Stampare automaticamente etichette tramite JSPrintManager
* Gestire stampa a colori o in bianco e nero
* Applicazione rapida delle impostazioni a tutti gli ordini

= Caratteristiche principali =

* **Interfaccia intuitiva**: Gestisci tutti gli ordini da un'unica schermata
* **Configurazione flessibile**: Imposta peso, formato (documento/pacco), destinazione (PT/estero), prodotto CTT
* **Stampa automatica**: Integrazione diretta con JSPrintManager per stampa immediata delle etichette
* **Applicazione massiva**: Applica le stesse impostazioni a tutti gli ordini selezionati con un click
* **Modalità stampa**: Scegli tra stampa a colori o bianco e nero
* **Meta box ordini**: Stampa etichetta direttamente dalla pagina ordine

= Requisiti =

* WooCommerce 5.0 o superiore
* JSPrintManager Client App installata e in esecuzione sul computer
* Stampante configurata
* Account CTT Correio Azul con numero cliente e contratto

== Installation ==

1. Carica la cartella `jspm-ctt-integration` nella directory `/wp-content/plugins/`
2. Attiva il plugin attraverso il menu 'Plugins' in WordPress
3. Configura i dati CTT (numero cliente, contratto, mittente) in CTT Correio > Impostazioni
4. Installa e avvia JSPrintManager Client App sul tuo computer
5. Vai in CTT Correio per gestire le spedizioni

= Installazione JSPrintManager =

1. Scarica JSPrintManager Client App da https://www.neodynamic.com/downloads/jspm/
2. Installa l'applicazione sul computer che gestisce la stampa
3. Avvia JSPrintManager Client App (deve essere sempre in esecuzione)
4. Il plugin si connetterà automaticamente all'applicazione

== Frequently Asked Questions ==

= Devo installare qualcosa sul mio computer? =

Sì, devi installare JSPrintManager Client App sul computer da cui vuoi stampare le etichette. L'applicazione deve essere in esecuzione per permettere la stampa.

= Il plugin funziona senza JSPrintManager? =

Puoi generare i file CTT (.txt) anche senza JSPrintManager, ma la funzionalità di stampa automatica richiede JSPrintManager Client App.

= Posso stampare in bianco e nero? =

Sì, il plugin offre la possibilità di scegliere tra stampa a colori o in bianco e nero per risparmiare inchiostro.

= Come configuro il peso degli ordini? =

Il plugin preleva automaticamente il peso dai prodotti WooCommerce. Se non configurato, puoi inserirlo manualmente per ogni ordine prima della generazione.

= Quali formati di spedizione supporta? =

Il plugin supporta formato Documento (per buste/lettere) e formato Pacco (per scatole/pacchi).

= Posso spedire all'estero? =

Sì, il plugin supporta sia destinazioni nazionali (PT) che internazionali (ES, FR, IT, DE, UK, US, ecc.).

== Screenshots ==

1. Schermata principale di gestione ordini CTT
2. Modal di configurazione ordine (peso, formato, destinazione)
3. Pagina impostazioni con dati CTT
4. Meta box nella pagina ordine

== Changelog ==

= 1.1.0 =
* Corretto percorso caricamento JSPrintManager.js
* Aggiunto handler AJAX per generazione HTML etichette
* Migliorata condizione di caricamento script per supporto pagine ordini
* Aggiunta gestione errori migliorata
* Ottimizzazione codice JavaScript

= 1.0.0 =
* Prima versione pubblica
* Integrazione con CTT Correio Azul
* Generazione file CTT
* Stampa etichette tramite JSPrintManager
* Configurazione ordini (peso, formato, destinazione)
* Applicazione massiva impostazioni
* Modalità stampa colori/bianco e nero

== Upgrade Notice ==

= 1.1.0 =
Correzioni importanti per il caricamento dei file JavaScript. Aggiornamento consigliato.

== Support ==

Per supporto e segnalazione bug, contatta cct.pt o visita il repository del progetto.

== Credits ==

* JSPrintManager by Neodynamic - https://www.neodynamic.com/products/printing/jspm/
