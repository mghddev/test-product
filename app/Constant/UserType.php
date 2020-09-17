<?php
namespace App\Constant;

/**
 * Class UserType
 * @package App\Constant
 */
class UserType
{
    const ADMIN = 1;
    const USER = 2;

    const All = [
      self::ADMIN,
      self::USER
    ];
}
