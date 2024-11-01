<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\Entry;
use TotalForm\Models\FormBlock;
use TotalForm\Validations\MatrixValidation;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;

class Matrix extends BlockType
{
    public static $category = 'field';
    public static $id       = 'matrix-field';
    public static $icon     = 'linear_scale';

    use InputHelpers;


    public static function input(FormBlock $block)
    {
        $columns = $block->argument('columns', []);
        $rows    = $block->argument('rows', []);

        $table     = Html::create('table', ['class' => 'form-matrix']);
        $tableHead = Html::create('tr');
        $tableBody = Html::create('tr');

        $tableHead->addContent(Html::create('th', []));
        foreach ($columns as $column):
            $tableHead->addContent(Html::create('th', [], $column['title']));
        endforeach;

        foreach ($rows as $row) :
            $tableBodyRow = Html::create('tr');
            $tableBodyRow->addContent(Html::create('td', [], $row['title']));

            foreach ($columns as $column):
                $tableBodyRow->addContent(
                    Html::create(
                        'td',
                        [],
                        Html::create(
                            'label',
                            ['class' => 'radio'],
                            Html::create(
                                'input',
                                [
                                    'type'  => 'radio',
                                    'name'  => static::getInputName($block->section->uid, $block->uid, $row['uid']),
                                    'value' => $column['uid'],
                                ]
                            )
                        )
                    )
                );
            endforeach;

            $tableBody->addContent($tableBodyRow);

        endforeach;


        $table->addContent(Html::create('thead', [], $tableHead));
        $table->addContent(Html::create('tbody', [], $tableBody));

        return $table;
    }

    public static function getSerializedFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        return $value;
    }

    public static function getTextFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        $text    = [];
        $rows    = Arrays::extract($block->argument('rows', []), 'title', 'uid');
        $columns = Arrays::extract($block->argument('columns', []), 'title', 'uid');

        foreach ($rows as $rowUID => $rowLabel) {
            foreach ($columns as $columnUID => $columnLabel) {
                if (isset($value[$rowUID]) && $value[$rowUID] === $columnUID) {
                    $text[] = sprintf('%s: %s', $rowLabel, $columnLabel);

                    break;
                }
            }
        }

        return implode("\n", $text);
    }

    public static function getMetadataFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        $rows    = Arrays::extract($block->argument('rows', []), 'title', 'uid');
        $columns = Arrays::extract($block->argument('columns', []), 'title', 'uid');

        $table   = [];
        $choices = [];
        $table[] = array_merge([''], array_values($columns));

        foreach ($rows as $rowUID => $rowLabel) {
            $tableRow = [$rowLabel];

            foreach ($columns as $columnUID => $columnLabel) {
                $tableRow[]         = isset($value[$rowUID]) && $value[$rowUID] === $columnUID ? '✔' : '';
                $choices[$rowLabel] = $columnLabel;
            }

            $table[] = $tableRow;
        }

        return ['table' => $table, 'choices' => $choices];
    }

    public static function getValidationRules(FormBlock $block)
    {
        $rules = parent::getValidationRules($block);

        if ($block->isRequired()) {
            $rules[$block->uid]['matrix'] = new MatrixValidation($block);
        }

        return $rules;
    }

    public static function getValidationMessages(FormBlock $formBlock)
    {
        return [
            "{$formBlock->uid}:matrix" => __('Please fill all criteria.', 'totalform'),
        ];
    }

}
