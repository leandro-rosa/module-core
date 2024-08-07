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

use LeandroRosa\Core\Api\CommandPoolInterface;
use LeandroRosa\Core\Api\GenericCommandInterface;
use Magento\Framework\Exception\NotFoundException;

use function __;

class CommandPool extends AbstractPool implements CommandPoolInterface
{
    /**
     * @param string $command
     *
     * @return mixed|GenericCommandInterface
     *
     * @throws NotFoundException
     */
    public function get($command)
    {
        if (!isset($this->commands[$command])) {
            throw new NotFoundException(__('Command %1 does not exist.', $command));
        }

        return $this->commands[$command];
    }
}
