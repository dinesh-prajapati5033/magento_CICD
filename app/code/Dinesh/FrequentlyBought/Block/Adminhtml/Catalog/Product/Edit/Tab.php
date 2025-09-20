<?php
/**
 * @category  Dinesh FrequentlyBought
 * @package   Dinesh_FrequentlyBought
 * @copyright Copyright (c) 2017 Dinesh
 * @author    Dinesh Team <support@dinesh.com>
 */

namespace Dinesh\FrequentlyBought\Block\Adminhtml\Catalog\Product\Edit;

use Magento\Backend\Block\Template\Context;

use Magento\Backend\Block\Widget\Tab as Tabs;

class Tab extends Tabs
{
    /**
     * Constructor
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        if (!$this->_request->getParam('id') || !$this->_authorization->isAllowed('Magento_Review::reviews_all')) {
            $this->setCanShow(false);
        }
    }
}
