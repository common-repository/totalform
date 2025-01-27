<?php

namespace TotalForm\Validations;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\Rakit\Validation\Rules\In;

class InArray extends In
{
    /**
     * @param  mixed  $value
     *
     * @return bool
     */
    public function check($value): bool
    {
        foreach ((array) $value as $v) {
            if (!parent::check($v)) {
                return false;
            }
        }

        return true;
    }
}
