export default class AjaxHandler {
    constructor($) {
        this.$ = $;
        this.ajaxUrl = wlm_ajax.ajax_url;
        this.nonce = wlm_ajax.nonce;
    }

    getLifecyclePartial(orderId, status) {
        return this.$.ajax({
            url: this.ajaxUrl,
            method: 'POST',
            data: { 
                action: 'get_lifecycle_partial',
                partial: 'container',
                status: status,
                order_id: orderId,
                nonce: this.nonce
            }
        });
    }

    updateLifecycleStatus(orderId, status) {
        return this.$.ajax({
            url: this.ajaxUrl,
            method: 'POST',
            data: { 
                action: 'update_lifecycle_status',
                status: status,
                order_id: orderId,
                nonce: this.nonce
            }
        });
    }
}
