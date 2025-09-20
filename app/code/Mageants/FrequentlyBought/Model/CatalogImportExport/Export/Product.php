<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Model\CatalogImportExport\Export;

use Magento\CatalogImportExport\Model\Export\Product as Products;

class Product extends Products
{
    /**
     * @inheritdoc
     */
    protected function setHeaderColumns($customOptionsData, $stockItemRows)
    {
        if (!$this->_headerColumns) {
            parent::setHeaderColumns($customOptionsData, $stockItemRows);

            $this->_headerColumns = array_merge(
                $this->_headerColumns,
                [
                    'customtype_skus',
                    'customtype_position'
                ]
            );
        }
    }
}
