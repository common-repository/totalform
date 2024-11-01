<?php


namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\ValidateInput;

/**
 * Class UpdateEntry
 *
 * @package TotalForm\Tasks\Entries
 * @method static Form invoke(string $entryUid, array $data)
 */
class UpdateEntry extends Task
{
    protected $data;
    protected $entryUid;

    public function __construct($entryUid, $data)
    {
        $this->data     = Collection::create($data);
        $this->entryUid = $entryUid;
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
        return ValidateInput::invoke(
            [
                'title'    => 'string',
                'slug'     => 'string|required',
                'settings' => 'array',
                'meta'     => 'array',
                'sections' => 'array',
            ],
            $this->data->toArray()
        );
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        //@TODO: To be defined, whether to implement or not (based on MVP roadmap)
        return Collection::create([]);
    }
}
