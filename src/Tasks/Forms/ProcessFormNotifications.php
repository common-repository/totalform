<?php

namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();



use Exception;
use TotalForm\Models\Entry;
use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class ProcessFormNotifications
 *
 * @package TotalForm\Tasks\Form
 * @method static array invoke(Form $form, Entry $entry)
 * @method static array invokeWithFallback(array $fallback, Form $form, Entry $entry)
 */
class ProcessFormNotifications extends Task
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
     * ProcessFormNotifications constructor.
     *
     * @param  Form  $form
     * @param  Entry  $entry
     */
    public function __construct(Form $form, Entry $entry)
    {
        $this->form  = $form;
        $this->entry = $entry;
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
        $notifications = [];

        try {

            foreach ($this->form->settings->notifications as $notification) {
                if (!$notification->enabled) {
                    continue;
                }

                $notifications[] = SendFormNotification::invoke($this->form, $this->entry, $notification);
            }
        } catch (Exception $exception) {
        }

        return $notifications;
    }
}
