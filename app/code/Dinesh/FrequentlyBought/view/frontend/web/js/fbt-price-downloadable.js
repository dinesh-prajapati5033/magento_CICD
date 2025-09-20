define([
    "jquery",
    "jquery/ui",
    "downloadable"
], function ($) {
    "use strict";

    $.widget('dinesh.frequently_bought_downloadable', $.mage.downloadable, {
        /**
         * Reload product price with selected link price included
         * @private
         */
        _reloadPrice: function () {
            var finalPrice = 0,
                basePrice = 0,
                productPrice,
                parentElement = this.element.closest('li');
            this.element.find(this.options.linkElement + ':checked').each($.proxy(function (index, element) {
                finalPrice += this.options.config.links[$(element).val()].finalPrice;
                basePrice += this.options.config.links[$(element).val()].basePrice;
            }, this));

            productPrice = parseFloat(parentElement.find('.related-checkbox').attr('data-price-amount'));
            parentElement.find('.item-price').attr('data-price-amount', productPrice + finalPrice);
            parentElement.find('.related-checkbox').change();
        }
    });

    return $.dinesh.frequently_bought_downloadable;
});
