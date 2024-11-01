<?php


namespace TotalForm\Validations;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\FormBlock;
use TotalFormVendors\Rakit\Validation\Rule;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;

class MatrixValidation extends Rule
{
    /**
     * @var FormBlock
     */
    protected $block;

    /**
     * @var string
     */
    protected $message = "Required";

    public function __construct(FormBlock $block)
    {
        $this->block = $block;
    }

    public function getMessage(): string
    {
        return __('Required', 'totalform');
    }

    /**
     * @param  array  $value
     *
     * @return bool
     */
    public function check($value): bool
    {
        $columns = Arrays::extract($this->block->argument('columns'), 'uid');
        $rows    = Arrays::extract($this->block->argument('rows'), 'uid');

        $userColumns = array_values((array) $value);

        foreach ($userColumns as $userColumn) {
            if (!in_array($userColumn, $columns, true)) {
                return false;
            }
        }

        $userRows = array_keys((array) $value);

        if (count($rows) !== count($userRows)) {
            return false;
        }

        if (!empty(array_diff($rows, $userRows))) {
            return false;
        }

        return true;
    }
}
