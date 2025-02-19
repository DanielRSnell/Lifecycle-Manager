import OrderPage from './modules/OrderPage.js';
import AjaxHandler from './modules/AjaxHandler.js';
import LifecycleContainer from './modules/LifecycleContainer.js';

jQuery(function($) {
    'use strict';

    const WooLifecycleManager = {
        init: function() {
            this.OrderPage = new OrderPage($);
            this.AjaxHandler = new AjaxHandler($);
            this.LifecycleContainer = new LifecycleContainer($, this.AjaxHandler);

            if (this.OrderPage.isOrderPage()) {
                this.LifecycleContainer.initialize();
            }
        }
    };

    WooLifecycleManager.init();
});
