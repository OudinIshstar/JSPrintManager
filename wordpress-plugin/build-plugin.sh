#!/bin/bash

# Script per creare un pacchetto ZIP installabile del plugin WordPress
# JSPrintManager CTT Integration

PLUGIN_NAME="jspm-ctt-integration"
VERSION="1.1.0"
BUILD_DIR="build"
DIST_DIR="dist"

echo "=================================================="
echo "Building JSPrintManager CTT Integration Plugin"
echo "Version: $VERSION"
echo "=================================================="

# Pulisci directory precedenti
echo "Cleaning previous builds..."
rm -rf "$BUILD_DIR"
rm -rf "$DIST_DIR"
mkdir -p "$BUILD_DIR/$PLUGIN_NAME"
mkdir -p "$DIST_DIR"

# Copia i file necessari
echo "Copying plugin files..."

# File principali
cp jspm-ctt-integration.php "$BUILD_DIR/$PLUGIN_NAME/"
cp readme.txt "$BUILD_DIR/$PLUGIN_NAME/"
cp uninstall.php "$BUILD_DIR/$PLUGIN_NAME/"
cp index.php "$BUILD_DIR/$PLUGIN_NAME/"

# Assets
echo "Copying assets..."
mkdir -p "$BUILD_DIR/$PLUGIN_NAME/assets/css"
mkdir -p "$BUILD_DIR/$PLUGIN_NAME/assets/js"

cp assets/css/*.css "$BUILD_DIR/$PLUGIN_NAME/assets/css/" 2>/dev/null || true
cp assets/css/index.php "$BUILD_DIR/$PLUGIN_NAME/assets/css/"

cp assets/js/*.js "$BUILD_DIR/$PLUGIN_NAME/assets/js/" 2>/dev/null || true
cp assets/js/index.php "$BUILD_DIR/$PLUGIN_NAME/assets/js/"

cp assets/index.php "$BUILD_DIR/$PLUGIN_NAME/assets/"

# Includes
echo "Copying includes..."
mkdir -p "$BUILD_DIR/$PLUGIN_NAME/includes"
cp includes/*.php "$BUILD_DIR/$PLUGIN_NAME/includes/" 2>/dev/null || true

# Templates
echo "Copying templates..."
mkdir -p "$BUILD_DIR/$PLUGIN_NAME/templates"
cp templates/*.php "$BUILD_DIR/$PLUGIN_NAME/templates/" 2>/dev/null || true

# Languages (se esistono)
if [ -d "languages" ]; then
    echo "Copying languages..."
    mkdir -p "$BUILD_DIR/$PLUGIN_NAME/languages"
    cp -r languages/* "$BUILD_DIR/$PLUGIN_NAME/languages/"
fi

# Crea lo ZIP
echo "Creating ZIP archive..."
cd "$BUILD_DIR"
zip -r "../$DIST_DIR/$PLUGIN_NAME-$VERSION.zip" "$PLUGIN_NAME" -x "*.DS_Store" "*.git*"
cd ..

# Verifica
echo ""
echo "=================================================="
echo "Build completed!"
echo "=================================================="
echo "Package: $DIST_DIR/$PLUGIN_NAME-$VERSION.zip"
echo ""

# Mostra dimensione
if [ -f "$DIST_DIR/$PLUGIN_NAME-$VERSION.zip" ]; then
    SIZE=$(du -h "$DIST_DIR/$PLUGIN_NAME-$VERSION.zip" | cut -f1)
    echo "Size: $SIZE"
    echo ""
    echo "File structure:"
    unzip -l "$DIST_DIR/$PLUGIN_NAME-$VERSION.zip" | head -30
    echo ""
    echo "=================================================="
    echo "✓ Plugin ready for installation!"
    echo ""
    echo "To install:"
    echo "1. Go to WordPress Admin > Plugins > Add New"
    echo "2. Click 'Upload Plugin'"
    echo "3. Choose: $DIST_DIR/$PLUGIN_NAME-$VERSION.zip"
    echo "4. Click 'Install Now' and then 'Activate'"
    echo "=================================================="
else
    echo "ERROR: Failed to create ZIP file"
    exit 1
fi
