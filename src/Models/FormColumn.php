<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class FormColumn
 *
 * @property array $width {
 * @type string $small
 * @type string $medium
 * @type string $large
 * }
 * @property Collection<FormBlock>|FormBlock[] $blocks
 *
 * @package TotalForm\Models
 */
class FormColumn extends Model
{
    /**
     * @var FormLayout
     */
    public $layout;

    /**
     * @var array
     */
    protected $types = [
        'blocks' => 'blocks',
    ];

    /**
     * FormColumn constructor.
     *
     * @param  FormLayout  $layout
     * @param  array  $attributes
     */
    public function __construct(FormLayout $layout, $attributes = [])
    {
        $this->layout = $layout;
        parent::__construct($attributes);
    }

    public function castToBlocks($data)
    {
        $blocks = [];
        foreach ($data as $index => $block) {
            $this->layout->section->blocks[] = $blocks[] = new FormBlock($this, $block);
        }

        return Collection::create($blocks);
    }
}
