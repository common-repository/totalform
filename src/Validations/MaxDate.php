<?php

namespace TotalForm\Validations;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\Rakit\Validation\Rules\Before;
use TotalFormVendors\Rakit\Validation\Rules\Date;

class MaxDate extends Before
{


    /**
     * Check the $value is valid
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    public function check($value): bool
    {
        $dateVal = new Date();
        if (!$dateVal->check($value)) {
            return false;
        }

        return parent::check($value);
    }
}
