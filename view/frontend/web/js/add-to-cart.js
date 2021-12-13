define([
    'jquery',
    './data-layer-push',
    'Magento_Customer/js/customer-data'
],
function ($, dataLayerPush, customerData) {
    'use strict'

    /**
     * A function that does add TagManager dataLayer object with added product information
     */
    return function (config) {
        $(document).on('ajax:addToCart', function (e, data) {
            var formData = new FormData(data.form[0])

            var quantity = formData.get('qty')
            var addedProductId = data.productIds[0]

            var subscription = customerData.get('cart').subscribe(function (cart) {
                var addedProductInCart = cart.items.find(item => item.product_id === addedProductId)

                // unsubscribes this listener from further cart updates
                // this was needed one-time only
                subscription.dispose()

                var addToCartDL = {
                    event: 'addToCart',
                    ecommerce: {
                        add: {
                            products: [{
                                id: addedProductInCart.product_sku,
                                // disabled as it doesn't work great for customisable products
                                // name: addedProductInCart.product_name,
                                price: addedProductInCart.product_price_value.incl_tax,
                                quantity: quantity
                            }]
                        }
                    }
                }

                if (config.currencyCode) {
                    addToCartDL.ecommerce.currencyCode = config.currencyCode
                }

                dataLayerPush({ ecommerce: null })
                dataLayerPush(addToCartDL)
            })
        })
    }
})
