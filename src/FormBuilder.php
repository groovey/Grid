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
                $value = coalesce($attributes['value']);
                $element = $app['form']->text($attributes['name'], $attributes['value'], $attributes);
                break;
            case 'password':
                $element = $app['form']->password($attributes['name'], $attributes);
                break;
            case 'static':
                $element = $app['twig']->render('grid/entry/static.html', ['text'  => $misc['text']]);
                break;
            case 'select-range':
                $element = $app['form']->selectRange($attributes['name'], $attributes['start'], $attributes['end'], $attributes['selected'], $attributes);
                break;
            case 'select-month':
                $element = $app['form']->selectMonth($attributes['name'], $attributes['selected'], $attributes);
                break;
                break;

            case 'checkbox':
            case 'checkbox-inline':
            case 'radio':
            case 'radio-inline':

                $elements = [];
                $selected = $attributes['selected'];

                foreach ($attributes['options'] as $value => $label) {
                    $checked = false;
                    if (in_array($value, $selected)) {
                        $checked = true;
                    }

                    list($type) = explode('-', $misc['type']);

                    $elements[] = [
                            'value' => $value,
                            'label' => $label,
                            'form'  => $app['form']->$type($attributes['name'], $value, $checked),
                        ];
                }

                $element = $app['twig']->render('grid/entry/'.$misc['type'].'.html', [
                                'elements'  => $elements,
                            ]);
                break;

            case 'select':
                $name     = element('name', $attributes);
                $options  = element('options', $attributes);
                $selected = element('selected', $attributes);

                unset($attributes['name']);
                unset($attributes['options']);
                unset($attributes['selected']);

                $element = $app['form']->select($name, $options, $selected, $attributes);
                break;

            case 'input-group':

                $prefix = element('prefix', $attributes);
                $suffix = element('suffix', $attributes);
                $value  = element('value', $attributes);

                unset($attributes['prefix']);
                unset($attributes['suffix']);

                $form = $app['form']->text($attributes['name'], $value, $attributes);

                $element = $app['twig']->render('grid/entry/input-group.html', [
                                'prefix' => $prefix,
                                'form'   => $form,
                                'suffix' => $suffix,
                            ]);

                break;
            default:
                break;
        }

        return $element;
    }
}
