<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Block\Product\ProductList;

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
use Magento\Catalog\Model\ProductFactory;
use Magento\Store\Model\StoreManagerInterface;

class FrequentlyBought extends Related
{
    /**
     * @var Data
     */
    protected $priceHelper;

    /**
     * @var Data
     */
    protected $fbtDataHelper;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var FormatInterface
     */
    protected $_localeFormat;

    /**
     * @var ProductFactory
     */
    protected $_productloader;

    /**
     * Construct
     *
     * @param Context $context
     * @param ProductFactory $_productloader
     * @param Cart $checkoutCart
     * @param Visibility $catalogProductVisibility
     * @param Session $checkoutSession
     * @param Manager $moduleManager
     * @param Data $priceHelper
     * @param ProductRepositoryInterface $productRepository
     * @param FbtData $fbtDataHelper
     * @param FormatInterface $localeFormat
     * @param StoreManagerInterface $storemanager
     * @param array $data
     */
    public function __construct(
        Context $context,
        ProductFactory $_productloader,
        Cart $checkoutCart,
        Visibility $catalogProductVisibility,
        Session $checkoutSession,
        Manager $moduleManager,
        Data $priceHelper,
        ProductRepositoryInterface $productRepository,
        FbtData $fbtDataHelper,
        FormatInterface $localeFormat,
        StoreManagerInterface $storemanager,
        array $data = []
    ) {
        $this->priceHelper       = $priceHelper;
        $this->productRepository = $productRepository;
        $this->fbtDataHelper     = $fbtDataHelper;
        $this->_localeFormat     = $localeFormat;
        $this->_productloader = $_productloader;
        $this->storemanager = $storemanager;

        parent::__construct(
            $context,
            $checkoutCart,
            $catalogProductVisibility,
            $checkoutSession,
            $moduleManager,
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
        return $this->priceHelper->currency($price, true, true);
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
        $product = $this->getProductById($productId);
        $option  = $this->getLayout()->getBlock('mageants.frequently.bought.product.info.options')
            ->setProduct($product)
            ->toHtml();

        return $option;
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
        $html        = '';
        $product     = $this->getProductById($productId);
        $productType = $product->getTypeId();
        switch ($productType) {
            case 'configurable':
                $html = $this->getLayout()->createBlock(
                    \Mageants\FrequentlyBought\Block\Product\View\Type\Configurable::class
                );
                break;
            case 'grouped':
                $html = $this->getLayout()->createBlock(\Magento\GroupedProduct\Block\Product\View\Type\Grouped::class)
                    ->setTemplate('Mageants_FrequentlyBought::product/view/type/grouped.phtml');
                break;
            case 'bundle':
                $html = $this->getLayout()->getBlock('mageants.fbt.product.info.bundle.options');
                break;
            case 'downloadable':
                $html = $this->getLayout()->createBlock(\Magento\Downloadable\Block\Catalog\Product\Links::class)
                    ->setTemplate('Mageants_FrequentlyBought::product/view/type/downloadable/links.phtml');
                break;
        }
        if ($html) {
            return $html->setProduct($product)->toHtml();
        }

        return $html;
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
}
