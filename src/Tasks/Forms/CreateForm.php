<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Support\Strings;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\ValidateInput;

/**
 * Class CreateForm
 *
 * @package TotalForm\Tasks\Forms
 * @method static Form invoke(array $data)
 */
class CreateForm extends Task
{
    protected $data;
    protected $saveIntoDB;

    public function __construct($data)
    {
        $this->data = Collection::create($data);
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
     * @throws Exception
     */
    protected function execute()
    {
        $form           = new Form();
        $form->title    = $this->data['title'];
        $form->settings = $this->data['settings'];
        $form->meta     = $this->data['meta'];
        $form->sections = $this->data['sections'];
        //@TODO: Handle uid
        $form->uid = Strings::uid();
        //@TODO: Handle duplicate slugs : create a task to generate a unique slug
        $form->slug         = GenerateUniqueSlug::invoke($form->uid, $this->data['slug']);
        $form->published_at = date_i18n('Y-m-d H:i:s');
        $form->updated_at   = date_i18n('Y-m-d H:i:s');
        $form->deleted_at   = null;
        $form->user_id      = get_current_user_id();


        Exception::throwUnless(
            $form->save(),
            __('Something went wrong during the process.', 'totalform')
        );


        return $form;
    }
}
