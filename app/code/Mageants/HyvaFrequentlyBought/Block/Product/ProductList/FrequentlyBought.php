<?php

/**
 * @category Mageants HyvaFrequentlyBought
 * @package Mageants_HyvaFrequentlyBought
 * @copyright Copyright (c) 2023 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\HyvaFrequentlyBought\Block\Product\ProductList;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\ProductList\Related;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Checkout\Model\ResourceModel\Cart;
use Magento\Checkout\Model\Session;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Module\Manager;
use Magento\Framework\Pricing\Helper\Data;
use Mageants\FrequentlyBought\Helper\Data as FbtData;
use Magento\Framework\View\DesignInterface;

class FrequentlyBought extends \Mageants\FrequentlyBought\Block\Product\ProductList\FrequentlyBought
{
    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $priceHelper;

    /**
     * @var \Mageants\FrequentlyBought\Helper\Data
     */
    protected $fbtDataHelper;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Framework\Locale\FormatInterface
     */
    protected $_localeFormat;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productloader;

    /**
     * @var \Magento\Framework\View\DesignInterface
     */
    protected $theme;

    /**
     * Constructor function
     *
     * @param Context $context
     * @param Cart $checkoutCart
     * @param \Magento\Catalog\Model\ProductFactory $_productloader
     * @param Visibility $catalogProductVisibility
     * @param Session $checkoutSession
     * @param Manager $moduleManager
     * @param Data $priceHelper
     * @param ProductRepositoryInterface $productRepository
     * @param FbtData $fbtDataHelper
     * @param FormatInterface $localeFormat
     * @param \Magento\Store\Model\StoreManagerInterface $storemanager
     * @param DesignInterface $theme
     * @param array $data
     */
    public function __construct(
        Context $context,
        Cart $checkoutCart,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        Visibility $catalogProductVisibility,
        Session $checkoutSession,
        Manager $moduleManager,
        Data $priceHelper,
        ProductRepositoryInterface $productRepository,
        FbtData $fbtDataHelper,
        FormatInterface $localeFormat,
        \Magento\Store\Model\StoreManagerInterface $storemanager,
        DesignInterface $theme,
        array $data = []
    ) {
        $this->theme = $theme;
        parent::__construct(
            $context,
            $_productloader,
            $checkoutCart,
            $catalogProductVisibility,
            $checkoutSession,
            $moduleManager,
            $priceHelper,
            $productRepository,
            $fbtDataHelper,
            $localeFormat,
            $storemanager,
            $data
        );
    }

    /**
     * Add Product Attributes and Prices
     *
     * @param Collection $collection
     *
     * @return collection
     */
    protected function _addProductAttributesAndPrices(Collection $collection)
    {
        $collection = parent::_addProductAttributesAndPrices($collection);

        $itemLimit = (int)$this->fbtDataHelper->getConfigGeneral('item_limit');
        if ($itemLimit) {
            $collection->getSelect()
                ->limit($itemLimit);
        }

        return $collection;
    }

    /**
     * Return Encoded Configuration
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $config = [
            'priceFormat' => $this->_localeFormat->getPriceFormat()
        ];

        return FbtData::jsonEncode($config);
    }

    /**
     * Return block name
     *
     * @return string
     */
    public function getTitleBlock()
    {
        return $this->fbtDataHelper->getConfigGeneral('block_name');
    }

    /**
     * Return product price with currency
     *
     * @param float $price
     *
     * @return price
     */
    public function getPriceWithCurrency($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }

    /**
     * Return Product Price
     *
     * @param object $product
     *
     * @return price
     */
    public function getPriceAmount($product)
    {
        if ($product->getTypeId() == 'grouped' || $product->getTypeId() == 'bundle') {
            return 0;
        }

        return $product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
    }

    /**
     * Return Current Product
     *
     * @return object
     */
    public function getCurrentProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    /**
     * Return Frequently Bought Together Collection
     *
     * @return collection
     */
    public function getFbtItems()
    {
        $currentProduct = $this->getCurrentProduct();
        $productData = $this->_productloader->create()->load($currentProduct->getId());

        return $productData->getCustomtypeProducts();
    }

    /**
     * Return Custom Option
     *
     * @param string $productId
     *
     * @return $option
     */
    public function getCustomOption($productId = null)
    {
        if (!$this->isHyva()) {
            return parent::getCustomOption($productId);
        } else {
            $product = $this->getProductById($productId);
            $option  = $this->getLayout()->getBlock('mageants.product.info.options')
                ->setProduct($product)
                ->toHtml();
            if ($option != '') {
                return $option;
            } else {
                return '';
            }
        }
    }

    /**
     * Return Options
     *
     * @param string $productId
     *
     * @return html
     */
    public function getOptionWrapper($productId = null)
    {
        if (!$this->isHyva()) {
            return parent::getOptionWrapper($productId);
        } else {
            $html        = '';
            $product     = $this->getProductById($productId);
            $productType = $product->getTypeId();
            switch ($productType) {
                case 'configurable':
                    $html = $this->getLayout()->createBlock(
                        \Mageants\HyvaFrequentlyBought\Block\Product\View\Type\Configurable::class
                    );
                    break;
                case 'grouped':
                    $html = $this->getLayout()
                        ->createBlock(\Magento\GroupedProduct\Block\Product\View\Type\Grouped::class)
                        ->setTemplate('Mageants_HyvaFrequentlyBought::product/view/type/grouped.phtml');
                    break;
                case 'bundle':
                    $html = $this->getLayout()->getBlock('mageants.product.info.bundle.options');
                    break;
                case 'downloadable':
                    $html = $this->getLayout()
                        ->createBlock(\Magento\Downloadable\Block\Catalog\Product\Links::class)
                        ->setTemplate('Magento_Downloadable::product/view/links.phtml');
                    break;
            }
            if ($html) {
                return $html->setProduct($product)->toHtml();
            }

            return $html;
        }
    }

    /**
     * Return Product by Id
     *
     * @param string $productId
     *
     * @return object
     */
    protected function getProductById($productId = null)
    {
        $storeId = $this->_storeManager->getStore()->getId();
        if ($productId) {
            $product = $this->productRepository->getById($productId, false, $storeId);
        } else {
            $product = $this->getCurrentProduct();
        }

        return $product;
    }

    /**
     * Return Separator Image
     *
     * @return string
     */
    public function getSeparatorImage()
    {
        return $this->fbtDataHelper->getIcon();
    }

    /**
     * Display wishlist option
     *
     * @return string
     */
    public function getShowWishList()
    {
        return $this->fbtDataHelper->getConfigGeneral('enable_add_to_wishlist');
    }

    /**
     * Return Product Collection By Product Id
     *
     * @param string $id
     *
     * @return colletion
     */
    public function getLoadProductById($id)
    {
        return $this->_productloader->create()->load($id);
    }

    /**
     * Get Configuration values
     *
     * @param string $configPath
     *
     * @return string
     */
    public function getConfigValue($configPath)
    {
        return $this->fbtDataHelper->getConfig($configPath);
    }
    /**
     * Get Media url
     */
    public function mediaUrl()
    {
        $currentStore = $this->storemanager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
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
