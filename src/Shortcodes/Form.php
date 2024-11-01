<?php

namespace TotalForm\Shortcodes;
! defined( 'ABSPATH' ) && exit();


use Exception;
use TotalForm\Models\Form as FormModel;
use TotalForm\Tasks\Forms\DisplayForm;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Shortcode;

/**
 * Class Form
 *
 * @package TotalForm\Shortcodes
 */
class Form extends Shortcode
{
    /**
     * Form constructor.
     */
    public function __construct()
    {
        parent::__construct('totalform');
    }

    /**
     * @return string
     */
    public function render(): string
    {
        try {
            $id   = (int) $this->getAttribute('id');
            $form = FormModel::byIdAndActive($id);

            return DisplayForm::invoke($form);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    static function register()
    {
        return new self();
    }
}
