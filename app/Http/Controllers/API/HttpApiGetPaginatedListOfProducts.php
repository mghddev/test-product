<?php
namespace App\Http\Controllers\API;

use App\Action\ActionGetPaginatedListOfProducts;
use App\Lib\Pagination\ListCriteria;
use Exception;

/**
 * Class HttpApiGetPaginatedListOfProducts
 * @package App\Http\Controllers\API
 */
class HttpApiGetPaginatedListOfProducts
{
    /**
     * @var ListCriteria
     */
    private ListCriteria $listCriteria;
    /**
     * @var ActionGetPaginatedListOfProducts
     */
    private ActionGetPaginatedListOfProducts $actionGetPaginatedListOfProducts;

    /**
     * HttpApiGetPaginatedListOfProducts constructor.
     * @param ListCriteria $listCriteria
     * @param ActionGetPaginatedListOfProducts $actionGetPaginatedListOfProducts
     */
    public function __construct(
        ListCriteria $listCriteria,
        ActionGetPaginatedListOfProducts $actionGetPaginatedListOfProducts
    )
    {
        $this->listCriteria = $listCriteria;
        $this->actionGetPaginatedListOfProducts = $actionGetPaginatedListOfProducts;
    }

    /**
     * @throws Exception
     */
    public function __invoke()
    {
        $products = $this->actionGetPaginatedListOfProducts
            ->__invoke($this->listCriteria->fromRequest(request()));

        return response()
            ->json($products->toArray(), 200);
    }
}
