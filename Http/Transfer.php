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

namespace LeandroRosa\Core\Http;

use Magento\Framework\DataObject;
use LeandroRosa\Core\Api\TransferInterface;

class Transfer extends DataObject implements TransferInterface
{
    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->getData(static::METHOD);
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        return $this->getData(static::URI);
    }

    /**
     * @inheritDoc
     */
    public function getOptions()
    {
        return $this->getData(static::OPTIONS) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function getParams()
    {
        return $this->getData(static::PARAMS);
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->getData(static::USERNAME);
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->getData(static::PASSWORD);
    }

    /**
     * @inheritDoc
     */
    public function setMethod($value)
    {
        return $this->setData(static::METHOD, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUri($value)
    {
        return $this->setData(static::URI, $value);
    }

    /**
     * @inheritDoc
     */
    public function setOptions(array $value)
    {
        return $this->setData(static::OPTIONS, $value);
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $value)
    {
        return $this->setData(static::PARAMS, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUsername($value)
    {
        return $this->setData(static::USERNAME, $value);
    }

    /**
     * @inheritDoc
     */
    public function setPassword($value)
    {
        return $this->setData(static::PASSWORD, $value);
    }
}
