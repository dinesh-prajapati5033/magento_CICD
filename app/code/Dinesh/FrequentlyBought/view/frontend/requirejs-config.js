var config = {
    map: {
        '*': {
            frequentlyBought: 'Dinesh_FrequentlyBought/js/frequentlybought',
            FbtSwatchRenderer: 'Dinesh_FrequentlyBought/js/fbt-swatch-renderer',
            FbtPriceBundle: 'Dinesh_FrequentlyBought/js/fbt-price-bundle',
            FbtPriceDownloadable: 'Dinesh_FrequentlyBought/js/fbt-price-downloadable'
        }
    },
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Dinesh_FrequentlyBought/js/swatch-renderer-mixin': true
            }
        }
    }
};