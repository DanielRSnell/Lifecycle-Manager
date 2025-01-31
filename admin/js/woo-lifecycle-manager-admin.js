(function($) {
    'use strict';

    let orderId = null;

    $(document).ready(function() {
        if (window.location.href.includes('wc-orders')) {
            orderId = new URLSearchParams(window.location.search).get('id');
            
            if (orderId) {
                const orderData = document.getElementById('order_data');
                if (orderData) {
                    const orderStatus = $('#order_status');
                    if (orderStatus.length) {
                        fetchLifecyclePartial('container', orderStatus.val());
                        orderStatus.on('change', function() {
                            updateLifecycleStatus($(this).val());
                        });
                    }
                }
            }
        }
    });

    function fetchLifecyclePartial(partial, status) {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: { 
                action: 'get_lifecycle_partial',
                partial: partial,
                status: status,
                order_id: orderId,
                nonce: wlm_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#lifecycle-container').remove();
                    $('#order_data').after(response.data);
                } else {
                    console.error('Error fetching lifecycle partial:', response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching lifecycle partial:', error);
            }
        });
    }

    function updateLifecycleStatus(status) {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: { 
                action: 'update_lifecycle_status',
                status: status,
                order_id: orderId,
                nonce: wlm_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#lifecycle-container').replaceWith(response.data);
                } else {
                    console.error('Error updating lifecycle status:', response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating lifecycle status:', error);
            }
        });
    }

    // Expose the fetchLifecyclePartial function globally
    window.fetchLifecyclePartial = fetchLifecyclePartial;
})(jQuery);
