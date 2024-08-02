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

namespace LeandroRosa\Core\Model\Validators;


use LeandroRosa\Core\Helper\DorisCoreConfiguration;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

class RequestDorisCredentialsValidator
{
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    protected DorisCoreConfiguration $dorisCoreConfig;

    /**
     * @param StoreManagerInterface $storeManager
     * @param DorisCoreConfiguration $dorisCoreConfig
     */
    public function __construct(
        StoreManagerInterface  $storeManager,
        DorisCoreConfiguration $dorisCoreConfig
    )
    {
        $this->storeManager = $storeManager;
        $this->dorisCoreConfig = $dorisCoreConfig;
    }

    /**
     * @param RequestHttp $request
     *
     * @return array
     *
     * @throws AuthenticationException
     * @throws LocalizedException
     */
    public function validate(RequestHttp $request): array
    {
        $dorisApiKey = $request->getHeader('doris-api-key');
        $dorisSecretKey = $request->getHeader('doris-secret-key');

        if (empty($dorisApiKey) || empty($dorisSecretKey)) {
            throw new AuthenticationException(__('Header doris-api-key or doris-secret-key are empty.'));
        }

        $websiteId = (int)$request->getHeader('website-id', $this->storeManager->getWebsite()->getId());
        if (!$websiteId) {
            throw new LocalizedException(__('Website id is empty.'));
        }

        if (
            $dorisApiKey !== $this->dorisCoreConfig->getDorisApiKey('website', $websiteId) ||
            $dorisSecretKey !== $this->dorisCoreConfig->getDorisSecretKey('website', $websiteId)
        ) {
            throw new AuthenticationException(__('Invalid Doris credentials.'));
        }

        return ['doris_api_key' => $dorisApiKey, 'doris_secret_key' => $dorisSecretKey, 'website_id' => $websiteId];
    }
}
