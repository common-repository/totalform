<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\BlockType;
use TotalForm\Services\BlockRegistry;
use TotalForm\Tasks\Blocks\RenderBlock;
use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class FormBlock
 *
 * @property string $uid
 * @property string $title
 * @property string $description
 * @property string $label
 * @property string $type
 * @property string $category
 * @property Collection $arguments
 * @property Collection<FormValidationRule>|FormValidationRule[] $validations
 *
 * @package TotalForm\Models
 */
class FormBlock extends Model
{
    /**
     * @var FormColumn
     */
    public $column;

    /**
     * @var FormSection
     */
    public $section;

    /**
     * @var Collection<FormValidationRule>|FormValidationRule[]
     */
    public $validations;

    /**
     * @var BlockType
     */
    public $blockType;

    /**
     * @var array
     */
    protected $types = [
        'arguments' => 'arguments',
    ];

    /**
     * FormBlock constructor.
     *
     * @param  FormColumn  $column
     * @param  array  $attributes
     */
    public function __construct(FormColumn $column, $attributes = [])
    {
        parent::__construct($attributes);
        $this->blockType = BlockRegistry::getByType($this->type);
        $this->column    = $column;
        $this->section   = $column->layout->section;
    }

    public function castToArguments($data)
    {
        $this->validations = $data['validations'] = $this->castToValidations($data['validations'] ?? []);

        return Collection::create($data);
    }

    public function castToValidations($data)
    {
        $validations = [];
        foreach ($data as $name => $validation) {
            $validations[$name] = new FormValidationRule($validation);
        }

        return Collection::create($validations);
    }

    /**
     * @return Collection<FormValidationRule>|FormValidationRule[]
     */
    public function getEnabledValidations()
    {
        return $this->validations->where(['enabled' => true]);
    }

    /**
     * @param  string  $attribute
     *
     * @param  null  $key
     *
     * @return array
     */
    public function getChoicesAttribute($attribute, $key = null): array
    {
        $choices = $this->argument('choices', []);

        if (empty($choices)) {
            return [];
        }

        if ($this->argument('allowOther')) {
            $choices[] = [
                'uid'   => 'other',
                'label' => __('Other', 'totalform'),
            ];
        }

        return Arrays::extract($choices, $attribute, $key);
    }

    /**
     * @param $name
     * @param  null  $default
     *
     * @return mixed
     */
    public function argument($name, $default = null)
    {
        return $this->getAttribute("arguments.{$name}", $default);
    }

    public function render()
    {
        return RenderBlock::invoke($this);
    }

    public function isField()
    {
        return true;
    }

    public function isContent()
    {
        return $this->blockType::$category === 'content';
    }

    public function isRequired()
    {
        $required = $this->validations->get('required');

        return isset($required['enabled']) && $required['enabled'] === true;
    }

    public function getSlug()
    {
        $slug = sanitize_title_with_dashes(remove_accents($this->title ?: $this->label));

        return str_replace('-', '_', $slug);
    }
}
