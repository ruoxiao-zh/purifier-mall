<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Controllers\AdminController;

class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->quickSearch('title', 'description');

        $grid->filter(function ($filter) {
            $filter->column(1/2, function ($filter) {
                $filter->like('title', '商品名称');
                $filter->like('description', '商品详情');
                $filter->equal('on_sale', '是否上架')->radio(['1' => '是', '0' => '否']);
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('rating', '评分');
                $filter->equal('price', '价格');
                $filter->equal('created_at', '创建时间')->datetime();
                $filter->between('updated_at', '更新时间')->datetime();
            });
        });

        $grid->column('id', __('Id'))->sortable();
        $grid->column('title', __('商品名称'));
        $grid->column('image', __('图片'))->image();
        $grid->column('description', __('商品详情'));
        $grid->column('on_sale', __('是否上架'))->display(function ($value) {
            return $value ? '是' : '否';
        });
        $grid->column('rating', __('评分'));
        $grid->column('sold_count', __('销量'))->sortable();
        $grid->column('review_count', __('评论数'));
        $grid->column('price', __('价格'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

        $grid->actions(function ($actions) {
            // 去掉查看
            $actions->disableView();
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product());

        $form->text('title', '商品名称')->rules('required');
        $form->image('image', '封面图片')->rules('required|image')
            ->uniqueName()->removable()->downloadable();
        $form->UEditor('description', '商品描述')->rules('required');
        $form->radio('on_sale', '是否上架')->options(['1' => '是', '0' => '否'])->default('0');
        $form->hasMany('skus', 'SKU 列表', function (Form\NestedForm $form) {
            $form->text('title', 'SKU 名称')->rules('required');
            $form->text('description', 'SKU 描述')->rules('required');
            $form->text('price', '单价')->rules('required|numeric|min:0.01');
            $form->text('stock', '剩余库存')->rules('required|integer|min:0');
        });

        $form->saving(function (Form $form) {
            $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price') ?: 0;
        });

        return $form;
    }
}
