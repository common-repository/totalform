<?php

namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Models\Entry;
use TotalForm\Models\EntryData;
use TotalForm\Models\EntrySection;
use TotalForm\Models\Form;
use TotalForm\Plugin;
use TotalForm\Services\BlockRegistry;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class TransformEntryDataToModels
 *
 * @package TotalForm\Tasks\Entries
 * @method static EntryData invoke(Form $form, Entry $entry, Collection $data)
 * @method static EntryData invokeWithFallback($fallback, Form $form, Entry $entry, Collection $data)
 */
class TransformEntryDataToModels extends Task
{
    /**
     * @var Collection
     */
    protected $data;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Entry
     */
    protected $entry;

    /**
     * TransformEntryDataToModels constructor.
     *
     * @param  Form  $form
     * @param  Entry  $entry
     * @param  Collection  $data
     */
    public function __construct(Form $form, Entry $entry, Collection $data)
    {
        $this->form  = $form;
        $this->entry = $entry;
        $this->data  = $data;
    }

    /**
     * @return bool
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @return EntryData
     * @throws Exception
     */
    protected function execute()
    {
        $model = EntryData::from($this->entry, [
            'sections' => [],
            'meta'     => [
                'version' => Plugin::env('version'),
            ],
        ]);

        foreach ($this->data->get('sections', []) as $sectionUid => $sectionData) {
            $section      = $this->form->sections->where(['uid' => $sectionUid])->first();
            $entrySection = EntrySection::from(
                $model,
                [
                    'uid'    => $sectionUid,
                    'title'  => $section->title,
                    'blocks' => [],
                ]
            );

            foreach ($sectionData as $blockUid => $blockValue) {
                $block = $section->blocks->where(['uid' => $blockUid])->first();
                if ($block === null) {
                    continue;
                }

                $blockType  = BlockRegistry::getByType($block->type);
                $entryBlock = $blockType::getEntryBlockFromRawValue($blockValue, $block, $entrySection, $this->entry);

                $entrySection->blocks->add($entryBlock);
            }

            $model['sections'][] = $entrySection;
        }

        return $model;
    }
}
