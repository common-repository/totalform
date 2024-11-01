<?php

namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Models\Entry;
use TotalForm\Models\Form;
use TotalForm\Models\FormSection;
use TotalForm\Tasks\Sections\ExtractSectionData;
use TotalForm\Tasks\Sections\ProcessSection;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class ExtractEntryData
 *
 * @package TotalForm\Tasks\Entries
 * @method static Collection invoke(Form $form, array $data, array $files)
 * @method static Collection invokeWithFallback($fallback, Form $form, array $data, array $files)
 */
class ExtractEntryData extends Task
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Entry
     */
    protected $entry;

    /**
     * ExtractEntryData constructor.
     *
     * @param  Form  $form
     * @param  array  $data
     * @param  array  $files
     *
     * @throws Exception
     */
    public function __construct(Form $form, array $data, array $files = [])
    {
        $this->form = $form;
        $this->data   = $data;
        $this->files  = $files;
    }

    /**
     * @return bool|mixed|void
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @return Collection
     * @throws Exception
     */
    protected function execute()
    {
        $entryData             = Arrays::only($this->data, ['ip', 'agent']);
        $entryData['sections'] = [];

        $sections = $this->form->sections->getIterator();

        /**
         * @var FormSection $section
         */
        foreach ($sections as $section) {
            $entryData['sections'][$section->uid] = ExtractSectionData::invoke(
                $section->uid,
                $this->data,
                $this->files
            );

            $nextAction = ProcessSection::invoke(
                $this->form->uid,
                $section->uid,
                $entryData['sections'][$section->uid]
            );

            if ($nextAction->isSubmit()) {
                break;
            }

            if ($nextAction->isSkip()) {
                while ($sections->valid()) {
                    $sections->next();
                    if ($nextAction->section_uid === $sections->current()->uid) {
                        $sections->seek($sections->key() - 1);
                        continue 2;
                    }
                }
            }
        }

        return Collection::create($entryData);
    }
}
