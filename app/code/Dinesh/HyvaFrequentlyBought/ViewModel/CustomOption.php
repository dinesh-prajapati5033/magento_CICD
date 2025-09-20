<?php

/**
 * @category Dinesh HyvaFrequentlyBought
 * @package Dinesh_HyvaFrequentlyBought
 * @copyright Copyright (c) 2023 Dinesh
 * @author Dinesh Team <info@dinesh.com>
 */

declare(strict_types=1);

namespace Dinesh\HyvaFrequentlyBought\ViewModel;

use Magento\Catalog\Block\Product\View\Options\Type\Select\CheckableFactory;
use Magento\Catalog\Model\Product\Option\Type\Date as DateCustomOptionConfig;
use Magento\Framework\Escaper;
use Dinesh\FrequentlyBought\Helper\Data;

class CustomOption extends \Hyva\Theme\ViewModel\CustomOption
{
    /**
     * Template variable
     *
     * @var string
     */
    protected $multipleTemplate = 'Dinesh_HyvaFrequentlyBought::product/fieldset/options/view/multiple.phtml';
    
    /**
     * @var string
     */
    protected $checkableTemplate = 'Dinesh_HyvaFrequentlyBought::product/fieldset/options/view/checkable.phtml';

    /**
     * @var Dinesh\FrequentlyBought\Helper\Data
     */
    protected $helperData;
    /**
     * Constructor function
     *
     * @param CheckableFactory $checkableFactory
     * @param DateCustomOptionConfig $dateCustomOptionConfig
     * @param Escaper $escaper
     * @param Data $helperData
     */
    public function __construct(
        CheckableFactory $checkableFactory,
        DateCustomOptionConfig $dateCustomOptionConfig,
        Escaper $escaper,
        Data $helperData
    ) {
        $this->helperData = $helperData;
        parent::__construct(
            $checkableFactory,
            $dateCustomOptionConfig,
            $escaper
        );
    }
    /**
     * Helper function
     *
     * @return mixed
     */
    public function getHelper()
    {
        return $this->helperData;
    }
}
