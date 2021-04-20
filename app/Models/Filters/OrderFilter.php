<?php

namespace App\Models\Filters;

use App\Enums\HttpCodeEnum;
use App\Enums\OrderRefundStatusEnum;
use App\Models\Order;
use EloquentFilter\ModelFilter;

class OrderFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function refundStatus($value): OrderFilter
    {
        if ( !in_array($value, OrderRefundStatusEnum::getValues(), true)) {
            abort(HttpCodeEnum::HTTP_CODE_422, '退款状态不合法!');
        }

        return $this->where('refund_status', $value);
    }

    public function contactPhone($value): OrderFilter
    {
        return $this->whereJsonContains('address->contact_phone', $value);
    }

    public function closed($value): OrderFilter
    {
        return $this->where('closed', $value);
    }
}
