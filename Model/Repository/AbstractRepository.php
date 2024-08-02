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

namespace LeandroRosa\Core\Model\Repository;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InvalidArgumentException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb as ResourceModel;
use LeandroRosa\Core\Api\GenericRepositoryInterface;

abstract class AbstractRepository implements GenericRepositoryInterface
{
    /**
     * Factory for creating new instances of the associated entity.
     * This property should be set in the child class to specify the entity factory.
     */
    protected $entityFactory;

    /**
     * Factory for creating new instances of the associated collection.
     * This property should be set in the child class to specify the collection factory.
     */
    protected $collectionFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var ResourceModel
     */
    protected ResourceModel $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected CollectionProcessorInterface $collectionProcessor;

    /**
     * @var SearchResultsInterfaceFactory
     */
    protected SearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ResourceModel $resource
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        SearchCriteriaBuilder         $searchCriteriaBuilder,
        ResourceModel                 $resource,
        CollectionProcessorInterface  $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory
    )
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->resource = $resource;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheirtDoc
     *
     * @throws CouldNotSaveException
     */
    public function save($entity)
    {
        try {
            if (is_array($entity)) {
                $data = $entity;
                $entity = $this->entityFactory->create();
                $entity->setData($data);
            }
            $this->resource->save($entity);
            return $entity;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
    }

    /**
     * @inheirtDoc
     *
     * @throws InvalidArgumentException
     * @throws NoSuchEntityException
     */
    public function get($value, $field = null)
    {
        if (!$this->entityFactory) {
            throw new InvalidArgumentException(__('Entity factory was not defined.'));
        }

        $entity = $this->entityFactory->create();
        $this->resource->load($entity, $value, $field);

        if (!$entity->getId()) {
            throw new NoSuchEntityException(__('The entity with the "%1" %2 doesn\'t exist.', $value, $field ?: 'ID'));
        }

        return $entity;
    }

    /**
     * @inheirtDoc
     *
     * @throws CouldNotDeleteException
     */
    public function delete(AbstractModel $entity): bool
    {
        try {
            $this->resource->delete($entity);
            return true;
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }

    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($entityId): bool
    {
        $entity = $this->get($entityId);
        return $this->delete($entity);
    }

    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException
     * @throws CouldNotDeleteException
     */
    public function deleteByCriteria(SearchCriteriaInterface $criteria): bool
    {
        $items = $this->getList($criteria);
        foreach ($items as $item) {
            $this->delete($item);
        }

        return true;
    }

    /**
     * @inheirtDoc
     *
     * @throws InvalidArgumentException
     */
    public function getList(?SearchCriteriaInterface $criteria = null): SearchResultsInterface
    {
        if (!$this->collectionFactory) {
            throw new InvalidArgumentException(__('Collection factory was not defined.'));
        }

        if (!$criteria) {
            $criteria =   $this->searchCriteriaBuilder->create();
        }
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);

        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilder;
    }
}
