<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Controller\Adminhtml\Product;

use Magento\Backend\App\Action\Context;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Catalog\Controller\Adminhtml\Product;

class Customtype extends Product
{
    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * Construct
     *
     * @param Context $context
     * @param Builder $productBuilder
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Context $context,
        Builder $productBuilder,
        LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context, $productBuilder);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Get products grid and serializer block
     *
     * @return Layout
     */
    public function execute()
    {
        $this->productBuilder->build($this->getRequest());
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('catalog.product.edit.tab.customtype')
            ->setProductsCustomtype($this->getRequest()->getPost('products_customtype', null));
        return $resultLayout;
    }
}
