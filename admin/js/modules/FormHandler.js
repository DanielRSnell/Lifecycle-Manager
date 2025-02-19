(function($) {
    'use strict';

    WooLifecycleManager.FormHandler = {
        init: function() {
            // Any form-specific initialization
        },

        refreshOrderState: function() {
            const stateScript = document.getElementById('lifecycle-order-state');
            if (stateScript) {
                try {
                    const state = JSON.parse(stateScript.textContent);
                    console.log('Order state refreshed:', state);
                    // You can add more logic here to update the UI based on the new state
                } catch (error) {
                    console.error('Error parsing order state: ' + error.message);
                }
            }
        }
    };

})(jQuery);
