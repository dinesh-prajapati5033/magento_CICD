<?php

/**
 * @category Dinesh HyvaFrequentlyBought
 * @package Dinesh_HyvaFrequentlyBought
 * @copyright Copyright (c) 2023 Dinesh
 * @author Dinesh Team <info@dinesh.com>
 */

namespace Dinesh\HyvaFrequentlyBought\Block\Product\View\Type;

use Magento\Framework\View\DesignInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product as CatalogProduct;
use Magento\Catalog\Model\Product\Image\UrlBuilder;
use Magento\ConfigurableProduct\Helper\Data;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Swatches\Helper\Data as SwatchData;
use Magento\Swatches\Helper\Media;
use Magento\Swatches\Model\SwatchAttributesProvider;

class Configurable extends \Dinesh\FrequentlyBought\Block\Product\View\Type\Configurable
{
    protected const FBT_RENDERER_TEMPLATE =
     'Dinesh_HyvaFrequentlyBought::product/view/type/configurable/renderer.phtml';
    
     /**
      * Constructor function
      *
      * @param DesignInterface $theme
      * @param Context $context
      * @param ArrayUtils $arrayUtils
      * @param EncoderInterface $jsonEncoder
      * @param Data $helper
      * @param CatalogProduct $catalogProduct
      * @param CurrentCustomer $currentCustomer
      * @param PriceCurrencyInterface $priceCurrency
      * @param ConfigurableAttributeData $configurableAttributeData
      * @param SwatchData $swatchHelper
      * @param Media $swatchMediaHelper
      * @param array $data
      * @param SwatchAttributesProvider|null $swatchAttributesProvider
      * @param UrlBuilder|null $imageUrlBuilder
      */
    public function __construct(
        DesignInterface $theme,
        Context $context,
        ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        Data $helper,
        CatalogProduct $catalogProduct,
        CurrentCustomer $currentCustomer,
        PriceCurrencyInterface $priceCurrency,
        ConfigurableAttributeData $configurableAttributeData,
        SwatchData $swatchHelper,
        Media $swatchMediaHelper,
        array $data = [],
        SwatchAttributesProvider $swatchAttributesProvider = null,
        UrlBuilder $imageUrlBuilder = null
    ) {
        $this->theme = $theme;
        parent::__construct(
            $context,
            $arrayUtils,
            $jsonEncoder,
            $helper,
            $catalogProduct,
            $currentCustomer,
            $priceCurrency,
            $configurableAttributeData,
            $swatchHelper,
            $swatchMediaHelper,
            $data,
            $swatchAttributesProvider,
            $imageUrlBuilder
        );
    }
    /**
     * Return template file
     *
     * @return template
     */
    protected function getRendererTemplate()
    {
        if (!$this->isHyva()) {
            return parent::getRendererTemplate();
        } else {
            return self::FBT_RENDERER_TEMPLATE;
        }
    }
    /**
     * Returns true if the current theme is a Hyva theme, i.e a descendant of Hyva/reset (or any Hyva namespaced theme)
     *
     * @return bool
     */
    public function isHyva()
    {
        $theme = $this->theme->getDesignTheme();
        while ($theme) {
            if (strpos($theme->getCode(), 'Hyva/') === 0) {
                return true;
            }
            $theme = $theme->getParentTheme();
        }
        return false;
    }
}
