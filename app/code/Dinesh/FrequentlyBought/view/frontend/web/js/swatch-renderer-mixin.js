
define(['jquery'], function ($) {
    'use strict';
    return function (SwatchRenderer) {
        $.widget('mage.SwatchRenderer', $['mage']['SwatchRenderer'], {

            /**
             * Load media gallery using ajax or json config.
             *
             * @private
             */
            _loadMedia: function () {
                var $main = "",
                    images;

                if (this.inProductList) {
                    $main = this.element.parents('.product-item-info');
                } else {
                    $main = this.element.closest('.dinesh-fbt-item').length ? this.element.parents('.dinesh-fbt-item') : this.element.parents('.column.main');
                }

                if (this.options.useAjax) {
                    this._debouncedLoadProductMedia();
                }  else {
                    images = this.options.jsonConfig.images[this.getProduct()];

                    if (!images) {
                        images = this.options.mediaGalleryInitial;
                    }
                    this.updateBaseImage(this._sortImages(images), $main, !this.inProductList);
                }
            },
            updateBaseImage: function (images, context, isInProductView) {
                var justAnImage = images[0],
                    initialImages = this.options.mediaGalleryInitial,
                    imagesToUpdate,
                    gallery = context.find(this.options.mediaGallerySelector).data('gallery'),
                    isInitial;

                if (isInProductView && !context.hasClass('dinesh-fbt-item')) {
                    if (_.isUndefined(gallery)) {
                        context.find(this.options.mediaGallerySelector).on('gallery:loaded', function () {
                            this.updateBaseImage(images, context, isInProductView);
                        }.bind(this));

                        return;
                    }

                    imagesToUpdate = images.length ? this._setImageType($.extend(true, [], images)) : [];
                    isInitial = _.isEqual(imagesToUpdate, initialImages);

                    if (this.options.gallerySwitchStrategy === 'prepend' && !isInitial) {
                        imagesToUpdate = imagesToUpdate.concat(initialImages);
                    }

                    imagesToUpdate = this._setImageIndex(imagesToUpdate);

                    gallery.updateData(imagesToUpdate);
                    this._addFotoramaVideoEvents(isInitial);
                } else if (justAnImage && justAnImage.img) {
                    context.find('.product-image-photo').attr('src', justAnImage.img);
                }
            }

        });
        return $['mage']['SwatchRenderer']; // Return flow of original action.
    };
});
