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

use LeandroRosa\Core\Api\GenericCommandInterface;
use Magento\Framework\Debug;
use Psr\Log\LoggerInterface;

class CommandFallBack implements GenericCommandInterface
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @retrurn array
     */
    public function execute(array $subject = [])
    {
        $trace = Debug::backtrace(true, false, true);
        $this->logger->warning('Command redirect to command fallback', ['trace' => $trace]);
        return [];
    }
}
