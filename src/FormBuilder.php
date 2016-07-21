<?php

namespace Groovey\Grid;

class FormBuilder
{
    public $yaml;

    public function container($app, $misc, $attributes)
    {
        $element = $this->element($app, $misc, $attributes);

        return $app['twig']->render('grid/entry/container.html', [
                                'form'  => $element,
                                'label' => $misc['label'],
                                'help'  => $misc['help'],
                            ]);
    }

    public function element($app, $misc, $attributes)
    {
        $attributes['class'] = coalesce($attributes['class'], 'form-control');

        switch ($misc['type']) {
            case 'text':
            case 'placeholder':
            case 'disabled':
                $element = $app['form']->text($attributes['name'], '', $attributes);
                break;
            case 'password':
                $element = $app['form']->password($attributes['name'], $attributes);
                break;
            case 'static':
                $element = $app['twig']->render('grid/entry/static.html', [
                                'text'  => $misc['text'],
                            ]);
                break;
            case 'checkbox-inline':

                $checkboxes = [];
                $selected   = $attributes['selected'];

                foreach ($attributes['options'] as $value => $label) {
                    $checked = false;
                    if (in_array($value, $selected)) {
                        $checked = true;
                    }

                    $checkboxes[] = [
                            'value' => $value,
                            'label' => $label,
                            'form'  => $app['form']->checkbox($attributes['name'], $value, $checked),
                        ];
                }

                $element = $app['twig']->render('grid/entry/checkbox-inline.html', [
                                'checkboxes'  => $checkboxes,
                            ]);
                break;
            default:
                # code...
                break;
        }

        return $element;
    }
}
