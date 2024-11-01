<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class SkipLogic
 *
 * @property string $action
 * @property Collection $arguments
 *
 * @package TotalForm\Models
 */
class FormSkipLogic extends Model
{
    /**
     * @var FormSection
     */
    public $section;

    /**
     * @var array
     */
    protected $types = [
        'arguments' => 'arguments',
    ];

    /**
     * SkipLogic constructor.
     *
     * @param  FormSection  $section
     * @param  array  $attributes
     */
    public function __construct(FormSection $section, $attributes = [])
    {
        parent::__construct($attributes);
        $this->section = $section;
    }

    public function castToArguments($data)
    {
        return Collection::create($data);
    }
}
