<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\ValidateInput;

/**
 * Class ShareFormViaEmail
 *
 * @package TotalForm\Tasks\Forms
 * @method static Form invoke(string $formUid, array $data)
 */
class ShareFormViaEmail extends Task
{
    protected $data;
    protected $formUid;

    public function __construct($formUid, $data)
    {
        $this->data = Collection::create($data);
        $this->formUid = $formUid;
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
        return ValidateInput::invoke(
            [
                'email' => 'string|email',
            ],
            $this->data->toArray()
        );
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        $form = Form::byUid($this->formUid);
        $sent = wp_mail(
            $this->data['email'],
            'Invitation to fill a form',
            sprintf(
                'You are invited to fill this form: <a href="%s">%s</a>',
                esc_attr(
                    $form->getUrl([
                                      'utm_campaign' => 'Form: '.esc_html($form->title),
                                      'utm_medium'   => 'email',
                                      'utm_source'   => 'share',
                                  ])
                ),
                esc_html($form->title)
            ),
            ['Content-Type: text/html; charset=UTF-8']
        );

        if (!$sent) {
            Exception::throwUnless(
                $form->save(),
                __('Something went wrong during the process.', 'totalform')
            );
        }

        return $sent;
    }
}
