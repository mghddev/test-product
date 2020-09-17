<?php
namespace App\Action;

use App\Entity\ProductEntity;
use App\Hydrate\ProductHyd;
use Illuminate\Http\UploadedFile;

/**
 * Class ActionGetArrayDataOfCsvFile
 * @package App\Action
 */
class ActionGetArrayDataOfCsvFile
{
    /**
     * @var ProductHyd
     */
    private ProductHyd $hyd;

    /**
     * ActionGetArrayDataOfCsvFile constructor.
     * @param ProductHyd $hyd
     */
    public function __construct(ProductHyd $hyd)
    {
        $this->hyd = $hyd;
    }

    /**
     * @param UploadedFile $csvFile
     * @return ProductEntity[]
     */
    public function __invoke(UploadedFile $csvFile)
    {
        $file_handle = fopen($csvFile, 'r');

        $line_of_text = [];
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0,  ',');
        }
        fclose($file_handle);

        return $this->convertLineOfCsvRecordsToArrayOfProductEntity($line_of_text);
    }

    /**
     * @param array $arr
     * @return ProductEntity[]
     */
    protected function convertLineOfCsvRecordsToArrayOfProductEntity(array $arr)
    {
        $entities = [];
        if (!empty($arr)) {
            $keyArray = $arr[0];
            unset($arr[0]);
            unset($arr[count($arr)]);

            $arr = array_map(function ($item) use ($keyArray) {
                $res = [];
                foreach ($keyArray as $num => $key) {
                    $res[$key] = $item[$num];
                }
                return $res;
            }, $arr);

            foreach($arr as $array) {
                $entity = new ProductEntity();
                $entity->setCategory($array['category'])
                    ->setProductName($array['productName'])
                    ->setPrice($array['price'])
                    ->setDescription($array['description'])
                    ->setQuantity($array['quantity']);

                $entities[] = $entity;
            }

            return $entities;
        }

        return $arr;
    }
}
