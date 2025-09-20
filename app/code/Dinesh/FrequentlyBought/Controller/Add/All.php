<?php
/**
 * @category  Dinesh FrequentlyBought
 * @package   Dinesh_FrequentlyBought
 * @copyright Copyright (c) 2017 Dinesh
 * @author    Dinesh Team <support@dinesh.com>
 */

namespace Dinesh\FrequentlyBought\Controller\Add;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Helper\Cart as CartHelper;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Escaper;
use Magento\Store\Model\StoreManagerInterface;
use Dinesh\FrequentlyBought\Helper\Data;
use Psr\Log\LoggerInterface;

class All extends \Magento\Checkout\Controller\Cart\Add
{
    /**
     * @var Data
     */
    protected $fbtDataHelper;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $cartHelper;

    /**
     * Construct
     *
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $checkoutSession
     * @param StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     * @param Cart $cart
     * @param ProductRepositoryInterface $productRepository
     * @param Data $fbtDataHelper
     * @param LoggerInterface $logger
     * @param Escaper $escaper
     * @param CartHelper $cartHelper
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        Cart $cart,
        ProductRepositoryInterface $productRepository,
        Data $fbtDataHelper,
        LoggerInterface $logger,
        Escaper $escaper,
        CartHelper $cartHelper
    ) {
        $this->fbtDataHelper = $fbtDataHelper;
        $this->escaper       = $escaper;
        $this->logger        = $logger;
        $this->cartHelper    = $cartHelper;

        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart,
            $productRepository
        );
    }

    /**
     * Add all selected product to shopping cart
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if (!$this->fbtDataHelper->isEnabled()) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
        $storeId = $this->_storeManager->getStore()->getId();
        $params  = $this->getRequest()->getParams();
        $customOptions = $this->getRequest()->getParam('options');
        
        try {
            if (empty($params['dinesh_fbt'])) {
                $this->messageManager->addError(
                    $this->escaper->escapeHtml(__('Please select product(s).'))
                );
                
                return $this->goBack();
            }
            $productsName = [];
            foreach ($params['dinesh_fbt'] as $productId => $value) {
                $paramsFbt            = [];
                $product              = $this->productRepository->getById($productId, false, $storeId);
                $productType          = $product->getTypeId();
                $paramsFbt['product'] = $productId;
                switch ($productType) {
                    case 'configurable':
                        if (isset($params['super_attribute']) && isset($params['super_attribute'][$productId])) {
                            $paramsFbt['super_attribute'] = $params['super_attribute'][$productId];
                        }
                        break;
                    case 'grouped':
                        if (isset($params['super_group']) && isset($params['super_group'][$productId])) {
                            $paramsFbt['super_group'] = $params['super_group'][$productId];
                        }
                        break;
                    case 'bundle':
                        if (isset($params['bundle_option']) && isset($params['bundle_option'][$productId])) {
                            $paramsFbt['bundle_option']     = $params['bundle_option'][$productId];
                            $paramsFbt['bundle_option_qty'] = $params['bundle_option_qty'][$productId];
                        }
                        break;
                    case 'downloadable':
                        if (isset($params['links']) && isset($params['links'][$productId])) {
                            $paramsFbt['links'] = $params['links'][$productId];
                        }
                        break;
                    default:
                        break;
                }
                if ($customOptions) {
                    $paramsFbt['options'] = $customOptions;
                }
                $productsName[] = '"' . $product->getName() . '"';
                $this->cart->addProduct($product, $paramsFbt);
            }
            $this->cart->save();
            if (!$this->_checkoutSession->getNoCartRedirect(true)) {
                if (!$this->cart->getQuote()->getHasError()) {
                    $message = __(
                        'You added %1 to your shopping cart.',
                        join(', ', $productsName)
                    );
                    $this->messageManager->addSuccessMessage($message);
                }

                return $this->goBack(null, $product);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            if ($this->_checkoutSession->getUseNotice(true)) {
                $this->messageManager->addNotice(
                    $this->escaper->escapeHtml($e->getMessage())
                );
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->messageManager->addError(
                        $this->escaper->escapeHtml($message)
                    );
                }
            }

            $url = $this->_checkoutSession->getRedirectUrl(true);

            if (!$url) {
                $cartUrl = $this->cartHelper->getCartUrl();
                $url     = $this->_redirect->getRedirectUrl($cartUrl);
            }

            return $this->goBack($url);
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
            $this->logger->critical($e);
            return $this->goBack();
        }
    }
}
