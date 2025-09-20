<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Model\Catalog\Product\Link;

use Magento\Catalog\Model\Product\Link\Proxy as Proxys;

class Proxy extends Proxys
{
    /**
     * @inheritdoc
     */
    public function useCustomtypeLinks()
    {
        return $this->_getSubject()->useCustomtypeLinks();
    }
}
