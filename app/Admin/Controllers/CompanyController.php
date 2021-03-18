<?php

namespace App\Admin\Controllers;

use App\Company;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Controllers\AdminController;

class CompanyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '公司管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Company());

        $grid->column('id', __('Id'));
        $grid->column('name', __('公司名称'));
        $grid->column('logo_path', __('公司 Logo'))->image();
        $grid->column('contact', __('联系方式'));
        $grid->column('address', __('地址'));
        $grid->column('description', __('描述信息'))->width(300);
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        $grid->disableExport();
        $grid->disableCreation();
        $grid->disableRowSelector();
        $grid->disableFilter();
//        $grid->filter(function ($filter) {
//            $filter->disableIdFilter(); // 去掉默认的 id 过滤器
//        });

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
        $show = new Show(Company::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('公司名称'));
        $show->field('logo_path', __('公司 Logo'))->image();
        $show->field('contact', __('联系方式'));
        $show->field('address', __('地址'));
        $show->field('description', __('描述信息'))->unescape();
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));

        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Company());

        $form->text('name', __('公司名称'))->rules('required');
        $form->image('logo_path', __('公司 Logo'))->rules('required|mimes:png,gif,jpeg,jpg')
            ->uniqueName()->removable()->downloadable();
        $form->text('contact', __('联系方式'))->rules('required');
        $form->text('address', __('地址'))->rules('required');
        $form->UEditor('description', __('描述信息'))->rules('required');

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });

        return $form;
    }
}
