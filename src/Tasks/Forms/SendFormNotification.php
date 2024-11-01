<?php

namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();



use Exception;
use TotalForm\Models\Entry;
use TotalForm\Models\Form;
use TotalForm\Models\FormNotification;
use TotalForm\Tasks\Entries\ConvertEntryToHTMLTable;
use TotalFormVendors\TotalSuite\Foundation\Support\Strings;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class SendFormNotification
 *
 * @package TotalForm\Tasks\Form
 * @method static boolean invoke(Form $form, Entry $entry, FormNotification $notification)
 * @method static boolean invokeWithFallback(array $fallback, Form $form, Entry $entry, FormNotification $notification)
 */
class SendFormNotification extends Task
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Entry
     */
    protected $entry;

    /**
     * @var FormNotification
     */
    protected $notification;

    /**
     * SendFormNotification constructor.
     *
     * @param  Form  $form
     * @param  Entry  $entry
     * @param  FormNotification  $notification
     */
    public function __construct(Form $form, Entry $entry, FormNotification $notification)
    {
        $this->form         = $form;
        $this->entry        = $entry;
        $this->notification = $notification;
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function execute()
    {
        $vars = [
            'formModel'  => $this->form,
            'entryModel' => $this->entry,
            'entry'      => ConvertEntryToHTMLTable::invoke($this->entry),

        ];
        preg_match('{fields\.([\w-]+)}', $this->notification->to, $matches);
        // add the fields-uid variable
        if (count($matches) === 2) {
            $vars["fields.".$matches[1]] = $this->entry->getFieldsMap()[$matches[1]];
        }
        $to = Strings::template($this->notification->to, $vars);
        $replyTo = Strings::template($this->notification->replyTo, $vars);
        $subject = Strings::template($this->notification->subject, $vars);
        $body    = Strings::template($this->notification->body, $vars);

        return wp_mail(
            $to,
            $subject,
            $body,
            ['Content-Type: text/html; charset=UTF-8', "Reply-To: {$replyTo}"]
        );
    }
}
