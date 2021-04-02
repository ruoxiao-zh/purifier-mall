<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Enums\HttpCodeEnum;
use App\Enums\OrderRefundStatusEnum;
use App\Enums\OrderShipStatusEnum;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends BaseModel
{
    use SoftDeletes;

    public static $refundStatusMap = [
        OrderRefundStatusEnum::REFUND_STATUS_PENDING    => '未退款',
        OrderRefundStatusEnum::REFUND_STATUS_APPLIED    => '已申请退款',
        OrderRefundStatusEnum::REFUND_STATUS_PROCESSING => '退款中',
        OrderRefundStatusEnum::REFUND_STATUS_SUCCESS    => '退款成功',
        OrderRefundStatusEnum::REFUND_STATUS_FAILED     => '退款失败',
    ];

    public static $shipStatusMap = [
        OrderShipStatusEnum::SHIP_STATUS_PENDING   => '未发货',
        OrderShipStatusEnum::SHIP_STATUS_DELIVERED => '已发货',
        OrderShipStatusEnum::SHIP_STATUS_RECEIVED  => '已收货',
    ];

    protected $fillable = [
        'no',
        'address',
        'total_amount',
        'remark',
        'paid_at',
        'payment_method',
        'payment_no',
        'refund_status',
        'refund_no',
        'closed',
        'reviewed',
        'ship_status',
        'ship_data',
        'extra',
    ];

    protected $casts = [
        'closed'    => 'boolean',
        'reviewed'  => 'boolean',
        'address'   => 'json',
        'ship_data' => 'json',
        'extra'     => 'json',
    ];

    protected $dates = [
        'paid_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ( !$model->no) {
                $model->no = static::findAvailableNo();
                if ( !$model->no) {
                    return false;
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function findAvailableNo()
    {
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            try {
                $no = $prefix . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            } catch (\Exception $e) {
                abort(HttpCodeEnum::HTTP_CODE_500, 'generate order no failed');
            }

            if ( !static::query()->where('no', $no)->exists()) {
                return $no;
            }
        }

        \Log::warning('find order no failed');

        return false;
    }

    public function scopeLoadingWith($query)
    {
        return $query->with(['items.product', 'items.productSku']);
    }

    public static function getAvailableRefundNo(): string
    {
        do {
            try {
                $no = Uuid::uuid4()->getHex();
            } catch (\Exception $e) {
                abort(HttpCodeEnum::HTTP_CODE_500, 'find available refund no failed');
            }
        } while (self::query()->where('refund_no', $no)->exists());

        return $no;
    }
}
