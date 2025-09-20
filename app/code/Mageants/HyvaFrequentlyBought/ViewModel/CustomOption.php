<?php

/**
 * @category Mageants HyvaFrequentlyBought
 * @package Mageants_HyvaFrequentlyBought
 * @copyright Copyright (c) 2023 Mageants
 * @author Mageants Team <info@mageants.com>
 */

declare(strict_types=1);

namespace Mageants\HyvaFrequentlyBought\ViewModel;

use Magento\Catalog\Block\Product\View\Options\Type\Select\CheckableFactory;
use Magento\Catalog\Model\Product\Option\Type\Date as DateCustomOptionConfig;
use Magento\Framework\Escaper;
use Mageants\FrequentlyBought\Helper\Data;

class CustomOption extends \Hyva\Theme\ViewModel\CustomOption
{
    /**
     * Template variable
     *
     * @var string
     */
    protected $multipleTemplate = 'Mageants_HyvaFrequentlyBought::product/fieldset/options/view/multiple.phtml';
    
    /**
     * @var string
     */
    protected $checkableTemplate = 'Mageants_HyvaFrequentlyBought::product/fieldset/options/view/checkable.phtml';

    /**
     * @var Mageants\FrequentlyBought\Helper\Data
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
