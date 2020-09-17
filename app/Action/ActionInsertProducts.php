<?php
namespace App\Action;


use App\DAL\Repository\ProductRepository;

class ActionInsertProducts
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $repository;

    /**
     * ActionInsertOrUpdateProducts constructor.
     * @param ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $entities
     * @return bool
     */
    public function __invoke(array $entities)
    {
        return $this->repository->bulkInsert($entities);
    }
}
