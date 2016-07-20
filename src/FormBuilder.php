<?php

namespace Groovey\Grid;

class FormBuilder
{
    public $yaml;

    public function input($app, $misc, $attributes)
    {
        $form = $this->form($app, $misc['type'], $attributes);

        return $app['twig']->render('grid/entry/text.html', [
                                'form'  => $form,
                                'label' => $misc['label'],
                                'help'  => $misc['help'],
                            ]);
    }

    public function form($app, $type, $attributes)
    {
        $attributes['class'] = coalesce($attributes['class'], 'form-control');

        switch ($type) {
            case 'text':
                $form = $app['form']->text('sample', '', $attributes);
                break;

            default:
                # code...
                break;
        }

        return $form;
    }
}
