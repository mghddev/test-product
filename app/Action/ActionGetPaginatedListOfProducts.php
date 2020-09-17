<?php
namespace App\Action;


use App\DAL\Repository\ProductRepository;
use App\Lib\Pagination\ListCriteria;
use App\Lib\Pagination\PaginatedEntityList;
use Exception;

class ActionGetPaginatedListOfProducts
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * ActionGetPaginatedListOfProducts constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param ListCriteria $listCriteria
     * @return PaginatedEntityList
     * @throws Exception
     */
    public function __invoke(ListCriteria $listCriteria)
    {
        return $this->productRepository->getList($listCriteria);
    }
}
