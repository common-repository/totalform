<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Exceptions\Forms\FormNotFound;
use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\ValidateInput;

/**
 * Class UpdateForm
 *
 * @package TotalForm\Tasks\Forms
 * @method static Form invoke(string $formUid, array $data)
 */
class UpdateForm extends Task
{
    protected $data;
    protected $formUid;

    public function __construct($formUid, $data)
    {
        $this->data    = Collection::create($data);
        $this->formUid = $formUid;
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
        $form = Form::byUid($this->formUid);
        FormNotFound::throwIf($form->deleted_at, __('Form is trashed.', 'totalform'));
        $form->slug = GenerateUniqueSlug::invoke($form->uid, $this->data['slug']);
        //@TODO: Handle duplicate slugs
        $form->title        = esc_html($this->data['title']);
        $form->settings     = $this->data['settings'];
        $form->meta         = $this->data['meta'];
        $form->sections     = $this->data['sections'];
        $form->published_at = empty($this->data['published_at']) ? null : date_i18n('Y-m-d H:i:s');
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
