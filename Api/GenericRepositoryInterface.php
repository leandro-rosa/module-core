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

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Model\AbstractModel;

interface GenericRepositoryInterface
{
    /**
     * @param mixed $entity
     *
     * @return mixed
     */
    public function save(AbstractModel $entity);

    /**
     * @param string|int|mixed $value
     * @param null|string $field
     *
     * @return mixed
     */
    public function get($value, $field = null);

    /**
     * @param mixed $entity
     *
     * @return bool
     */
    public function delete(AbstractModel $entity): bool;

    /**
     * @param int $entityId
     *
     * @return bool
     */
    public function deleteById($entityId): bool;

    /**
     * @param SearchCriteriaInterface $criteria
     *
     * @return bool
     */
    public function deleteByCriteria(SearchCriteriaInterface $criteria): bool;

    /**
     * @param SearchCriteriaInterface|null $criteria
     *
     * @return SearchResultsInterface
     */
    public function getList(?SearchCriteriaInterface $criteria ): SearchResultsInterface;

    /**
     * @return SearchCriteriaBuilder
     */
    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder;
}
