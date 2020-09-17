<?php
namespace App\Constant;

/**
 * Class ProductCategory
 * @package App\Constant
 */
class ProductCategory
{
    const GENERAL = 'general';
    const FRUIT = 'fruit';
    const BEANS = 'beans';

    const All = [
        self::GENERAL,
        self::BEANS,
        self::FRUIT,
    ];
}
