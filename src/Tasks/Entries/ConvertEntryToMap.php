<?php


namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Entry;
use TotalFormVendors\TotalSuite\Foundation\Task;

class ConvertEntryToMap extends Task
{
    /**
     * @var Entry
     */
    protected $entry;

    /**
     * ConvertEntryToMap constructor.
     *
     * @param  Entry  $entry
     */
    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }


    protected function validate()
    {
        return true;
    }

    protected function execute()
    {
        $map = [];
        foreach ($this->entry->data->sections as $section) {
            if ($section->blocks->isEmpty()) {
                continue;
            }

            foreach ($section->blocks as $index => $block) {
                $map[$block->getSlug() ?: ($block->type.'_'.$index)] = $block->text;
            }
        }

        return $map;
    }
}
