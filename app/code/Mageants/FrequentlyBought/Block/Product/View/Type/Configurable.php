<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Block\Product\View\Type;

use Magento\Swatches\Block\Product\Renderer\Configurable as Configurables;

class Configurable extends Configurables
{
    protected const FBT_RENDERER_TEMPLATE = 'Mageants_FrequentlyBought::product/view/type/options/configurable.phtml';

    /**
     * Return template file
     *
     * @return template
     */
    protected function getRendererTemplate()
    {
        return self::FBT_RENDERER_TEMPLATE;
    }
}
