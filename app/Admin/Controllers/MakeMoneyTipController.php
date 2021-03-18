<?php

namespace App\Admin\Controllers;

use App\Models\MakeMoneyTip;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MakeMoneyTipController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '赚钱小技巧';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MakeMoneyTip());
        $grid->model()->sorted();

        $grid->column('id', __('Id'))->sortable();
        $grid->column('cover_image', __('封面图'))->image();
        $grid->column('author', __('作者'));
        $grid->column('brief_intro', __('简介'))->width(200);
        $grid->column('content', __('图文详情'))->width(300);
        $grid->column('sort', __('排序'))->sortable();
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'))->sortable();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(MakeMoneyTip::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('cover_image', __('封面图'))->image();
        $show->field('author', __('作者'));
        $show->field('brief_intro', __('简介'));
        $show->UEditor('content', __('图文详情'));
        $show->field('sort', __('排序'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MakeMoneyTip());

        $form->image('cover_image', __('封面图'))->rules('required|mimes:png,gif,jpeg,jpg')
            ->uniqueName()->removable()->downloadable();
        $form->text('author', __('作者'));
        $form->text('brief_intro', __('简介'))->rules('required');
        $form->UEditor('content', __('图文详情'))->rules('required');
        $form->text('sort', __('排序'))->default(100);

        return $form;
    }
}
