<?php

/**
 * @category Dinesh HyvaFrequentlyBought
 * @package Dinesh_HyvaFrequentlyBought
 * @copyright Copyright (c) 2023 Dinesh
 * @author Dinesh Team <info@dinesh.com>
 */

declare(strict_types=1);

namespace Dinesh\HyvaFrequentlyBought\Observer;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class FrequentlyBought implements ObserverInterface
{
    /**
     * @var ComponentRegistrar
     */
    private $componentRegistrar;

    /**
     * Constructor Dependencies
     *
     * @param ComponentRegistrar $componentRegistrar
     */
    public function __construct(ComponentRegistrar $componentRegistrar)
    {
        $this->componentRegistrar = $componentRegistrar;
    }

    /**
     * Main execute method
     *
     * @param  Observer $event
     * @return null
     */
    public function execute(Observer $event): void
    {
        $config = $event->getData('config');
        $extensions = $config->hasData('extensions') ? $config->getData('extensions') : [];

        $path = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, 'Dinesh_HyvaFrequentlyBought');

        $extensions[] = ['src' => substr($path, strlen(BP) + 1)];

        $config->setData('extensions', $extensions);
    }
}
