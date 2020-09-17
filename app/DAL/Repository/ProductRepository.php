<?php
namespace App\DAL\Repository;

use App\Common\RepoTrait;
use App\Entity\ProductEntity;
use App\Hydrate\ProductHyd;
use App\Lib\Pagination\ListCriteria;
use App\Lib\Pagination\PaginatedEntityList;
use App\Product;
use DateTime;
use Exception;

/**
 * Class ProductRepository
 * @package App\DAL\Repository
 */
class ProductRepository
{

    use RepoTrait;

    /**
     * @var Product
     */
    private Product $model;
    /**
     * @var ProductHyd
     */
    private ProductHyd $hyd;

    /**
     * ProductRepository constructor.
     * @param Product $model
     * @param ProductHyd $hyd
     */
    public function __construct(Product $model, ProductHyd $hyd)
    {
        $this->model = $model;
        $this->hyd = $hyd;
    }

    /**
     * @param array $entities
     * @return bool
     */
    public function bulkInsert(array $entities)
    {
        $entities = array_map(function (ProductEntity $entity) {
            $entity->setCreatedAt(new DateTime());
            return $entity;
        }, $entities);

        $array = $this->hyd->arrayOfEntitiesToArrayOfArrays($entities);

        return $this->model->newQuery()
            ->insert($array);
    }

    /**
     * @param ListCriteria $listCriteria
     * @return PaginatedEntityList
     * @throws Exception
     */
    public function getList(ListCriteria $listCriteria)
    {
        $query = $this->buildQueryByListCriteria(
            $listCriteria,
            $this->model
        );

        $paginated_list = $this->makePaginatedList($query, $listCriteria);

        $data = $paginated_list->getData();

        $paginated_list->setData($this->hyd->arrayOfEntitiesToArrayOfArrays(
            $this->hyd->arrayOfArraysToArrayOfEntities($data))
        );

        return $paginated_list;
    }


}
