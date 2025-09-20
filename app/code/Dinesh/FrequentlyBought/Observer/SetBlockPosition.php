<?php
/**
 * @category  Dinesh FrequentlyBought
 * @package   Dinesh_FrequentlyBought
 * @copyright Copyright (c) 2017 Dinesh
 * @author    Dinesh Team <support@dinesh.com>
 */

namespace Dinesh\FrequentlyBought\Observer;

use Magento\Framework\Event\ObserverInterface;
use Dinesh\FrequentlyBought\Helper\Data;
use Magento\Framework\Event\Observer;

class SetBlockPosition implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * Construct
     *
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
         $this->_helper = $helper;
    }

    /**
     * Use to move the blocks according to admin configuration
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $action = $observer->getEvent();
        $fullActionName = $action->getFullActionName();
        if ($fullActionName=='catalog_product_view' || $fullActionName=='checkout_cart_configure') {
            $configPath = 'fbt_section/bought_together_settings/fbt_section_position';
            $getPosition = explode(':', $this->_helper->getConfig($configPath));
            if ($getPosition[0] !='') {
                if ($getPosition[0] =="replace") {
                    $myXml = '<referenceBlock name="'.$getPosition[1].'" remove="true"/>
                    <move element="dinesh.frequently.bought.together"
                    destination="content.aside" after="catalog.product.related"/>'
                    ;
                } else {
                    $myXml = '<move element="dinesh.frequently.bought.together"
                    destination="'.$getPosition[0].'" '.$getPosition[1].'="'.$getPosition[2].'"/>';
                }
                $layout = $observer->getEvent()->getLayout();
                $layout->getUpdate()->addUpdate($myXml);
                $layout->generateXml();
            }
        }
        return $this;
    }
}
