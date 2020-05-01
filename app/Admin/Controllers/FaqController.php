<?php

namespace App\Admin\Controllers;

use App\Faq;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FaqController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Faq';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Faq());

        $grid->column('id', __('Id'));
        $grid->column('question', __('Question'));
        $grid->column('answer', __('Answer'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Faq::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('question', __('Question'));
        $show->field('answer', __('Answer'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Faq());

        $form->text('question', __('Question'));
        $form->textarea('answer', __('Answer'));
        $form->switch('status', __('Status'));

        return $form;
    }
}
