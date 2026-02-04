/**
 * JSPrintManager CTT Integration - Admin JavaScript
 */

(function($) {
    'use strict';

    var JSPMCtt = {
        
        ordersData: {}, // Memorizza dati personalizzati per ogni ordine
        
        init: function() {
            this.bindEvents();
            this.initJSPM();
            this.loadOrders();
        },
        
        bindEvents: function() {
            // Select all checkbox
            $(document).on('change', '#jspm-ctt-select-all', this.toggleSelectAll);
            
            // Order checkboxes
            $(document).on('change', '.jspm-ctt-order-checkbox', this.updateSelectedCount);
            
            // Action buttons - modificati per aprire modal
            $(document).on('click', '.jspm-ctt-generate-file', this.openConfigModal.bind(this));
            $(document).on('click', '.jspm-ctt-print-labels', this.openConfigModal.bind(this));
            $(document).on('click', '.jspm-ctt-generate-and-print', this.openConfigModal.bind(this));
            
            // Modal events
            $(document).on('click', '.jspm-ctt-modal-close, .jspm-ctt-modal-cancel', this.closeModal);
            $(document).on('click', '.jspm-ctt-modal-confirm', this.confirmModal.bind(this));
            $(document).on('click', '.jspm-ctt-quick-apply', this.quickApplyAll);
            
            // Order meta box buttons
            $(document).on('click', '.jspm-ctt-print-label', this.printSingleLabel);
            $(document).on('click', '.jspm-ctt-download-file', this.downloadSingleFile);
            
            // Settings
            $(document).on('click', '.jspm-ctt-save-settings', this.saveSettings);
            
            // Load orders on status change
            $(document).on('change', '#jspm-ctt-order-status', this.loadOrders.bind(this));
        },
        
        initJSPM: function() {
            // Inizializza JSPrintManager
            if (typeof JSPM !== 'undefined') {
                JSPM.JSPrintManager.auto_reconnect = true;
                JSPM.JSPrintManager.start();
                
                JSPM.JSPrintManager.WS.onStatusChanged = function() {
                    if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open) {
                        console.log('JSPrintManager connesso');
                    } else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
                        console.warn('JSPrintManager disconnesso');
                        JSPMCtt.showNotice('JSPrintManager non è connesso. Assicurati che l\'applicazione client sia in esecuzione.', 'error');
                    }
                };
            }
        },
        
        toggleSelectAll: function() {
            var checked = $(this).prop('checked');
            $('.jspm-ctt-order-checkbox').prop('checked', checked);
            JSPMCtt.updateSelectedCount();
        },
        
        updateSelectedCount: function() {
            var count = $('.jspm-ctt-order-checkbox:checked').length;
            $('.jspm-ctt-selected-count').text(count + ' ordini selezionati');
            
            // Enable/disable action buttons
            var hasSelection = count > 0;
            $('.jspm-ctt-generate-file, .jspm-ctt-print-labels, .jspm-ctt-generate-and-print')
                .prop('disabled', !hasSelection);
        },
        
        getSelectedOrderIds: function() {
            var orderIds = [];
            $('.jspm-ctt-order-checkbox:checked').each(function() {
                orderIds.push($(this).val());
            });
            return orderIds;
        },
        
        getPrintMode: function() {
            return $('input[name="print_mode"]:checked').val() || 'color';
        },
        
        loadOrders: function() {
            var status = $('#jspm-ctt-order-status').val() || 'processing';
            var $tbody = $('.jspm-ctt-orders-table tbody');
            
            $tbody.html('<tr><td colspan="6">Caricamento ordini...</td></tr>');
            
            $.ajax({
                url: jspmCttData.ajax_url,
                type: 'POST',
                data: {
                    action: 'jspm_ctt_get_orders',
                    nonce: jspmCttData.nonce,
                    status: status,
                    limit: 100
                },
                success: function(response) {
                    if (response.success) {
                        JSPMCtt.renderOrders(response.data);
                    } else {
                        $tbody.html('<tr><td colspan="6">Errore nel caricamento degli ordini</td></tr>');
                    }
                },
                error: function() {
                    $tbody.html('<tr><td colspan="6">Errore di connessione</td></tr>');
                }
            });
        },
        
        renderOrders: function(orders) {
            var $tbody = $('.jspm-ctt-orders-table tbody');
            $tbody.empty();
            
            if (orders.length === 0) {
                $tbody.html('<tr><td colspan="6">Nessun ordine trovato</td></tr>');
                return;
            }
            
            orders.forEach(function(order) {
                var row = '<tr>' +
                    '<td class="order-checkbox"><input type="checkbox" class="jspm-ctt-order-checkbox" value="' + order.id + '"></td>' +
                    '<td class="order-id">#' + order.number + '</td>' +
                    '<td>' + order.date + '</td>' +
                    '<td>' + order.customer + '</td>' +
                    '<td>' + order.total + '</td>' +
                    '<td><span class="order-status ' + order.status + '">' + order.status + '</span></td>' +
                    '</tr>';
                $tbody.append(row);
            });
            
            JSPMCtt.updateSelectedCount();
        },
        
        openConfigModal: function(e) {
            e.preventDefault();
            
            var orderIds = JSPMCtt.getSelectedOrderIds();
            if (orderIds.length === 0) {
                JSPMCtt.showNotice('Seleziona almeno un ordine', 'error');
                return;
            }
            
            // Salva azione cliccata
            JSPMCtt.currentAction = $(e.currentTarget).hasClass('jspm-ctt-generate-file') ? 'generate' :
                                     $(e.currentTarget).hasClass('jspm-ctt-print-labels') ? 'print' : 'both';
            
            // Carica dati ordini esistenti
            $.ajax({
                url: jspmCttData.ajax_url,
                type: 'POST',
                data: {
                    action: 'jspm_ctt_get_order_data',
                    nonce: jspmCttData.nonce,
                    order_ids: orderIds
                },
                success: function(response) {
                    if (response.success) {
                        JSPMCtt.ordersData = response.data;
                        JSPMCtt.renderModal(orderIds);
                    }
                }
            });
        },
        
        renderModal: function(orderIds) {
            var modalHtml = '<div class="jspm-ctt-modal show">' +
                '<div class="jspm-ctt-modal-content">' +
                '<div class="jspm-ctt-modal-header">' +
                '<h2><span class="dashicons dashicons-admin-settings"></span> Configura Spedizioni (' + orderIds.length + ' ordini)</h2>' +
                '<button class="jspm-ctt-modal-close">&times;</button>' +
                '</div>' +
                '<div class="jspm-ctt-modal-body">' +
                '<div class="jspm-ctt-quick-fill">' +
                '<label>Applica a tutti:</label>' +
                '<input type="number" id="quick-weight" placeholder="Peso (kg)" step="0.001" min="0.001" style="width:120px">' +
                '<select id="quick-format">' +
                '<option value="1">📄 Documento</option>' +
                '<option value="3">📦 Pacco</option>' +
                '</select>' +
                '<select id="quick-destination">' +
                '<option value="PT">🇵🇹 Nazionale (PT)</option>' +
                '<option value="ES">🇪🇸 Spagna</option>' +
                '<option value="FR">🇫🇷 Francia</option>' +
                '<option value="IT">🇮🇹 Italia</option>' +
                '<option value="DE">🇩🇪 Germania</option>' +
                '<option value="UK">🇬🇧 Regno Unito</option>' +
                '<option value="US">🇺🇸 USA</option>' +
                '</select>' +
                '<select id="quick-product">' +
                '<option value="C01">Correio Azul</option>' +
                '<option value="C02">Correio Normal</option>' +
                '<option value="C13">Correio Registado</option>' +
                '</select>' +
                '<button class="jspm-ctt-quick-apply">Applica</button>' +
                '</div>' +
                '<div class="jspm-ctt-orders-config">';
            
            // Genera form per ogni ordine
            orderIds.forEach(function(orderId) {
                var orderData = JSPMCtt.ordersData[orderId] || {};
                var weight = orderData.weight || '';
                var format = orderData.format || '1';
                var destination = orderData.destination || 'PT';
                var product = orderData.product || 'C01';
                
                var destBadge = destination === 'PT' ? 
                    '<span class="jspm-ctt-destination-badge national">Nazionale</span>' :
                    '<span class="jspm-ctt-destination-badge international">Internazionale</span>';
                
                modalHtml += '<div class="jspm-ctt-order-config" data-order-id="' + orderId + '">' +
                    '<div class="jspm-ctt-order-config-header">' +
                    '<h3>Ordine #' + orderId + destBadge + '</h3>' +
                    '</div>' +
                    '<div class="jspm-ctt-order-config-fields">' +
                    '<div class="jspm-ctt-field">' +
                    '<label>Peso (kg) *</label>' +
                    '<input type="number" name="weight_' + orderId + '" value="' + weight + '" step="0.001" min="0.001" placeholder="0.500" required>' +
                    '<small>Es: 0.5 per 500g</small>' +
                    '</div>' +
                    '<div class="jspm-ctt-field">' +
                    '<label>Formato</label>' +
                    '<select name="format_' + orderId + '">' +
                    '<option value="1"' + (format === '1' ? ' selected' : '') + '>📄 Documento (busta)</option>' +
                    '<option value="3"' + (format === '3' ? ' selected' : '') + '>📦 Pacco</option>' +
                    '<option value="2"' + (format === '2' ? ' selected' : '') + '>Non normalizado</option>' +
                    '<option value="4"' + (format === '4' ? ' selected' : '') + '>Solo documenti registrati</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="jspm-ctt-field">' +
                    '<label>Destinazione</label>' +
                    '<select name="destination_' + orderId + '">' +
                    '<option value="PT"' + (destination === 'PT' ? ' selected' : '') + '>🇵🇹 Portogallo</option>' +
                    '<option value="ES"' + (destination === 'ES' ? ' selected' : '') + '>🇪🇸 Spagna</option>' +
                    '<option value="FR"' + (destination === 'FR' ? ' selected' : '') + '>🇫🇷 Francia</option>' +
                    '<option value="IT"' + (destination === 'IT' ? ' selected' : '') + '>🇮🇹 Italia</option>' +
                    '<option value="DE"' + (destination === 'DE' ? ' selected' : '') + '>🇩🇪 Germania</option>' +
                    '<option value="UK"' + (destination === 'UK' ? ' selected' : '') + '>🇬🇧 UK</option>' +
                    '<option value="US"' + (destination === 'US' ? ' selected' : '') + '>🇺🇸 USA</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="jspm-ctt-field">' +
                    '<label>Prodotto CTT</label>' +
                    '<select name="product_' + orderId + '">' +
                    '<option value="C01"' + (product === 'C01' ? ' selected' : '') + '>C01 - Correio Azul</option>' +
                    '<option value="C02"' + (product === 'C02' ? ' selected' : '') + '>C02 - Correio Normal</option>' +
                    '<option value="C13"' + (product === 'C13' ? ' selected' : '') + '>C13 - Correio Registado</option>' +
                    '<option value="C14"' + (product === 'C14' ? ' selected' : '') + '>C14 - Registado Simples</option>' +
                    '</select>' +
                    '<small class="jspm-ctt-product-info">Standard: Correio Azul</small>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            });
            
            modalHtml += '</div></div>' +
                '<div class="jspm-ctt-modal-footer">' +
                '<button class="jspm-ctt-btn jspm-ctt-btn-secondary jspm-ctt-modal-cancel">Annulla</button>' +
                '<button class="jspm-ctt-btn jspm-ctt-btn-primary jspm-ctt-modal-confirm">Conferma e Procedi</button>' +
                '</div>' +
                '</div>' +
                '</div>';
            
            $('body').append(modalHtml);
        },
        
        quickApplyAll: function() {
            var weight = $('#quick-weight').val();
            var format = $('#quick-format').val();
            var destination = $('#quick-destination').val();
            var product = $('#quick-product').val();
            
            if (weight) {
                $('[name^="weight_"]').val(weight);
            }
            if (format) {
                $('[name^="format_"]').val(format);
            }
            if (destination) {
                $('[name^="destination_"]').val(destination);
                // Aggiorna badge
                $('.jspm-ctt-order-config').each(function() {
                    var badge = $(this).find('.jspm-ctt-destination-badge');
                    if (destination === 'PT') {
                        badge.removeClass('international').addClass('national').text('Nazionale');
                    } else {
                        badge.removeClass('national').addClass('international').text('Internazionale');
                    }
                });
            }
            if (product) {
                $('[name^="product_"]').val(product);
            }
            
            JSPMCtt.showNotice('Valori applicati a tutti gli ordini!', 'success');
        },
        
        closeModal: function() {
            $('.jspm-ctt-modal').remove();
        },
        
        confirmModal: function() {
            // Valida e raccoglie dati
            var ordersData = {};
            var hasErrors = false;
            
            $('.jspm-ctt-order-config').each(function() {
                var orderId = $(this).data('order-id');
                var weight = $('[name="weight_' + orderId + '"]').val();
                
                if (!weight || parseFloat(weight) <= 0) {
                    JSPMCtt.showNotice('Peso obbligatorio per ordine #' + orderId, 'error');
                    hasErrors = true;
                    return false;
                }
                
                ordersData[orderId] = {
                    weight: weight,
                    format: $('[name="format_' + orderId + '"]').val(),
                    destination: $('[name="destination_' + orderId + '"]').val(),
                    product: $('[name="product_' + orderId + '"]').val()
                };
            });
            
            if (hasErrors) {
                return;
            }
            
            // Salva dati e procedi con azione
            JSPMCtt.ordersData = ordersData;
            JSPMCtt.closeModal();
            
            // Esegui azione originale
            if (JSPMCtt.currentAction === 'generate') {
                JSPMCtt.generateFile();
            } else if (JSPMCtt.currentAction === 'print') {
                JSPMCtt.printLabels();
            } else {
                JSPMCtt.generateAndPrint();
            }
        },
        
        generateFile: function(e) {
            if (e) e.preventDefault();
            
            var orderIds = JSPMCtt.getSelectedOrderIds();
            if (orderIds.length === 0) {
                JSPMCtt.showNotice('Seleziona almeno un ordine', 'error');
                return;
            }
            
            var $btn = $('.jspm-ctt-generate-file');
            $btn.prop('disabled', true).html('<span class="jspm-ctt-loading"></span> Generazione in corso...');
            
            $.ajax({
                url: jspmCttData.ajax_url,
                type: 'POST',
                data: {
                    action: 'jspm_ctt_generate_file',
                    nonce: jspmCttData.nonce,
                    order_ids: orderIds,
                    orders_data: JSPMCtt.ordersData,
                    print_mode: JSPMCtt.getPrintMode()
                },
                success: function(response) {
                    if (response.success) {
                        JSPMCtt.showNotice('File CTT generato con successo! ' + response.data.rows + ' spedizioni.', 'success');
                        
                        // Download file
                        window.open(response.data.file_url, '_blank');
                    } else {
                        JSPMCtt.showNotice('Errore: ' + response.data, 'error');
                    }
                },
                error: function() {
                    JSPMCtt.showNotice('Errore di connessione', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-download"></span> Genera File CTT');
                }
            });
        },
        
        printLabels: function(e) {
            if (e) e.preventDefault();
            
            if (typeof JSPM === 'undefined') {
                JSPMCtt.showNotice('JSPrintManager non è disponibile', 'error');
                return;
            }
            
            var orderIds = JSPMCtt.getSelectedOrderIds();
            if (orderIds.length === 0) {
                JSPMCtt.showNotice('Seleziona almeno un ordine', 'error');
                return;
            }
            
            var $btn = $('.jspm-ctt-print-labels');
            $btn.prop('disabled', true).html('<span class="jspm-ctt-loading"></span> Stampa in corso...');
            
            // Ottieni stampante selezionata
            JSPM.JSPrintManager.getPrinters().then(function(printers) {
                if (printers.length === 0) {
                    JSPMCtt.showNotice('Nessuna stampante disponibile', 'error');
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-printer"></span> Stampa Etichette');
                    return;
                }
                
                // Usa prima stampante disponibile o quella selezionata
                var printer = new JSPM.InstalledPrinter(printers[0]);
                
                // Genera file prima per ottenere i dati di stampa
                $.ajax({
                    url: jspmCttData.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'jspm_ctt_generate_file',
                        nonce: jspmCttData.nonce,
                        order_ids: orderIds,
                        orders_data: JSPMCtt.ordersData,
                        print_mode: JSPMCtt.getPrintMode()
                    },
                    success: function(response) {
                        if (response.success && response.data.print_data) {
                            // Stampa ogni etichetta
                            JSPMCtt.printMultipleLabels(response.data.print_data, printer);
                        }
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('<span class="dashicons dashicons-printer"></span> Stampa Etichette');
                    }
                });
            });
        },
        
        printMultipleLabels: function(printData, printer) {
            var printMode = JSPMCtt.getPrintMode();
            var printed = 0;
            
            printData.forEach(function(data, index) {
                setTimeout(function() {
                    JSPMCtt.printLabel(data.order_id, printer, printMode);
                    printed++;
                    
                    if (printed === printData.length) {
                        JSPMCtt.showNotice('Stampa di ' + printed + ' etichette completata!', 'success');
                    }
                }, index * 1000); // Aspetta 1 secondo tra ogni stampa
            });
        },
        
        printLabel: function(orderId, printer, printMode) {
            // Genera HTML dell'etichetta
            $.ajax({
                url: jspmCttData.ajax_url,
                type: 'POST',
                data: {
                    action: 'jspm_ctt_get_label_html',
                    nonce: jspmCttData.nonce,
                    order_id: orderId,
                    print_mode: printMode
                },
                success: function(response) {
                    if (response.success) {
                        var cpj = new JSPM.ClientPrintJob();
                        cpj.clientPrinter = printer;
                        
                        // Crea file HTML
                        var htmlFile = new JSPM.PrintFile(
                            response.data.html,
                            JSPM.FileSourceType.Text,
                            'label_' + orderId + '.html',
                            1
                        );
                        
                        cpj.files.push(htmlFile);
                        cpj.sendToClient();
                    }
                }
            });
        },
        
        generateAndPrint: function(e) {
            if (e) e.preventDefault();
            
            var orderIds = JSPMCtt.getSelectedOrderIds();
            if (orderIds.length === 0) {
                JSPMCtt.showNotice('Seleziona almeno un ordine', 'error');
                return;
            }
            
            var $btn = $('.jspm-ctt-generate-and-print');
            $btn.prop('disabled', true).html('<span class="jspm-ctt-loading"></span> Elaborazione in corso...');
            
            // Prima genera il file
            $.ajax({
                url: jspmCttData.ajax_url,
                type: 'POST',
                data: {
                    action: 'jspm_ctt_generate_file',
                    nonce: jspmCttData.nonce,
                    order_ids: orderIds,
                    orders_data: JSPMCtt.ordersData,
                    print_mode: JSPMCtt.getPrintMode()
                },
                success: function(response) {
                    if (response.success) {
                        // Download file
                        window.open(response.data.file_url, '_blank');
                        
                        // Poi stampa le etichette
                        setTimeout(function() {
                            $('.jspm-ctt-print-labels').trigger('click');
                        }, 1000);
                    } else {
                        JSPMCtt.showNotice('Errore: ' + response.data, 'error');
                    }
                },
                error: function() {
                    JSPMCtt.showNotice('Errore di connessione', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-yes"></span> Genera e Stampa');
                }
            });
        },
        
        printSingleLabel: function(e) {
            e.preventDefault();
            var orderId = $(this).data('order-id');
            
            if (typeof JSPM === 'undefined') {
                alert('JSPrintManager non è disponibile');
                return;
            }
            
            var $btn = $(this);
            $btn.prop('disabled', true).html('<span class="jspm-ctt-loading"></span> Stampa...');
            
            JSPM.JSPrintManager.getPrinters().then(function(printers) {
                if (printers.length === 0) {
                    alert('Nessuna stampante disponibile');
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-printer"></span> Stampa Etichetta CTT');
                    return;
                }
                
                var printer = new JSPM.InstalledPrinter(printers[0]);
                JSPMCtt.printLabel(orderId, printer, 'color');
                
                setTimeout(function() {
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-printer"></span> Stampa Etichetta CTT');
                }, 2000);
            });
        },
        
        downloadSingleFile: function(e) {
            e.preventDefault();
            var orderId = $(this).data('order-id');
            
            var $btn = $(this);
            $btn.prop('disabled', true);
            
            $.ajax({
                url: jspmCttData.ajax_url,
                type: 'POST',
                data: {
                    action: 'jspm_ctt_generate_file',
                    nonce: jspmCttData.nonce,
                    order_ids: [orderId]
                },
                success: function(response) {
                    if (response.success) {
                        window.open(response.data.file_url, '_blank');
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        },
        
        saveSettings: function(e) {
            e.preventDefault();
            
            var settings = {
                client_number: $('#ctt_client_number').val(),
                contract_number: $('#ctt_contract_number').val(),
                sender_name: $('#ctt_sender_name').val(),
                sender_address: $('#ctt_sender_address').val(),
                sender_postal_code: $('#ctt_sender_postal_code').val(),
                sender_city: $('#ctt_sender_city').val(),
                sender_email: $('#ctt_sender_email').val(),
                sender_phone: $('#ctt_sender_phone').val()
            };
            
            var $btn = $(this);
            $btn.prop('disabled', true).text('Salvataggio...');
            
            $.ajax({
                url: jspmCttData.ajax_url,
                type: 'POST',
                data: {
                    action: 'jspm_ctt_update_settings',
                    nonce: jspmCttData.nonce,
                    settings: settings
                },
                success: function(response) {
                    if (response.success) {
                        JSPMCtt.showNotice('Impostazioni salvate con successo!', 'success');
                    } else {
                        JSPMCtt.showNotice('Errore nel salvataggio: ' + response.data, 'error');
                    }
                },
                error: function() {
                    JSPMCtt.showNotice('Errore di connessione', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).text('Salva Impostazioni');
                }
            });
        },
        
        showNotice: function(message, type) {
            var $notice = $('<div class="jspm-ctt-status ' + type + ' show">' + message + '</div>');
            $('.jspm-ctt-controls').prepend($notice);
            
            setTimeout(function() {
                $notice.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        if ($('.jspm-ctt-admin-page').length > 0 || $('#jspm-ctt-order-actions').length > 0) {
            JSPMCtt.init();
        }
    });

})(jQuery);
