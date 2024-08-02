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

namespace LeandroRosa\Core\Http\Client;

use Psr\Log\LoggerInterface;
use Laminas\Soap\ClientFactory;
use Laminas\Soap\Client;
use LeandroRosa\Core\Api\ClientInterface;
use LeandroRosa\Core\Api\GenericBuildInterface;
use LeandroRosa\Core\Api\GenericCommandInterface;
use LeandroRosa\Core\Api\TransferInterface;

class Soap implements ClientInterface
{
    /**
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Soap constructor.
     *
     * @param ClientFactory $clientFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ClientFactory $clientFactory,
        LoggerInterface $logger
    ) {
        $this->clientFactory = $clientFactory;
        $this->logger = $logger;
    }

    /**
     * @inheirtDoc
     */
    public function placeRequest(
        TransferInterface $transfer,
        GenericBuildInterface $responseBuild,
        GenericCommandInterface $responseValidator = null
    ) {
        /** @var Client $client */
        $client = $this->clientFactory->create();
        $options = $transfer->getOptions() ?? [];
        $client->setWSDL($transfer->getUri())->setOptions($options);

        $response = $client->call($transfer->getMethod(), $transfer->getParams());
        $this->logRequestResponse($client);

        if ($responseValidator) {
            $responseValidator->execute(['response' => $response]);
        }

        return $responseBuild->build(['response' => $response, 'transfer' => $transfer]);
    }

    /**
     * @param Client $client
     *
     * @return void
     */
    protected function logRequestResponse(Client $client): void
    {
        $this->logger->debug("/// BEGIN REQUEST ///");
        $requestStr = $client->getLastRequest();
        $requestStr .= "\r\n";
        $this->logger->debug($requestStr);
        $this->logger->debug("/// END REQUEST ///");

        $this->logger->debug("/// BEGIN RESPONSE ///");
        $responseStr = $client->getLastResponse();
        $responseStr .= "\r\n";

        $this->logger->debug($responseStr);
        $this->logger->debug("/// END RESPONSE ///");
    }
}
