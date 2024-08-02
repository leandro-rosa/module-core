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

use LeandroRosa\Core\Api\GenericBuildInterface;
use LeandroRosa\Core\Api\GenericCommandInterface;
use LeandroRosa\Core\Http\Transfer;
use Exception;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use LeandroRosa\Core\Api\ClientInterface;
use LeandroRosa\Core\Api\TransferInterface;

class Rest implements ClientInterface
{
    /**
     * @var ClientFactory
     */
    protected ClientFactory $clientFactory;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var Json
     */
    protected Json $serializer;

    /**
     * @var DataObjectFactory
     */
    protected DataObjectFactory $dataObjectFactory;

    /**
     * @param ClientFactory $clientFactory
     * @param LoggerInterface $logger
     * @param Json $serializer
     * @param DataObjectFactory $dataObjectFactory
     */
    public function __construct(
        ClientFactory     $clientFactory,
        LoggerInterface   $logger,
        Json              $serializer,
        DataObjectFactory $dataObjectFactory,
    )
    {
        $this->clientFactory = $clientFactory;
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * @param TransferInterface $transfer
     * @param GenericBuildInterface $responseBuild
     * @param GenericCommandInterface|null $responseValidator
     *
     * @return mixed
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function placeRequest(
        TransferInterface       $transfer,
        GenericBuildInterface   $responseBuild = null,
        GenericCommandInterface $responseValidator = null
    )
    {
        $client = $this->clientFactory->create();
        $options = $transfer->getOptions();

        try {
            if (!empty($option['body'])) {
                $payload = $option['body'];
                if (is_array($payload)) {
                    $options['body'] = $this->serializer->serialize($payload);
                }
            }
            $response = $client->request($transfer->getMethod(), $transfer->getUri(), $options);
            $responseData = $response->getBody()->getContents();
        } catch (Exception $exception) {
            $dorisErrorMessage = $exception?->getResponse()?->getBody()?->getContents() ?: $exception->getMessage();

            if ($dorisErrorMessage) {
                $this->logger->error('Error Doris Request: ', [
                    'error_message' => $dorisErrorMessage,
                    'error_code' => $exception->getCode(),
                    'error_file' => $exception->getFile() . ':' . $exception->getLine(),
                    'request_data' => $transfer->getData(),
                ]);
                throw new LocalizedException(__($dorisErrorMessage), $exception);
            }

            $this->logger->error('Error Doris Request: ', [
                'error_message' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'error_file' => $exception->getFile() . ':' . $exception->getLine(),
                'request_data' => $transfer->getData(),
            ]);
            throw $exception;
        }
        try {
            $data = $this->serializer->unserialize($responseData);
            $dataObject = $this->dataObjectFactory->create(['data' => $data]);
        } catch (Exception $exception) {
            $this->logRequestResponse($transfer, $response, [
                'response' => $responseData,
                'error_message' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'error_file' => $exception->getFile() . ':' . $exception->getLine(),
            ]);
            return $responseData;
        }

        $this->logRequestResponse($transfer, $response, $data);
        $responseValidator?->execute(['response' => $data]);

        if (!$responseBuild) {
            return !empty($dataObject->getData()) ? $dataObject : $data;
        }

        return $responseBuild->build(['response' => $data]);
    }

    /**
     * @param Transfer $transfer
     * @param ResponseInterface $response
     * @param $responseData
     *
     * @return void
     */
    protected function logRequestResponse(
        Transfer          $transfer,
        ResponseInterface $response,
                          $responseData
    ): void
    {
        $this->logger->debug('Doris Request', [
            'request_data' => $transfer->getData(),
            'response_data' => $responseData,
            'response_header' => $response->getHeaders()
        ]);
    }
}
