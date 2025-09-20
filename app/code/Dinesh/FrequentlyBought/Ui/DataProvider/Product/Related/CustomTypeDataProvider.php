<?php
/**
 * @category  Dinesh FrequentlyBought
 * @package   Dinesh_FrequentlyBought
 * @copyright Copyright (c) 2017 Dinesh
 * @author    Dinesh Team <support@dinesh.com>
 */

namespace Dinesh\FrequentlyBought\Ui\DataProvider\Product\Related;

use Magento\Catalog\Ui\DataProvider\Product\Related\AbstractDataProvider;

class CustomTypeDataProvider extends AbstractDataProvider
{
    /**
     * @inheritdoc
     */
    protected function getLinkType()
    {
        return 'customtype';
    }
}
