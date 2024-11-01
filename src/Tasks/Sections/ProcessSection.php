<?php

namespace TotalForm\Tasks\Sections;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalForm\Models\FormSection;
use TotalForm\Models\NextAction;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class ProcessSection
 *
 * @package TotalForm\Tasks\Sections
 * @method static NextAction invoke(string $formUid, string $sectionUid, Collection $entry)
 * @method static NextAction invokeWithFallback($fallback, string $formUid, string $sectionUid, Collection $entry)
 */
class ProcessSection extends Task
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var FormSection
     */
    protected $section;

    /**
     * @var array
     */
    protected $entry = [];

    /**
     * ProcessSection constructor.
     *
     * @param  string  $formUid
     * @param  string  $sectionUid
     * @param  Collection|array  $entry
     *
     * @throws Exception
     */
    public function __construct(string $formUid, string $sectionUid, $entry)
    {
        $this->form    = Form::byUid($formUid);
        $this->section = $this->form->sections->where(['uid' => $sectionUid])->first();
        $this->entry   = $entry;
    }

    /**
     * @inheritDoc
     * @return bool
     * @throws Exception
     */
    protected function validate()
    {
        ValidateSection::invoke($this->section, $this->entry);

        return true;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function execute()
    {
        return ProcessConditions::invoke($this->form, $this->section, $this->entry);
    }
}
