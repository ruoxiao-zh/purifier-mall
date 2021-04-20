<?php

namespace App\Http\Resources;

use App\Models\Order;

class OrderResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                         => $this->id,
            'no'                         => $this->no,
            'user'                       => $this->user,
            'address'                    => $this->address,
            'total_amount'               => $this->total_amount,
            'remark'                     => $this->remark,
            'paid_at'                    => $this->paid_at,
            'payment_method'             => $this->payment_method,
            'payment_no'                 => $this->payment_no,
            'refund_status'              => Order::$refundStatusMap[$this->refund_status],
            'refund_no'                  => $this->refund_no,
            'closed'                     => $this->closed,
            'reviewed'                   => $this->reviewed,
            'ship_status'                => Order::$shipStatusMap[$this->ship_status],
            'ship_data'                  => $this->ship_data,
            'extra'                      => $this->extra,
            'items'                      => $this->items,
            'created_at'                 => $this->created_at,
            'updated_at'                 => $this->updated_at,
            'created_at_diff_for_humans' => $this->created_at_diff_for_humans,
            'updated_at_diff_for_humans' => $this->updated_at_diff_for_humans,
        ];
    }
}
