var config = {
    map: {
        '*': {
            frequentlyBought: 'Mageants_FrequentlyBought/js/frequentlybought',
            FbtSwatchRenderer: 'Mageants_FrequentlyBought/js/fbt-swatch-renderer',
            FbtPriceBundle: 'Mageants_FrequentlyBought/js/fbt-price-bundle',
            FbtPriceDownloadable: 'Mageants_FrequentlyBought/js/fbt-price-downloadable'
        }
    },
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Mageants_FrequentlyBought/js/swatch-renderer-mixin': true
            }
        }
    }
};