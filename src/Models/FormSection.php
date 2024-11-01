<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class Form
 *
 * @property string $uid
 * @property string $title
 * @property string $description
 * @property FormSkipLogic $skip_logic
 * @property Collection<FormLayout>|FormLayout[] $layouts
 * @property Collection<FormBlock>|FormBlock[] $blocks
 *
 * @package TotalForm\Models
 */
class FormSection extends Model
{
    /**
     * @var array
     */
    protected $types = [
        'skip_logic' => 'skipLogic',
        'layouts'    => 'layouts',
    ];

    /**
     * @var Form $form
     */
    public $form;

    /**
     * @var int
     */
    public $index = 0;

    /**
     * @var FormSection
     */
    public $previousSection;

    /**
     * @var FormSection
     */
    public $nextSection;

    /**
     * @var FormBlock[]
     */
    public $blocks = [];

    /**
     * @var Condition[]
     */
    public $conditions = [];

    public function __construct(Form $form, $attributes = [])
    {
        $this->blocks = Collection::create();
        parent::__construct($attributes);
        $this->form = $form;
    }

    /**
     * @param  string  $data
     *
     * @return FormSkipLogic
     * @noinspection PhpUnused
     */
    public function castToSkipLogic($data): FormSkipLogic
    {
        return new FormSkipLogic($this, $data);
    }

    /**
     * @param  array  $data
     *
     * @return Collection
     * @noinspection PhpUnused
     */
    public function castToLayouts($data): Collection
    {
        $layouts = [];
        foreach ($data as $index => $layout) {
            $layouts[] = new FormLayout($this, $layout);
        }

        return Collection::create($layouts);
    }

    /**
     * @param  FormSection  $previousSection
     */
    public function setPreviousSection(FormSection $previousSection)
    {
        $this->previousSection = $previousSection;
    }

    /**
     * @param  FormSection  $nextSection
     */
    public function setNextSection(FormSection $nextSection)
    {
        $this->nextSection = $nextSection;
    }

    /**
     * @param  int  $index
     */
    public function setIndex(int $index)
    {
        $this->index = $index;
    }
}
