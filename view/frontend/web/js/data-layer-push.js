define([], function () {
    'use strict'

    return function (data) {
        var dataLayerName = window.dataLayerName || 'dataLayer'
        window[dataLayerName].push(data)
    }
})
