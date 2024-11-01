<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\FormBlock;
use TotalForm\Models\FormValidationRule;

class Email extends Text
{
    public static $category = 'field';
    public static $id       = 'email-field';
    public static $icon     = 'crop_7_5';

    use InputHelpers;

    public static function input(FormBlock $block)
    {
        return parent::input($block)
                     ->setAttribute('type', 'email');
    }

    public static function getValidationRules(FormBlock $block)
    {
        if ($block->isRequired()) {
            $block->validations->set(
                'email',
                FormValidationRule::from(
                    [
                        'enabled' => true,
                    ]
                )
            );
        }

        return parent::getValidationRules($block);
    }

}
