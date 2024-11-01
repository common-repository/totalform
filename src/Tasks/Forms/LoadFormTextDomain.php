<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use MO;
use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Task;
use Translation_Entry;

/**
 * Class LoadFormTextDomain
 *
 * @package TotalForm\Tasks\Forms
 * @method static Form invoke(Form $form)
 */
class LoadFormTextDomain extends Task
{
    /**
     * @var $form
     */
    protected $form;

    /**
     * LoadFormTextDomain constructor.
     *
     * @param  Form  $form
     */
    public function __construct($form)
    {
        $this->form = $form;
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        if (empty($this->form->settings->texts)) {
            return;
        }

        $domain = $GLOBALS['l10n']['totalform'] ?? $GLOBALS['l10n']['totalform'] = new MO();
        foreach ($this->form->settings->texts as $original => $expression) {
            if (empty($original)) {
                continue;
            }

            if (empty($domain->entries[$original])) {
                $entry               = new Translation_Entry();
                $entry->singular     = $original;
                $entry->translations = empty($expression) ? [$original] : [$expression];

                $domain->add_entry($entry);
            }
        }
    }
}
