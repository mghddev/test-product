<?php
namespace App\Http\Controllers\API;

use App\Action\ActionGetArrayDataOfCsvFile;
use App\Action\ActionInsertProducts;
use App\Hydrate\ProductHyd;
use App\Validation\CreateProductsFromExcelValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Class HttpApiUploadExcelFileOfProductAndBulkInsert
 * @package App\Http\Controllers\API
 */
class HttpUploadExcelFileOfProductAndBulkInsert
{
    /**
     * @var CreateProductsFromExcelValidation
     */
    private CreateProductsFromExcelValidation $validation;
    /**
     * @var ActionGetArrayDataOfCsvFile
     */
    private ActionGetArrayDataOfCsvFile $actionGetArrayDataOfCsvFile;
    /**
     * @var ActionInsertProducts
     */
    private ActionInsertProducts $actionInsertProducts;
    /**
     * @var ProductHyd
     */
    private ProductHyd $hyd;


    /**
     * HttpApiUploadExcelFileOfProductAndBulkInsert constructor.
     * @param CreateProductsFromExcelValidation $validation
     * @param ActionGetArrayDataOfCsvFile $actionGetArrayDataOfCsvFile
     * @param ActionInsertProducts $actionInsertProducts
     * @param ProductHyd $hyd
     */
    public function __construct(
        CreateProductsFromExcelValidation $validation,
        ActionGetArrayDataOfCsvFile $actionGetArrayDataOfCsvFile,
        ActionInsertProducts $actionInsertProducts,
        ProductHyd $hyd
    )
    {

        $this->validation = $validation;
        $this->actionGetArrayDataOfCsvFile = $actionGetArrayDataOfCsvFile;
        $this->actionInsertProducts = $actionInsertProducts;
        $this->hyd = $hyd;
    }

    /**
     * @return JsonResponse
     * @throws ValidationException
     */
    public function __invoke()
    {
        ### validation input data
        $this->validation->createProductsFromExcel(request()->all());

        ### Save And Convert Excel file to array of product entity
        $csvFile = request()->file('products');
        $this->saveFile($csvFile);

        $entities = $this->actionGetArrayDataOfCsvFile->__invoke($csvFile);

        ### insert products to products table
        $this->actionInsertProducts->__invoke($entities);

        return response()
            ->json(
                $this->hyd->arrayOfEntitiesToArrayOfArrays($entities),
                201);
    }

    /**
     * @param UploadedFile $file
     */
    protected function saveFile(UploadedFile $file)
    {
        $name = 'CSVFiles' . DIRECTORY_SEPARATOR .time();
        Storage::disk()->put($name, $file);
    }
}
