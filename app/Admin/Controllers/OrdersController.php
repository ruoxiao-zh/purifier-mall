<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Enums\HttpCodeEnum;
use Illuminate\Http\Request;
use Encore\Admin\Layout\Content;
use App\Enums\OrderShipStatusEnum;
use Encore\Admin\Controllers\AdminController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class OrdersController extends AdminController
{
    use ValidatesRequests;
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->model()->whereNotNull('paid_at')->orderBy('paid_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('no', __('订单流水号'));
        $grid->column('user.name', __('买家'));
        $grid->column('total_amount', __('总金额'))->sortable();
        $grid->column('payment_method', __('支付方式'));
        $grid->column('paid_at', __('支付时间'))->sortable();
        $grid->column('remark', __('备注信息'));
        $grid->column('ship_status', __('物流'))->display(function ($value) {
            return Order::$shipStatusMap[$value];
        });
        $grid->column('refund_status', __('退款状态'))->display(function ($value) {
            return Order::$refundStatusMap[$value];
        });

        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    public function show($id, Content $content)
    {
        return $content->header('查看订单')->body(view('admin.orders.show', ['order' => Order::find($id)]));
    }

    public function ship(Order $order, Request $request)
    {
        if ( !$order->paid_at) {
            return response()->json(['message' => '该订单未付款'], HttpCodeEnum::HTTP_CODE_500);
        }

        if ($order->ship_status !== OrderShipStatusEnum::SHIP_STATUS_PENDING) {
            return response()->json(['message' => '该订单已发货'], HttpCodeEnum::HTTP_CODE_500);
        }

        $data = $this->validate($request, [
            'express_company' => ['required'],
            'express_no'      => ['required'],
        ], [], [
            'express_company' => '物流公司',
            'express_no'      => '物流单号',
        ]);

        $order->update([
            'ship_status' => OrderShipStatusEnum::SHIP_STATUS_DELIVERED,
            'ship_data'   => $data,
        ]);

        return redirect()->back();
    }
}
