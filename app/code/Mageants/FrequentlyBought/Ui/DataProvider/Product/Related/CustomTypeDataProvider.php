<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Ui\DataProvider\Product\Related;

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
