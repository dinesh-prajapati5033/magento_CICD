<?php

/**
 * @category Dinesh HyvaFrequentlyBought
 * @package Dinesh_HyvaFrequentlyBought
 * @copyright Copyright (c) 2023 Dinesh
 * @author Dinesh Team <info@dinesh.com>
 */

namespace Dinesh\HyvaFrequentlyBought\Observer;

use Magento\Framework\View\DesignInterface;

class SetBlockPosition extends \Dinesh\FrequentlyBought\Observer\SetBlockPosition
{
    /**
     * @var \Dinesh\AlsoBought\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\View\DesignInterface
     */
    protected $theme;
 
    /**
     * Constructor function
     *
     * @param \Dinesh\FrequentlyBought\Helper\Data $helper
     * @param DesignInterface $theme
     */
    public function __construct(
        \Dinesh\FrequentlyBought\Helper\Data $helper,
        DesignInterface $theme
    ) {
        $this->theme = $theme;
        parent::__construct($helper);
    }

    /**
     * Use to move the blocks according to admin configuration
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->isHyva()) {
            $action = $observer->getEvent();
            $fullActionName = $action->getFullActionName();
            if ($fullActionName=='catalog_product_view' || $fullActionName=='checkout_cart_configure') {
                $configPath = 'fbt_section/bought_together_settings/fbt_section_position';
                $getPosition = explode(':', $this->_helper->getConfig($configPath));
                if ($getPosition[0] !='') {
                    if ($getPosition[0]=='content' && $getPosition[1]=='before') {
                        $myXml = '<move element="dinesh.frequently.bought.together.new"
                             destination="'.$getPosition[0].'" '.$getPosition[1].'="'.$getPosition[2].'"/>';
                    } elseif ($getPosition[0]=='content' && $getPosition[1]=='after') {
                        $myXml = '<move element="dinesh.frequently.bought.together.new"
                        destination="'.$getPosition[0].'" '.$getPosition[1].'="'.$getPosition[2].'"/>';
                    } else {
                        $myXml = '';
                    }
                    $layout = $observer->getEvent()->getLayout();
                    $layout->getUpdate()->addUpdate($myXml);
                    $layout->generateXml();
                }
            }
        }
        return $this;
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
