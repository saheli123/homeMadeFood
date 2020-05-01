<?php

namespace App\Admin\Controllers;

use App\FoodItem;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DishController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\FoodItem';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new FoodItem());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('slug', __('Slug'));
        $grid->column('dish_type', __('Dish type'));
        $grid->column('cuisine_type', __('Cuisine type'));
        $grid->column('detail', __('Detail'));
        $grid->column('picture', __('Picture'));
        $grid->column('price', __('Price'));
        $grid->column('user_id', __('User id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('delivery_type', __('Delivery type'));
        $grid->column('unit', __('Unit'));
        $grid->column('delivery_end_time', __('Delivery end time'));

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
        $show = new Show(FoodItem::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('slug', __('Slug'));
        $show->field('dish_type', __('Dish type'));
        $show->field('cuisine_type', __('Cuisine type'));
        $show->field('detail', __('Detail'));
        $show->field('picture', __('Picture'));
        $show->field('price', __('Price'));
        $show->field('user_id', __('User id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('delivery_type', __('Delivery type'));
        $show->field('unit', __('Unit'));
        $show->field('delivery_end_time', __('Delivery end time'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new FoodItem());

        $form->text('name', __('Name'));
        $form->textarea('slug', __('Slug'));
        $form->text('dish_type', __('Dish type'));
        $form->text('cuisine_type', __('Cuisine type'));
        $form->textarea('detail', __('Detail'));
        $form->textarea('picture', __('Picture'));
        $form->decimal('price', __('Price'));
        $form->number('user_id', __('User id'));
        $form->text('delivery_type', __('Delivery type'));
        $form->text('unit', __('Unit'));
        $form->datetime('delivery_end_time', __('Delivery end time'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
