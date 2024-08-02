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

namespace LeandroRosa\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class DorisCoreConfiguration extends AbstractHelper
{
    const DORIS_URI_CONFIG_PATH = 'doris_setup/general/doris_uri';
    const DORIS_API_KEY_CONFIG_PATH = 'doris_setup/general/doris_api_key';
    const DORIS_SECRET_KEY_CONFIG_PATH = 'doris_setup/general/doris_secret_key';

    /**
     * @param ?string $scopeType
     * @param ?string $scopeCode
     *
     * @return ?string
     */
    public function getUri($scopeType = 'website', $scopeCode = null): ?string
    {
        return $this->scopeConfig->getValue(static::DORIS_URI_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param ?string $scopeType
     * @param ?string $scopeCode
     *
     * @return ?string
     */
    public function getDorisApiKey($scopeType = 'website', $scopeCode = null): ?string
    {
        return $this->scopeConfig->getValue(static::DORIS_API_KEY_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param ?string $scopeType
     * @param ?string $scopeCode
     *
     * @return ?string
     */
    public function getDorisSecretKey($scopeType = 'website', $scopeCode = null): ?string
    {
        return $this->scopeConfig->getValue(static::DORIS_SECRET_KEY_CONFIG_PATH, $scopeType, $scopeCode);
    }
}
