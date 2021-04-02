<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(\Faker\Generator::class);
        $orders = factory(Order::class)->times(100)->create();
        $products = collect([]);
        foreach ($orders as $order) {
            // 每笔订单随机 1 - 3 个商品
            $items = factory(OrderItem::class)->times(random_int(1, 3))->create([
                'order_id'    => $order->id,
                'rating'      => $order->reviewed ? random_int(1, 5) : null,  // 随机评分 1 - 5
                'review'      => $order->reviewed ? $faker->sentence : null,
                'reviewed_at' => $order->reviewed ? $faker->dateTimeBetween($order->paid_at) : null, // 评价时间不能早于支付时间
            ]);

            // 计算总价
            $total = $items->sum(function (OrderItem $item) {
                return $item->price * $item->amount;
            });

            // 更新订单总价
            $order->update([
                'total_amount' => $total,
            ]);

            // 将这笔订单的商品合并到商品集合中
            $products = $products->merge($items->pluck('product'));
        }

        $products->unique('id')->each(function (Product $product) {
            // 查出该商品的销量、评分、评价数
            $result = OrderItem::query()->where('product_id', $product->id)
                ->whereHas('order', function ($query) {
                    $query->whereNotNull('paid_at');
                })
                ->first([
                    \DB::raw('count(*) as review_count'),
                    \DB::raw('avg(rating) as rating'),
                    \DB::raw('sum(amount) as sold_count'),
                ]);

            $product->update([
                'rating'       => $result->rating ?: 5, // 如果某个商品没有评分，则默认为 5 分
                'review_count' => $result->review_count,
                'sold_count'   => $result->sold_count,
            ]);
        });
    }
}
