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

namespace LeandroRosa\Core\Api;

interface ClientInterface
{
    /**
     * @param TransferInterface $transfer
     * @param GenericBuildInterface $responseBuild
     * @param GenericCommandInterface|null $responseValidator
     *
     * @return mixed
     */
    public function placeRequest(
        TransferInterface       $transfer,
        GenericBuildInterface   $responseBuild,
        GenericCommandInterface $responseValidator = null
    );
}
