<?php

namespace App\Admin\Controllers;

use App\Enums\HttpCodeEnum;
use App\Enums\OrderShipStatusEnum;
use App\Models\Order;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Encore\Admin\Layout\Content;
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
//        $grid->column('user_id', __('User id'));
//        $grid->column('address', __('Address'));
        $grid->column('total_amount', __('总金额'))->sortable();
        $grid->column('paid_at', __('支付时间'))->sortable();
        $grid->column('remark', __('备注信息'));

//        $grid->column('payment_method', __('Payment method'));
//        $grid->column('payment_no', __('Payment no'));

//        $grid->column('refund_no', __('Refund no'));
//        $grid->column('closed', __('Closed'));
//        $grid->column('reviewed', __('Reviewed'));
        $grid->column('ship_status', __('物流'))->display(function ($value) {
            return Order::$shipStatusMap[$value];
        });
        $grid->column('refund_status', __('退款状态'))->display(function ($value) {
            return Order::$refundStatusMap[$value];
        });
//        $grid->column('ship_data', __('Ship data'))->display(function ($value) {
//            return Order::$shipStatusMap[$value];
//        });
//        $grid->column('extra', __('Extra'));
//        $grid->column('deleted_at', __('Deleted at'));
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));
        // 禁用创建按钮，后台不需要创建订单
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 禁用删除和编辑按钮
            $actions->disableDelete();
            $actions->disableEdit();
        });
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id, Content $content)
    {
//        $show = new Show(Order::findOrFail($id));
//
//        $show->field('id', __('Id'));
//        $show->field('no', __('No'));
//        $show->field('user_id', __('User id'));
//        $show->field('address', __('Address'));
//        $show->field('total_amount', __('Total amount'));
//        $show->field('remark', __('Remark'));
//        $show->field('paid_at', __('Paid at'));
//        $show->field('payment_method', __('Payment method'));
//        $show->field('payment_no', __('Payment no'));
//        $show->field('refund_status', __('Refund status'));
//        $show->field('refund_no', __('Refund no'));
//        $show->field('closed', __('Closed'));
//        $show->field('reviewed', __('Reviewed'));
//        $show->field('ship_status', __('Ship status'));
//        $show->field('ship_data', __('Ship data'));
//        $show->field('extra', __('Extra'));
//        $show->field('deleted_at', __('Deleted at'));
//        $show->field('created_at', __('Created at'));
//        $show->field('updated_at', __('Updated at'));

//        return $show;
        return $content->header('查看订单')->body(view('admin.orders.show',
            ['order' => Order::find($id)]));
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
