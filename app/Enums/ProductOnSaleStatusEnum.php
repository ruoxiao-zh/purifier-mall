<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 商品是否上架状态
 *
 * Class ProductOnSaleStatusEnum
 * @package App\Enums
 */
final class ProductOnSaleStatusEnum extends Enum
{
    /**
     * 未上架
     */
    const NOT_ON_SALE = 0;

    /**
     * 已上架
     */
    const IS_ON_SALE = 1;
}
