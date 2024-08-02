<?php
/**
 * Leandro Rosa
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Doris Module to newer
 * versions in the future. If you wish to customize it for your
 * needs please refer to https://developer.adobe.com/commerce/docs/ for more information.
 *
 * @category LeandroRosa
 *
 * @copyright Copyright (c) 2024 Leandro Rosa (https:www.rosa-planet.com.br)
 *
 * @author Leandro Rosa <dev.leandrorosa@gmail.com>
 */
declare(strict_types=1);

namespace LeandroRosa\Core\Model\Command;

use Magento\Framework\ObjectManager\TMapFactory;
use LeandroRosa\Core\Api\GenericCommandInterface;

abstract class AbstractPool
{
    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @var TMapFactory
     */
    protected $tMapFactory;

    /**
     * CommandPool constructor.
     *
     * @param TMapFactory $tMapFactory
     * @param array $commands
     * @param string $type
     */
    public function __construct(
        TMapFactory $tMapFactory,
        array $commands = [],
        string $type = GenericCommandInterface::class
    ) {
        $this->tMapFactory = $tMapFactory;
        $this->commands = $commands;
        $this->init($type);
    }

    /**
     * @param string $type
     *
     * @return void
     */
    protected function init ($type): void
    {
        $this->commands = $this->tMapFactory->create([
            'array' => $this->commands,
            'type' => $type
        ]);
    }
}
