<?php

namespace TotalForm\Events\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Models\Entry;
use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Event;

class OnEntryCreated extends Event
{
    const ALIAS = 'totalform/entry/created';

    /**
     * @var Form
     */
    public $form;

    /**
     * @var Entry
     */
    public $entry;

    /**
     * constructor.
     *
     * @param  Form  $form
     * @param  Entry  $entry
     */
    public function __construct($form, $entry)
    {
        $this->form  = $form;
        $this->entry = $entry;
    }
}
