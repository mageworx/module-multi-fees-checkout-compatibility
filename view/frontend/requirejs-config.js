var config = {
    config: {
        mixins: {
            'MageWorx_MultiFees/js/view/summary/fee': {
                'MageWorx_MultiFeesCheckout/js/view/summary/totals/mageworx-fee-total-mixin': true
            },
            'MageWorx_MultiFees/js/view/summary/fee-tax': {
                'MageWorx_MultiFeesCheckout/js/view/summary/totals/mageworx-fee-total-mixin': true
            },
            'MageWorx_MultiFees/js/view/mageworx-fee-form': {
                'MageWorx_MultiFeesCheckout/js/view/summary/additional-inputs/mageworx-fee-form-mixin': true
            },
            'MageWorx_MultiFees/js/view/summary/product-fee': {
                'MageWorx_MultiFeesCheckout/js/view/summary/totals/mageworx-fee-total-mixin': true
            },
            'MageWorx_MultiFees/js/view/summary/product-fee-tax': {
                'MageWorx_MultiFeesCheckout/js/view/summary/totals/mageworx-fee-total-mixin': true
            }
        }
    }
};
