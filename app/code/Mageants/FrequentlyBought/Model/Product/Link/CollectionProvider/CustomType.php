<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Model\Product\Link\CollectionProvider;

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
