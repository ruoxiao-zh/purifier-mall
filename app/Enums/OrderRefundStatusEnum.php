<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 订单退款状态
 *
 * Class OrderRefundStatusEnum
 * @package App\Enums
 */
final class OrderRefundStatusEnum extends Enum
{
    /**
     * 未退款
     */
    public const REFUND_STATUS_PENDING = 'pending';

    /**
     * 已申请退款
     */
    public const REFUND_STATUS_APPLIED = 'applied';

    /**
     * 退款中
     */
    public const REFUND_STATUS_PROCESSING = 'processing';

    /**
     * 退款成功
     */
    public const REFUND_STATUS_SUCCESS = 'success';

    /**
     * 退款失败
     */
    public const REFUND_STATUS_FAILED = 'failed';
}
