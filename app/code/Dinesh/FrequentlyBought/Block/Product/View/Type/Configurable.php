<?php
/**
 * @category  Dinesh FrequentlyBought
 * @package   Dinesh_FrequentlyBought
 * @copyright Copyright (c) 2017 Dinesh
 * @author    Dinesh Team <support@dinesh.com>
 */

namespace Dinesh\FrequentlyBought\Block\Product\View\Type;

use Magento\Swatches\Block\Product\Renderer\Configurable as Configurables;

class Configurable extends Configurables
{
    protected const FBT_RENDERER_TEMPLATE = 'Dinesh_FrequentlyBought::product/view/type/options/configurable.phtml';

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
