<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Plugin;

use Magento\Bundle\Block\Catalog\Product\View\Type\Bundle;
use Magento\Bundle\Model\Option;

class BundleRenderer
{
    /**
     * After Plugin for getOptionHtml method
     *
     * @param Bundle $subject
     * @param \Closure $proceed
     * @param Option $option
     *
     * @return $proceed
     */
    public function aroundGetOptionHtml(
        Bundle $subject,
        \Closure $proceed,
        Option $option
    ) {
        $renderersMap = (array)$subject->getData('renderers');
        if (isset($renderersMap[$option->getType()])) {
            $rendererBlockName = $renderersMap[$option->getType()];
            $renderer = $this->getChildBlock($rendererBlockName);
            if ($renderer) {
                return $renderer->setOption($option)->toHtml();
            }
            return $proceed($option);
        }
    }
}
