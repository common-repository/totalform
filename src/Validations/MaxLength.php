<?php

namespace TotalForm\Validations;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\Rakit\Validation\Rule;
use TotalFormVendors\Rakit\Validation\Rules\Traits\SizeTrait;

class MaxLength extends Rule
{
    use SizeTrait;

    /** @var string */
    protected $message = "The :attribute maximum length  is :maxLength";

    /** @var array */
    protected $fillableParams = ['maxLength'];

    /**
     * Check the $value is valid
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);
        $max         = $this->getBytesSize($this->parameter('maxLength'));
        $valueString = strlen((string) $value);

        return $valueString <= $max;
    }
}
