export default class OrderPage {
    constructor($) {
        this.$ = $;
    }

    isOrderPage() {
        return window.location.href.includes('page=wc-orders') &&
               window.location.href.includes('action=edit') &&
               new URLSearchParams(window.location.search).get('id');
    }
}
