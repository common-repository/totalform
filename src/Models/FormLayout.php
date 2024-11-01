<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class FormLayout
 *
 * @property int $basis
 * @property bool $fluid
 * @property Collection<FormColumn>|FormColumn[] $columns
 *
 * @package TotalForm\Models
 */
class FormLayout extends Model
{
    /**
     * @var FormSection
     */
    public $section;

    /**
     * @var array
     */
    protected $types = [
        'columns' => 'columns',
    ];

    /**
     * FormLayout constructor.
     *
     * @param  FormSection  $section
     * @param  array  $attributes
     */
    public function __construct(FormSection $section, $attributes = [])
    {
        $this->section = $section;
        parent::__construct($attributes);
    }

    public function castToColumns($data)
    {
        $columns = [];
        foreach ($data as $index => $column) {
            $columns[] = new FormColumn($this, $column);
        }

        return Collection::create($columns);
    }
}
