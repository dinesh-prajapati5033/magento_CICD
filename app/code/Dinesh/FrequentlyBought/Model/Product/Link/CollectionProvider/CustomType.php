<?php
/**
 * @category  Dinesh FrequentlyBought
 * @package   Dinesh_FrequentlyBought
 * @copyright Copyright (c) 2017 Dinesh
 * @author    Dinesh Team <support@dinesh.com>
 */

namespace Dinesh\FrequentlyBought\Model\Product\Link\CollectionProvider;

use Magento\Catalog\Model\ProductLink\CollectionProviderInterface;
use Magento\Catalog\Model\Product;

class CustomType implements CollectionProviderInterface
{
    /**
     * @inheritdoc
     */
    public function getLinkedProducts(Product $product)
    {
        $products = $product->getCustomtypeProducts();

        if (!isset($products)) {
            return [];
        }

        return $products;
    }
}
