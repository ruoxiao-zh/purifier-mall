<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 订单发货状态
 *
 * Class OrderShipStatusEnum
 * @package App\Enums
 */
final class OrderShipStatusEnum extends Enum
{
    /**
     * 未发货
     */
    public const SHIP_STATUS_PENDING = 'pending';

    /**
     * 已发货
     */
    public const SHIP_STATUS_DELIVERED = 'delivered';

    /**
     * 已收货
     */
    public const SHIP_STATUS_RECEIVED = 'received';
}
