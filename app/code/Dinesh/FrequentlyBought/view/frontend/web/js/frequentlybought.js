define([
    'jquery',
    'Magento_Catalog/js/price-utils',
    'mage/translate',
    'jquery/ui'
], function($, priceUtils, $t) {
    'use strict';

    $.widget('dinesh.frequentlyBought', {
        options: {
            addWishlistUrl: '',
            showDetails: $t('Show details'),
            hideDetails: $t('Hide details')
        },
        cache: {
            priceObject: {}
        },

        _create: function() {
            this._EventListener();
            this._bind();
            this._renderShowDetail();
        },

        _bind: function() {
            this.element.find('.dinesh-fbt-rows .related-checkbox').trigger('click');
        },

        _EventListener: function() {
            var self = this;
            this.element.on('change', '.dinesh-fbt-rows .related-checkbox', function() {
                self._reloadTotalPrice();
            });
            this.element.on('change', '.dinesh-fbt-rows .dinesh-fbt-grouped .dinesh-fbt-grouped-qty', function() {
                self._reloadGroupedPrice($(this));
            });
            this.element.find('.dinesh-fbt-rows .product-custom-option').on('change', this._onOptionChanged.bind(this));
            this.element.on('click', '.dinesh-fbt-add-to-wishlist button', function() {
                self._addToWishList($(this));
            });
            this.element.on('click', '.dinesh-fbt-detail .detailed-node', function() {
                self._showHideDetail($(this));
            });
            this.element.on('click', '.dinesh-fbt-add-to-cart button', function() {
                self._addValidation();
            });
        },

        _addValidation: function() {
            $('.dinesh-fbt-rows ol li').each(function() {
                var selectTypesFlag = false,
                    $widget = $(this);
                if (!$widget.find('.related-checkbox').is(':checked')) {
                    $widget.find('.dinesh-fbt-detail a:not(".not-active")').click();
                    return;
                }

                if ($(this).find('.dinesh-fbt-grouped').length > 0) {
                    var active = false;
                    $(this).find('.dinesh-fbt-grouped-qty').each(function() {
                        var qty = parseFloat($(this).val());
                        if (qty > 0) {
                            active = true;
                        }
                    });
                    if (!active) {
                        $(this).find('.dinesh-fbt-detail a.not-active').click();
                    }
                }

                $widget.find('[aria-required="true"]').each(function() {
                    var _this = this,
                        optionType = $(_this).prop('type');
                    if (optionType != 'hidden' && !$(_this).hasClass('qty')) {
                        switch (optionType) {
                            case 'text':
                                selectTypesFlag = false;
                                if ($(_this).val()) {
                                    selectTypesFlag = true;
                                }
                                break;

                            case 'textarea':
                                selectTypesFlag = false;
                                if ($(_this).val()) {
                                    selectTypesFlag = true;
                                }
                                break;

                            case 'radio':
                                selectTypesFlag = false;
                                var nameRadio = $(_this).attr('name');
                                $('[name="' + nameRadio + '"]').each(function() {
                                    if ($(this).is(':checked')) {
                                        selectTypesFlag = true;
                                        return false;
                                    }
                                });
                                break;

                            case 'select-one':
                                selectTypesFlag = false;
                                if ($(_this).val()) {
                                    selectTypesFlag = true;
                                }
                                break;

                            case 'select-multiple':
                                selectTypesFlag = false;
                                _.each($(_this).find('option'), function(option) {
                                    if ($(option).is(':selected')) {
                                        selectTypesFlag = true;
                                        return false;
                                    }
                                });
                                break;

                            case 'checkbox':
                                selectTypesFlag = false;
                                _.each($(_this).closest('.mg-fbt-options-list').find('.mp-fbt-multi-select'), function(option) {
                                    if ($(option).is(':checked')) {
                                        selectTypesFlag = true;
                                        return false;
                                    }
                                });
                                break;

                            case 'file':
                                selectTypesFlag = false;
                                if ($(_this).val() && !$(_this).prop('disabled')) {
                                    selectTypesFlag = true;
                                }
                                break;
                        };
                        if (!selectTypesFlag) {
                            return false;
                        }
                    }
                });
                if (!selectTypesFlag && $(this).find('.dinesh-fbt-grouped').length == 0) {
                    $widget.find('.dinesh-fbt-detail a.not-active').click();
                }
            });
        },

        _onOptionChanged: function(event) {
            var optionPrice = 0,
                changes = {},
                element = $(event.target),
                optionName = element.prop('name'),
                optionType = element.prop('type'),
                parentElement = element.closest('li'),
                checkboxElement = parentElement.find('.related-checkbox'),
                productId = checkboxElement.attr('data-dinesh-fbt-product-id'),
                productPrice = parseFloat(checkboxElement.attr('data-price-amount'));
            switch (optionType) {
                case 'text':

                case 'textarea':
                    optionPrice = parseFloat(element.closest('div.field').find('.price-wrapper').attr('data-price-amount'));
                    if (element.val()) {
                        changes[optionName] = optionPrice;
                    } else {
                        changes[optionName] = 0;
                    }
                    break;

                case 'radio':
                    optionPrice = parseFloat(element.attr('price'));
                    if (element.is(':checked')) {
                        changes[optionName] = optionPrice;
                    }
                    break;
                case 'select-one':
                    if (element.find(":selected").attr('price')) {
                        optionPrice = parseFloat(element.find(":selected").attr('price'));
                    }
                    changes[optionName] = optionPrice;
                    break;

                case 'select-multiple':
                    _.each(element.find('option'), function(option) {
                        if ($(option).is(':selected')) {
                            optionPrice += parseFloat($(option).attr('price'));
                        }
                    });
                    changes[optionName] = optionPrice;
                    break;

                case 'checkbox':
                    _.each(element.closest('.options-list').find('.product-custom-option'), function(option) {
                        if ($(option).is(':checked')) {
                            optionPrice += parseFloat($(option).attr('price'));
                        }
                    });
                    changes[optionName] = optionPrice;
                    break;

                case 'file':
                    // Checking for 'disable' property equal to checking DOMNode with id*="change-"
                    if (element.val() && !element.prop('disabled')) {
                        optionPrice = parseFloat(element.closest('div.field').find('.price-wrapper').attr('data-price-amount'));
                    }
                    changes[optionName] = optionPrice;
                    break;
            };
            $.extend(this.cache.priceObject, changes);
            _.each(this.cache.priceObject, function(value, key) {
                var parentElementUpdate = $('[name="' + key + '"]').closest('li'),
                    productIdUpdate = parentElementUpdate.find('.related-checkbox').attr('data-dinesh-fbt-product-id');
                if (productId == productIdUpdate) {
                    productPrice += parseFloat(value);
                }
            });
            parentElement.find('.item-price').attr('data-price-amount', productPrice);
            this._reloadTotalPrice();
        },

        _reloadImageProductList: function($this, checked) {
            var productId = $this.attr('data-dinesh-fbt-product-id');
            var elm = $('.dinesh-fbt-image-box li:not(".dinesh-fbt-hidden"):first');
        },

        _reloadTotalPrice: function() {
            var totalPrice = 0,
                count = 0,
                _this = this;

            $('.dinesh-fbt-rows .related-checkbox').each(function() {
                if ($(this).is(':checked')) {
                    totalPrice += parseFloat($(this).closest('li').find('.item-price').attr('data-price-amount'));
                    _this._reloadImageProductList($(this), true);
                    count++;
                } else {
                    _this._reloadImageProductList($(this), false);
                }
                var priceElement = $(this).closest('li').find('.item-price'),
                    priceItem = $(priceElement).attr('data-price-amount');
                $(priceElement).empty().append(_this._getFormattedPrice(priceItem));
            });

            var priceBox = $('.dinesh-fbt-price-box');
            $('.dinesh-fbt-price-wrapper').attr('data-price-amount', totalPrice);
            $('.dinesh-fbt-price').empty().append(_this._getFormattedPrice(totalPrice));

            _this._reloadButtonLabel(count);
        },

        _reloadButtonLabel: function(number) {
            var buttonCartLabel = $t('Add %s to Cart'),
                buttonWishlistLabel = $t('Add %s to Wishlist'),
                numberLessThanTwelve = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve'],
                num = parseInt(number, 10),
                replace = '';
            var numCount = 0;
            numCount = num;
            if (num > 1) {
                replace = ' all';
                if (num <= 12) {
                    replace += ' ' + numberLessThanTwelve[num];
                }
            }

            buttonCartLabel = buttonCartLabel.replace(' %s', replace);
            buttonWishlistLabel = buttonWishlistLabel.replace(' %s', replace);
            $(".dinesh-fbt-accessories-count").empty().append(numCount);

            // $('.dinesh-fbt-add-to-cart button').attr('title', buttonCartLabel);
            // $('.dinesh-fbt-add-to-cart button span').empty().append(buttonCartLabel);

            return this;
        },

        _reloadGroupedPrice: function($this) {
            var price = 0,
                _this = this,
                totalPrice = 0,
                productId = $this.closest('li').find('.related-checkbox').attr('data-dinesh-fbt-product-id');
            if ($this.val() > 0) {
                price = parseFloat($this.val()) * parseFloat($this.attr('data-child-product-price-amount'));
            }
            $this.attr('data-child-product-price-total', price);
            $('.dinesh-fbt-rows #dinesh-fbt-super-product-table-' + productId + ' .dinesh-fbt-grouped-qty').each(function() {
                totalPrice += parseFloat($(this).attr('data-child-product-price-total'));
            });
            $('.dinesh-fbt-price-' + productId).attr('data-price-amount', totalPrice);

            _this._reloadTotalPrice();
        },

        _getFormattedPrice: function(price) {
            var priceFormat = {
                decimalSymbol: '.',
                groupLength: 3,
                groupSymbol: ",",
                integerRequired: false,
                precision: 2,
                requiredPrecision: 2
            };
            return priceUtils.formatPrice(price, priceFormat, false);
        },

        _addToWishList: function($this) {
            var url = $this.attr('data-url');
            if (url) {
                this.element.attr('action', url);
            }
        },

        _renderShowDetail: function() {
            var $widget = this;
            $('.dinesh-fbt-rows .dinesh-fbt-option-product').each(function() {
                var _this = this,
                    html = '';
                if ($(_this).children().length > 0) {
                    var element = $(_this).closest('li').find('.dinesh-fbt-detail');
                    html += '<a class="detailed-node not-active" href="javascript:void(0)">';
                    html += $widget.options.showDetails;
                    html += '</a>';
                    if ($(element).children().length == 0) {
                        $(element).append(html);
                    }
                }
            });
        },

        _showHideDetail: function($this) {
            var $widget = this;
            if (!$this.hasClass('not-active')) {
                $this.addClass('not-active').empty().html($widget.options.showDetails);
                $this.closest('li').find('.dinesh-fbt-option-product').addClass('dinesh-fbt-hidden');
            } else {
                $this.removeClass('not-active').empty().html($widget.options.hideDetails);
                $this.closest('li').find('.dinesh-fbt-option-product').removeClass('dinesh-fbt-hidden');
            }
        }
    });
    return $.dinesh.frequentlyBought;
});