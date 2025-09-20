<?php
/**
 * @category  Dinesh FrequentlyBought
 * @package   Dinesh_FrequentlyBought
 * @copyright Copyright (c) 2017 Dinesh
 * @author    Dinesh Team <support@dinesh.com>
 */

namespace Dinesh\FrequentlyBought\Block\Product\View\Options\Type;

use Magento\Catalog\Block\Product\View\Options\Type\Date as Dates;

class Date extends Dates
{
    /**
     * Return Calendar Date html
     *
     * @return $calendarHtml
     */
    public function getCalendarDateHtml()
    {
        $calendarHtml = parent::getCalendarDateHtml();

        // $productId    = $this->getProduct()->getId();
        $replaceArray = [
            'options_' . $this->getOption()->getId() . '_date'
            => 'options_' . $this->getOption()->getId() . '_date',
            'options[' . $this->getOption()->getId() . '][date]'
            => 'options[' . $this->getOption()->getId() . '][date]'
        ];

        $calendarHtml = str_replace(array_keys($replaceArray), array_values($replaceArray), $calendarHtml);

        return $calendarHtml;
    }

    /**
     * Return selected html
     *
     * @param string $name
     * @param string $value
     *
     * @return object
     */
    protected function _getHtmlSelect($name, $value = null)
    {
        $select = parent::_getHtmlSelect($name, $value);

        // $productId   = $this->getProduct()->getId();
        $selectName  = 'options[' . $this->getOption()->getId() . '][' . $name . ']';
        $extraParams = $select->getExtraParams();
        $selName = $select->getName();
        $extraParams = str_replace('data-selector="'.$selName.'"', 'data-selector="'.$selectName.'"', $extraParams);
        $select->setId('options_' . $this->getOption()->getId() . '_' . $name)
        ->setName($selectName)
        ->setExtraParams($extraParams);

        return $select;
    }
}
