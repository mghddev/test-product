<?php
namespace App\Entity;

use DateTime;

/**
 * Class ProductEntity
 * @package App\Entity
 */
class ProductEntity
{
    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * @var string|null
     */
    protected ?string $category = null;

    /**
     * @var string|null
     */
    protected ?string $productName = null;

    /**
     * @var int|null
     */
    protected ?int $price = null;

    /**
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * @var int|null
     */
    protected ?int $quantity = null;

    /**
     * @var DateTime|null
     */
    protected ?DateTime $created_at = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ProductEntity
     */
    public function setId(?int $id): ProductEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     * @return ProductEntity
     */
    public function setCategory(?string $category): ProductEntity
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     * @param string|null $productName
     * @return ProductEntity
     */
    public function setProductName(?string $productName): ProductEntity
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int|null $price
     * @return ProductEntity
     */
    public function setPrice(?int $price): ProductEntity
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): ProductEntity
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     * @return ProductEntity
     */
    public function setQuantity(?int $quantity): ProductEntity
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    /**
     * @param DateTime|null $created_at
     * @return $this
     */
    public function setCreatedAt(?DateTime $created_at): ProductEntity
    {
        $this->created_at = $created_at;
        return $this;
    }

}
