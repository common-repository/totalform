<?php


namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Entry;
use TotalFormVendors\TotalSuite\Foundation\Task;

class ConvertEntryToText extends Task
{
    /**
     * @var Entry
     */
    protected $entry;

    /**
     * ConvertEntryToText constructor.
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
        $text = '';
        foreach ($this->entry->data->sections as $section) {
            if ($section->blocks->isEmpty()) {
                continue;
            }

            foreach ($section->blocks as $block) {
                $text .= sprintf('%s: %s', $block->title, $block->text)."\r\n";
            }
        }

        return $text;
    }
}
