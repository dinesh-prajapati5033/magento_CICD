<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    protected const CONFIG_MODULE_PATH = 'frequentlybought';

    /**
     * @var isArea
     */
    protected $isArea = [];

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Construct
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->objectManager = $objectManager;
        $this->storeManager  = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * GetFbtStatus
     *
     * @return boolean
     */
    public function getFBTStatus()
    {
        $fbtStatus = $this->_scopeConfig->getValue(
            'fbt_section/bought_together_settings/enable_disable',
            ScopeInterface::SCOPE_STORE
        );
        return $fbtStatus;
    }

    /**
     * Return Media Helper object
     *
     * @return object
     */
    public function getMediaHelper()
    {
        return $this->objectManager->get(Media::class);
    }

    /**
     * Return separator icon image src
     *
     * @return string
     */
    public function getIcon()
    {
        $icon = $this->getConfigGeneral('separator_image');
        if (!$icon) {
            return false;
        }

        return $this->getMediaHelper()->resizeImage($icon, 30);
    }

    /**
     * Check if frequently bought extension enabled
     *
     * @return true
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * Return Configuration value
     *
     * @param string $code
     * @param string $storeId
     *
     * @return string|null
     */
    public function getConfigGeneral($code = '', $storeId = null)
    {
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getConfigValue(static::CONFIG_MODULE_PATH . '/general' . $code, $storeId);
    }

    /**
     * Get Configuration value
     *
     * @param string $field
     * @param string $scopeValue
     * @param string $scopeType
     *
     * @return string|null
     */
    public function getConfigValue($field, $scopeValue = null, $scopeType = ScopeInterface::SCOPE_STORE)
    {
        //if (!$this->isArea() && is_null($scopeValue)) {
        if (!$this->isArea() && $scopeValue === null) {
            /** @var \Magento\Backend\App\Config $backendConfig */
            if (!$this->backendConfig) {
                $this->backendConfig = $this->objectManager->get(\Magento\Backend\App\ConfigInterface::class);
            }

            return $this->backendConfig->getValue($field);
        }

        return $this->scopeConfig->getValue($field, $scopeType, $scopeValue);
    }

    /**
     * Check area condition
     *
     * @param string $area
     *
     * @return string
     */
    public function isArea($area = Area::AREA_FRONTEND)
    {
        if (!isset($this->isArea[$area])) {
            /** @var \Magento\Framework\App\State $state */
            $state = $this->objectManager->get(\Magento\Framework\App\State::class);

            try {
                $this->isArea[$area] = ($state->getAreaCode() == $area);
            } catch (\Exception $e) {
                $this->isArea[$area] = false;
            }
        }

        return $this->isArea[$area];
    }

    // phpcs:disable
    /**
     * Return Encoded value
     *
     * @param string $valueToEncode
     *
     * @return string
     */
    public static function jsonEncode($valueToEncode)
    {
        try {
            $encodeValue = self::getJsonHelper()->jsonEncode($valueToEncode);
        } catch (\Exception $e) {
            $encodeValue = '{}';
        }

        return $encodeValue;
    }

    /**
     * Return ObjectManager Instance
     *
     * @return ObjectManager
     */
    public static function getJsonHelper()
    {
        return ObjectManager::getInstance()->get(JsonHelper::class);
    }
    // phpcs:enable

    /**
     * Check and return Configuration value
     *
     * @param string $config_path
     *
     * @return string|null
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            ScopeInterface::SCOPE_STORE
        );
    }
}
