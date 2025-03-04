// Make jQuery globally available
window.$ = window.jQuery;

import OrderPage from './modules/OrderPage.js';
import AjaxHandler from './modules/AjaxHandler.js';
import LifecycleContainer from './modules/LifecycleContainer.js';
import { initRepeaterTabs } from './modules/acf/repeater-tabs.js';
import { initFlexibleManager } from './modules/acf/flexible-manager.js';
import { initAcfForms } from './modules/acf/form-handler.js';

function initEditor() {
  // initRepeaterTabs();
  // initFlexibleManager();

  // if (typeof window.acf !== 'undefined') {
  //   window.acf.addAction('append', function($el) {
  //     if ($el.hasClass('acf-row')) {
  //       initRepeaterTabs();
  //     }
  //     initFlexibleManager();
  //   });

  //   window.acf.addAction('remove', function($el) {
  //     if ($el.hasClass('acf-row')) {
  //       initRepeaterTabs();
  //     }
  //     initFlexibleManager();
  //   });

  //   window.acf.addAction('sortstop', function($el) {
  //     if ($el.hasClass('values')) {
  //       initFlexibleManager();
  //     }
  //   });
  // }
  console.log('Editor Initialized');
}

window.$(function() {
  const WooLifecycleManager = {
    init: function() {
      this.OrderPage = new OrderPage(window.$);
      this.AjaxHandler = new AjaxHandler(window.$);
      this.LifecycleContainer = new LifecycleContainer(window.$, this.AjaxHandler);

      if (this.OrderPage.isOrderPage()) {
        this.LifecycleContainer.initialize();
      }
    }
  };

  WooLifecycleManager.init();
});



if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initEditor);
} else {
  initEditor();
}
