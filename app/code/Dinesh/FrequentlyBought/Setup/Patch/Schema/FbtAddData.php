<?php
/**
 * @category  Dinesh FrequentlyBought
 * @package   Dinesh_FrequentlyBought
 * @copyright Copyright (c) 2017 Dinesh
 * @author    Dinesh Team <support@dinesh.com>
 */
declare(strict_types=1);
namespace Dinesh\FrequentlyBought\Setup\Patch\Schema;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class FbtAddData implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * EnableSegmentation constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        try {
            $setup = $this->moduleDataSetup;
            $setup->startSetup();
            $fbt = \Dinesh\FrequentlyBought\Model\Catalog\Product\Link::LINK_TYPE_FREQUENTLYBOUGHTTOGETHER;
            $conn = $setup->getConnection();
            $select = $conn->select('code')->from('catalog_product_link_type')->where('link_type_id = ?', [$fbt]);
            $extensionAvailable = $conn->fetchAll($select);
            $codeValue = "";

            foreach ($extensionAvailable as $extensionData) {
                $codeValue = $extensionData['code'];
            }

            if ($codeValue == 'frequentlyboughttogether') {
                $codeValue = 'frequentlyboughttogether';
            } else {
                $data = [
                    [
                        'link_type_id' => $fbt,
                        'code' => 'frequentlyboughttogether'
                    ]
                ];
                $setup->getConnection()->insertArray(
                    $setup->getTable('catalog_product_link_type'),
                    ['link_type_id', 'code'],
                    $data
                );

                $data = [
                    [
                        'link_type_id' => $fbt,
                        'product_link_attribute_code' => 'position',
                        'data_type' => 'int',
                    ]
                ];
                $setup->getConnection()->insertArray(
                    $setup->getTable('catalog_product_link_attribute'),
                    ['link_type_id', 'product_link_attribute_code', 'data_type'],
                    $data
                );
            }

            $setup->endSetup();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
