<?php
namespace App\Hydrate;

use App\Entity\ProductEntity;
use DateTime;
use Exception;

/**
 * Class ProductHyd
 * @package App\Hydrate
 */
class ProductHyd
{
    /**
     * @var ProductEntity
     */
    protected ProductEntity $entity;

    /**
     * @param ProductEntity $entity
     * @return $this
     */
    public function fromEntity(ProductEntity $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return ProductEntity
     */
    public function toEntity()
    {
        return $this->entity;
    }

    /**
     * @param array $arr
     * @return ProductEntity
     * @throws Exception
     */
    public function fromArray(array $arr)
    {
        return $this->arrayToEntity($arr);
    }

    /**
     * @param array $arr
     * @return ProductEntity
     * @throws Exception
     */
    private function arrayToEntity(array $arr)
    {
        $entity = new ProductEntity();

        if (isset($arr['id'])) {
            $entity->setId($arr['id']);
        }

        if (isset($arr['category'])) {
            $entity->setCategory($arr['category']);
        }

        if (isset($arr['productName'])) {
            $entity->setProductName($arr['productName']);
        }

        if (isset($arr['price'])) {
            $entity->setPrice($arr['price']);
        }

        if (isset($arr['description'])) {
            $entity->setDescription($arr['description']);
        }

        if (isset($arr['quantity'])) {
            $entity->setQuantity($arr['quantity']);
        }

        if (isset($arr['created_at'])) {
            if ($arr['created_at'] instanceof DateTime) {
                $entity->setCreatedAt($arr['created_at']);
            } else {
                $entity->setCreatedAt(new DateTime($arr['created_at']));
            }
        }

        return $entity;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->entityToArray($this->entity);
    }

    /**
     * @param ProductEntity $entity
     * @return array
     */
    private function entityToArray(ProductEntity $entity)
    {
        return [
            'id' => $entity->getId(),
            'category' => $entity->getCategory(),
            'productName' => $entity->getProductName(),
            'price' => $entity->getPrice(),
            'description' => $entity->getDescription(),
            'quantity' => $entity->getQuantity(),
            'created_at' => $entity->getCreatedAt(),
        ];
    }

    /**
     * @param array $array
     * @return array
     * @throws Exception
     */
    public function arrayOfArraysToArrayOfEntities(array $array)
    {
        $entities = [];
        foreach ($array as $item) {
            $entities[] = $this->arrayToEntity($item);
        }

        return $entities;
    }

    /**
     * @param array $entities
     * @return array
     */
    public function arrayOfEntitiesToArrayOfArrays(array $entities)
    {
        $arr = [];
        foreach ($entities as $entity) {
            $arr[] = $this->entityToArray($entity);
        }

        return $arr;
    }
}
