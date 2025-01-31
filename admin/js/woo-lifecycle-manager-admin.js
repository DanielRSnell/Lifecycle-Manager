(function($) {
    'use strict';

    const WooLifecycleManager = {
        orderId: null,
        ajaxUrl: wlm_ajax.ajax_url,
        nonce: wlm_ajax.nonce,

        init: function() {
            if (this.isOrderPage()) {
                this.orderId = this.getOrderIdFromUrl();
                if (this.orderId) {
                    this.setupEventListeners();
                    this.initialFetch();
                }
            }
        },

        isOrderPage: function() {
            return window.location.href.includes('wc-orders');
        },

        getOrderIdFromUrl: function() {
            return new URLSearchParams(window.location.search).get('id');
        },

        setupEventListeners: function() {
            const orderStatus = $('#order_status');
            if (orderStatus.length) {
                orderStatus.on('change', this.handleStatusChange.bind(this));
            }
        },

        initialFetch: function() {
            const orderStatus = $('#order_status');
            if (orderStatus.length) {
                this.fetchLifecyclePartial('container', orderStatus.val());
            }
        },

        handleStatusChange: function(event) {
            this.updateLifecycleStatus($(event.target).val());
        },

        fetchLifecyclePartial: function(partial, status) {
            $.ajax({
                url: this.ajaxUrl,
                method: 'POST',
                data: { 
                    action: 'get_lifecycle_partial',
                    partial: partial,
                    status: status,
                    order_id: this.orderId,
                    nonce: this.nonce
                },
                success: this.handleFetchSuccess.bind(this),
                error: this.handleAjaxError.bind(this)
            });
        },

        updateLifecycleStatus: function(status) {
            $.ajax({
                url: this.ajaxUrl,
                method: 'POST',
                data: { 
                    action: 'update_lifecycle_status',
                    status: status,
                    order_id: this.orderId,
                    nonce: this.nonce
                },
                success: this.handleUpdateSuccess.bind(this),
                error: this.handleAjaxError.bind(this)
            });
        },

        handleFetchSuccess: function(response) {
            if (response.success) {
                this.updateDom(response.data);
            } else {
                this.handleError('Error fetching lifecycle partial: ' + response.data);
            }
        },

        handleUpdateSuccess: function(response) {
            if (response.success) {
                this.updateDom(response.data);
            } else {
                this.handleError('Error updating lifecycle status: ' + response.data);
            }
        },

        updateDom: function(html) {
            let $container = $('#lifecycle-container');
            if (!$container.length) {
                $('#order_data').after('<div id="lifecycle-container"></div>');
                $container = $('#lifecycle-container');
            }
            $container.html(html);
            this.refreshOrderState();
        },

        refreshOrderState: function() {
            const stateScript = document.getElementById('lifecycle-order-state');
            if (stateScript) {
                try {
                    const state = JSON.parse(stateScript.textContent);
                    console.log('Order state refreshed:', state);
                } catch (error) {
                    this.handleError('Error parsing order state: ' + error.message);
                }
            }
        },

        handleAjaxError: function(xhr, status, error) {
            this.handleError('AJAX error: ' + error);
        },

        handleError: function(message) {
            console.error(message);
        }
    };

    $(document).ready(function() {
        WooLifecycleManager.init();
    });

    window.WooLifecycleManager = WooLifecycleManager;

})(jQuery);
