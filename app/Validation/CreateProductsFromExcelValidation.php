<?php
namespace App\Validation;

use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;

/**
 * Class CreateProductsFromExcelValidation
 * @package App\Validation
 */
class CreateProductsFromExcelValidation
{
    /**
     * @var Factory
     */
    private Factory $validator;

    /**
     * CreateProductsFromExcelValidation constructor.
     * @param Factory $validator
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param array $arr
     * @throws ValidationException
     */
    public function createProductsFromExcel(array $arr)
    {
        $this->validator->validate($arr, [
            'products' => 'required|mimes:csv,txt'
        ]);
    }
}
