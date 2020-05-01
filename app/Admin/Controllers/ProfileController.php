<?php

namespace App\Admin\Controllers;

use App\Profile;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProfileController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Profile';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Profile());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('bio', __('Bio'));
        // $grid->column('image', __('Image'));
        // $grid->column('facebook', __('Facebook'));
        // $grid->column('twitter', __('Twitter'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('dish_type', __('Dish type'));
        // $grid->column('delivery_type', __('Delivery type'));

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
        $show = new Show(Profile::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('bio', __('Bio'));
        $show->field('image', __('Image'));
        // $show->field('facebook', __('Facebook'));
        // $show->field('twitter', __('Twitter'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('dish_type', __('Dish type'));
        // $show->field('delivery_type', __('Delivery type'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Profile());

        $form->number('user_id', __('User id'));
        $form->textarea('bio', __('Bio'));
        // $form->image('image', __('Image'));
        // $form->text('facebook', __('Facebook'));
        // $form->text('twitter', __('Twitter'));
        $form->select('dish_type', __('Dish type'))->options(['Veg' => 'Veg', 'Non-Veg' => 'Non-Veg', 'Veg or Non-Veg' => 'Both']);;
        // $form->text('delivery_type', __('Delivery type'));

        return $form;
    }
}
