<?php


namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Events\Entries\OnEntryCreated;
use TotalForm\Models\Entry;
use TotalForm\Models\Form;
use TotalForm\Tasks\Utils\GetIP;
use TotalForm\Tasks\Utils\GetUserAgent;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Support\Strings;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\ValidateInput;

/**
 * Class CreateEntry
 *
 * @package TotalForm\Tasks\Entries
 * @method static Entry invoke(Form $form, Collection $data)
 */
class CreateEntry extends Task
{
    protected $data;
    protected $form;

    public function __construct(Form $form, Collection $data)
    {
        $this->form = $form;
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
        return ValidateInput::invoke(
            [],
            $this->data->toArray()
        );
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        //@TODO: To be defined, whether to implement or not (based on MVP roadmap)
        $entry = new Entry();
        //@TODO: Handle duplicate slugs
        $entry->form_uid   = $this->form->uid;
        $entry->meta       = $this->data['meta'] ?? [];
        $entry->uid        = Strings::uid();
        $entry->ip         = GetIP::invoke();
        $entry->user_agent = GetUserAgent::invoke();
        $entry->created_at = date_i18n('Y-m-d H:i:s');
        $entry->deleted_at = null;
        $entry->user_id    = get_current_user_id();
        $entry->data       = TransformEntryDataToModels::invoke($this->form, $entry, $this->data);

        Exception::throwUnless(
            $entry->save(),
            __('Something went wrong during the process.', 'totalform')
        );

        OnEntryCreated::emit($this->form, $entry);

        return $entry;
    }
}
