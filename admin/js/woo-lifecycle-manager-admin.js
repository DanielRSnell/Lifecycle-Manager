(function($) {
    'use strict';

    $(document).ready(function() {
        if (window.location.href.includes('wc-orders')) {
            const orderData = document.getElementById('order_data');
            if (orderData) {
                const lifecycleContainer = $('<div>', {
                    id: 'lifecycle-container',
                    class: 'lc-container'
                });
                const lifecycleStatus = $('<div>', {
                    class: 'lc-status'
                });
                lifecycleContainer.append(lifecycleStatus);
                $(orderData).after(lifecycleContainer);

                const orderStatus = $('#order_status');
                if (orderStatus.length) {
                    updateLifecycleStatus(orderStatus.val());
                    orderStatus.on('change', function() {
                        updateLifecycleStatus($(this).val());
                    });
                }
            }
        }
    });

    function updateLifecycleStatus(status) {
        const lifecycleStatus = $('.lc-status');
        if (lifecycleStatus.length) {
            lifecycleStatus.text(status.replace('wc-', '').replace('-', ' '));
        }
    }
})(jQuery);
