<?php

namespace App\Admin\Controllers;

use App\Slideshow;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Controllers\AdminController;

class SlideshowController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '轮播图';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Slideshow());
        $grid->model()->sorted();

        $grid->column('id', __('Id'))->sortable();
        $grid->column('url_path', __('轮播图'))->image();
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
        $show = new Show(Slideshow::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('url_path', __('轮播图路径'));
        $show->field('url_path', __('轮播图预览'))->image();
        $show->field('sort', __('排序'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Slideshow());

        $form->image('url_path', __('轮播图预览'))->rules('required|mimes:png,gif,jpeg,jpg')
            ->uniqueName()->removable()->downloadable();
        $form->text('sort', __('排序'))->default(100);

        return $form;
    }
}
