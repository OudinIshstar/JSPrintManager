#!/bin/bash

# Script per pubblicare il plugin su GitHub
# Esegui questo script dopo aver creato il repository su github.com

echo "╔═══════════════════════════════════════════════════════╗"
echo "║  Pubblica JSPrintManager CTT Plugin su GitHub        ║"
echo "╚═══════════════════════════════════════════════════════╝"
echo ""

# Chiedi username GitHub
read -p "Inserisci il tuo username GitHub: " GITHUB_USERNAME

if [ -z "$GITHUB_USERNAME" ]; then
    echo "❌ Username obbligatorio!"
    exit 1
fi

# Nome repository suggerito
REPO_NAME="jspm-ctt-wordpress-plugin"
read -p "Nome repository [$REPO_NAME]: " CUSTOM_REPO_NAME
REPO_NAME=${CUSTOM_REPO_NAME:-$REPO_NAME}

echo ""
echo "📋 Riepilogo:"
echo "   Username: $GITHUB_USERNAME"
echo "   Repository: $REPO_NAME"
echo "   URL: https://github.com/$GITHUB_USERNAME/$REPO_NAME"
echo ""
read -p "Procedere? (y/n): " CONFIRM

if [ "$CONFIRM" != "y" ]; then
    echo "Operazione annullata."
    exit 0
fi

echo ""
echo "⚠️  PRIMA DI CONTINUARE:"
echo ""
echo "1. Vai su: https://github.com/new"
echo "2. Nome repository: $REPO_NAME"
echo "3. Descrizione: Plugin WordPress per CTT Correio Azul con JSPrintManager"
echo "4. Scegli: Pubblico o Privato"
echo "5. NON aggiungere README, .gitignore o licenza (già inclusi)"
echo "6. Clicca 'Create repository'"
echo ""
read -p "Hai creato il repository? (y/n): " REPO_CREATED

if [ "$REPO_CREATED" != "y" ]; then
    echo "Crea prima il repository su GitHub e riprova."
    exit 0
fi

echo ""
echo "🚀 Inizializzo Git e pubblico il codice..."
echo ""

# Vai nella cartella plugin
cd "$(dirname "$0")"

# Verifica se git è già inizializzato
if [ -d ".git" ]; then
    echo "⚠️  Git già inizializzato. Rimuovo..."
    rm -rf .git
fi

# Inizializza git
git init
echo "✓ Git inizializzato"

# Aggiungi tutti i file
git add .
echo "✓ File aggiunti"

# Primo commit
git commit -m "Initial commit - JSPrintManager CTT Integration v1.1.0

- Plugin WordPress per integrazione CTT Correio Azul
- Generazione automatica file CTT
- Stampa etichette tramite JSPrintManager
- Gestione completa ordini WooCommerce
- PHP 8.0+ compatible"
echo "✓ Commit creato"

# Rinomina branch a main
git branch -M main
echo "✓ Branch rinominato a main"

# Aggiungi remote
REPO_URL="https://github.com/$GITHUB_USERNAME/$REPO_NAME.git"
git remote add origin "$REPO_URL"
echo "✓ Remote aggiunto: $REPO_URL"

# Push
echo ""
echo "📤 Push su GitHub..."
if git push -u origin main; then
    echo ""
    echo "╔═══════════════════════════════════════════════════════╗"
    echo "║  ✅ PUBBLICAZIONE COMPLETATA!                         ║"
    echo "╚═══════════════════════════════════════════════════════╝"
    echo ""
    echo "🎉 Il tuo plugin è ora su GitHub!"
    echo ""
    echo "📍 Repository: https://github.com/$GITHUB_USERNAME/$REPO_NAME"
    echo ""
    echo "🔧 Per lavorarci in locale:"
    echo "   git clone https://github.com/$GITHUB_USERNAME/$REPO_NAME.git"
    echo "   cd $REPO_NAME"
    echo ""
    echo "📝 Prossimi passi:"
    echo "   1. Modifica il README-GITHUB.md e rinominalo in README.md"
    echo "   2. Aggiungi screenshot nella cartella .github/"
    echo "   3. Configura GitHub Actions per build automatico (opzionale)"
    echo ""
else
    echo ""
    echo "❌ Errore durante il push!"
    echo ""
    echo "Possibili cause:"
    echo "1. Repository non esistente - crea prima su github.com"
    echo "2. Autenticazione necessaria - configura token GitHub"
    echo "3. URL errato - verifica username e nome repository"
    echo ""
    echo "Per configurare autenticazione GitHub:"
    echo "   gh auth login"
    echo ""
    exit 1
fi
