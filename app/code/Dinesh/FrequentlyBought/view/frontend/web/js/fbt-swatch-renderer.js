define([
    'jquery',
    'underscore',
    'Magento_Swatches/js/swatch-renderer'
], function($, _) {
    'use strict';

    $.widget('dinesh.FbtSwatchRenderer', $.mage.SwatchRenderer, {
        _create: function() {
            this.productForm = this.element.parents(this.options.selectorProductTile).find('form:first');
        },

        _RenderFormInput: function(config) {
            var productId = this.options.jsonConfig.productId,
                inputHtml = this._super(config);

            inputHtml = inputHtml.replace('super_attribute', 'super_attribute[' + productId + ']');

            return inputHtml;
        },

        _OnClick: function($this, $widget) {
            this._super($this, $widget);

            if ($widget.element.closest('li').find('.item-price').length) {
                $widget._UpdatePrice();
            }
        },

        _UpdatePrice: function() {
            var $widget = this,
                $product = $widget.element.closest('li'),
                options = _.object(_.keys($widget.optionsMap), {}),
                result;

            $widget.element.find('.' + $widget.options.classes.attributeClass).each(function() {
                var attributeId = $widget.getAttributeId($(this));
                options[attributeId] = $widget.getOptionSelected($(this));
            });

            result = $widget.options.jsonConfig.optionPrices[_.findKey($widget.options.jsonConfig.index, options)];

            if (result) {
                $product.find('.item-price').attr('data-price-amount', result.finalPrice.amount);
                $product.find('.related-checkbox').attr('data-price-amount', result.finalPrice.amount).change();
            }
        },

        getAttributeId: function($element) {
            return $element.attr('data-attribute-id') || $element.attr('attribute-id');
        },

        getOptionSelected: function($element) {
            return $element.attr('data-option-selected') || $element.attr('option-selected');
        },

        _EmulateSelected: function(selectedAttributes) {},

        _LoadProductMedia: function() {}
    });

    return $.dinesh.FbtSwatchRenderer;
});