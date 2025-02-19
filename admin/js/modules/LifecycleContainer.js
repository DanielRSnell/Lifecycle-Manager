export default class LifecycleContainer {
    constructor($, ajaxHandler) {
        this.$ = $;
        this.ajaxHandler = ajaxHandler;
    }

    initialize() {
        const $orderData = this.$('#order_data');
        if ($orderData.length) {
            this.createContainer($orderData);
            this.fetchAndUpdateContainer();
            this.setupEventListeners();
        }
    }

    createContainer($orderData) {
        if (!this.$('#lifecycle-container').length) {
            $orderData.after('<div id="lifecycle-container"></div>');
        }
    }

    fetchAndUpdateContainer() {
        const orderId = this.getOrderIdFromUrl();
        const status = this.$('select#order_status').val();

        this.ajaxHandler.getLifecyclePartial(orderId, status)
            .done(response => {
                if (response.success && response.data) {
                    this.$('#lifecycle-container').html(response.data);
                } else {
                    console.error('Error fetching lifecycle partial:', response.data || 'Unknown error');
                }
            })
            .fail((xhr, status, error) => {
                console.error('AJAX error:', error);
            });
    }

    setupEventListeners() {
        this.$('select#order_status').on('change', this.handleStatusChange.bind(this));
    }

    handleStatusChange(event) {
        const newStatus = this.$(event.target).val();
        const orderId = this.getOrderIdFromUrl();

        this.ajaxHandler.updateLifecycleStatus(orderId, newStatus)
            .done(response => {
                if (response.success && response.data) {
                    this.$('#lifecycle-container').html(response.data);
                } else {
                    console.error('Error updating lifecycle status:', response.data || 'Unknown error');
                }
            })
            .fail((xhr, status, error) => {
                console.error('AJAX error:', error);
            });
    }

    getOrderIdFromUrl() {
        return new URLSearchParams(window.location.search).get('id');
    }
}
