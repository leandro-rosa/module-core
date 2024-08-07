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

namespace LeandroRosa\Core\DorisLoggerHandler;

use Magento\Framework\Logger\Handler\Exception as MagentoLoggerException;
use Monolog\Logger;

class Exception extends MagentoLoggerException
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/doris-exception.log';

    /**
     * @var int
     */
    protected $loggerType = Logger::INFO;
}

