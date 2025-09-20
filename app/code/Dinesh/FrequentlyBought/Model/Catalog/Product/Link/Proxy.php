<?php
/**
 * @category  Dinesh FrequentlyBought
 * @package   Dinesh_FrequentlyBought
 * @copyright Copyright (c) 2017 Dinesh
 * @author    Dinesh Team <support@dinesh.com>
 */

namespace Dinesh\FrequentlyBought\Model\Catalog\Product\Link;

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
