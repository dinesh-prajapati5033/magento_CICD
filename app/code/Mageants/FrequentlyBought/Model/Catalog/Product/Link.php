<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Model\Catalog\Product;

use Magento\Catalog\Model\Product\Link as Links;

class Link extends Links
{
    public const LINK_TYPE_FREQUENTLYBOUGHTTOGETHER = 6;

    /**
     * Set Custom Type Links
     *
     * @return Links
     */
    public function useCustomtypeLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_FREQUENTLYBOUGHTTOGETHER);
        return $this;
    }

    /**
     * Save data for product relations
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return Links
     */
    public function saveProductRelations($product)
    {
        parent::saveProductRelations($product);

        $data = $product->getCustomtypeData();
        if (!($data===null)) {
            $this->_getResource()->saveProductLinks($product->getId(), $data, self::LINK_TYPE_FREQUENTLYBOUGHTTOGETHER);
        }
        return $this;
    }
}
