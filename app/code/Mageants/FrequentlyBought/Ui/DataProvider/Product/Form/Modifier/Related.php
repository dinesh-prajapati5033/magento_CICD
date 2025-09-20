<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Ui\DataProvider\Product\Form\Modifier;

use Magento\Ui\Component\Form\Fieldset;
use Mageants\FrequentlyBought\Helper\Data as FbtHelper;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Api\ProductLinkRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Eav\Api\AttributeSetRepositoryInterface;


class Related extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Related
{
    protected const DATA_SCOPE_CUSTOMTYPE = 'customtype';

    /**
     * @var string
     */
    private static $previousGroup = 'search-engine-optimization';

    /**
     * @var int
     */
    private static $sortOrder = 90;

    /**
     * @var FbtHelper
     */
    private $fbtHelper;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var ProductLinkRepositoryInterface
     */
    protected $productLinkRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var Status
     */
    protected $status;

    /**
     * @var AttributeSetRepositoryInterface
     */
    protected $attributeSetRepository;

    /**
     * @var string
     */
    protected $scopeName;

    /**
     * @var string
     */
    protected $scopePrefix;

    /**
     * Related constructor.
     *
     * @param FbtHelper $fbtHelper
     */
    public function __construct(
        FbtHelper $fbtHelper,
        UrlInterface $urlBuilder,
        LocatorInterface $locator,
        ProductLinkRepositoryInterface $productLinkRepository,
        ProductRepositoryInterface $productRepository,
        ImageHelper $imageHelper,
        Status $status,
        AttributeSetRepositoryInterface $attributeSetRepository,
        $scopeName = '',
        $scopePrefix = ''
    ) {
        $this->fbtHelper = $fbtHelper;
        $this->urlBuilder = $urlBuilder;
        $this->locator = $locator;
        $this->productLinkRepository = $productLinkRepository;
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
        $this->status = $status;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->scopeName = $scopeName;
        $this->scopePrefix = $scopePrefix;
    }

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta)
    {
        if ($this->fbtHelper->getFBTStatus()) {
            $meta = array_replace_recursive(
                $meta,
                [
                    static::GROUP_RELATED => [
                        'children' => [
                            $this->scopePrefix . static::DATA_SCOPE_RELATED => $this->getRelatedFieldset(),
                            $this->scopePrefix . static::DATA_SCOPE_UPSELL => $this->getUpSellFieldset(),
                            $this->scopePrefix . static::DATA_SCOPE_CROSSSELL => $this->getCrossSellFieldset(),
                            $this->scopePrefix . static::DATA_SCOPE_CUSTOMTYPE => $this->getCustomTypeFieldset()
                        ],
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'label' => __('Related Products, Up-Sells, Cross-Sells and Frequently Bought Together'),
                                    'collapsible' => true,
                                    'componentType' => Fieldset::NAME,
                                    'dataScope' => static::DATA_SCOPE,
                                    'sortOrder' =>
                                        $this->getNextGroupSortOrder(
                                            $meta,
                                            self::$previousGroup,
                                            self::$sortOrder
                                        ),
                                ],
                            ],

                        ],
                    ],
                ]
            );
        } else {
            $meta = array_replace_recursive(
                $meta,
                [
                    static::GROUP_RELATED => [
                        'children' => [
                            $this->scopePrefix . static::DATA_SCOPE_RELATED => $this->getRelatedFieldset(),
                            $this->scopePrefix . static::DATA_SCOPE_UPSELL => $this->getUpSellFieldset(),
                            $this->scopePrefix . static::DATA_SCOPE_CROSSSELL => $this->getCrossSellFieldset()
                        ],
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'label' => __('Related Products, Up-Sells, Cross-Sells'),
                                    'collapsible' => true,
                                    'componentType' => Fieldset::NAME,
                                    'dataScope' => static::DATA_SCOPE,
                                    'sortOrder' =>
                                        $this->getNextGroupSortOrder(
                                            $meta,
                                            self::$previousGroup,
                                            self::$sortOrder
                                        ),
                                ],
                            ],

                        ],
                    ],
                ]
            );
        }

        return $meta;
    }

    /**
     * Prepares config for the Custom type products fieldset
     *
     * @return array
     */
    protected function getCustomTypeFieldset()
    {
        $content = __(
            'Frequently bought together products are shown to product details page.'
        );

        return [
            'children' => [
                'button_set' => $this->getButtonSet(
                    $content,
                    __('Add Frequently Bought Together Products'),
                    $this->scopePrefix . static::DATA_SCOPE_CUSTOMTYPE
                ),
                'modal' => $this->getGenericModal(
                    __('Add Frequently Bought Together Products'),
                    $this->scopePrefix . static::DATA_SCOPE_CUSTOMTYPE
                ),
                static::DATA_SCOPE_CUSTOMTYPE => $this->getGrid($this->scopePrefix . static::DATA_SCOPE_CUSTOMTYPE),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Frequently Bought Together'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 90,
                    ],
                ],
            ]
        ];
    }

    /**
     * Retrieve all data scopes
     *
     * @return array
     */
    protected function getDataScopes()
    {
        return [
            static::DATA_SCOPE_RELATED,
            static::DATA_SCOPE_CROSSSELL,
            static::DATA_SCOPE_UPSELL,
            static::DATA_SCOPE_CUSTOMTYPE
        ];
    }
}
