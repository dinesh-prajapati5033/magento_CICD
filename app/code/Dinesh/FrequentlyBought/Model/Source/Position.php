<?php
/**
 * @category  Dinesh FrequentlyBought
 * @package   Dinesh_FrequentlyBought
 * @copyright Copyright (c) 2017 Dinesh
 * @author    Dinesh Team <support@dinesh.com>
 */

namespace Dinesh\FrequentlyBought\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class Position implements ArrayInterface
{
    /**
     * Return Options Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __('Content Top'),
                'value' => 'content:before:-',
            ],
            [
                'label' => __('Content Bottom'),
                'value' => 'content:after:-',
            ],
            [
                'label' => __('Before Related Product Block'),
                'value' => 'content.aside:before:catalog.product.related',
            ],
            [
                'label' => __('Replace with Related Product Block'),
                'value' => 'replace:catalog.product.related',
            ],
            [
                'label' => __('After Related Product Block'),
                'value' => 'content.aside:after:catalog.product.related',
            ],
            [
                'label' => __('Before Up-sell Product Block'),
                'value' => 'content.aside:before:product.info.upsell',
            ],
            [
                'label' => __('Replace with Up-sell Block'),
                'value' => 'replace:product.info.upsell',
            ],
            [
                'label' => __('After Up-sell Block'),
                'value' => 'content.aside:after:product.info.upsell',
            ],
        ];
        return $options;
    }
}
