<?php

namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\View\Engine;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Manager;

/**
 * Class ViewForm
 *
 * @package TotalForm\Tasks\Form
 */
class ViewForm extends Task
{
    /**
     * @var string
     */
    protected $form;

    /**
     * @var Engine
     */
    protected $engine;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * ViewSuvey constructor.
     *
     * @param  Manager  $manager
     * @param  Engine  $engine
     * @param  Form  $form
     */
    public function __construct(Manager $manager, Engine $engine, Form $form)
    {
        $this->form = $form;
        $this->manager = $manager;
        $this->engine = $engine;
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
     * @throws \Exception
     */
    protected function execute()
    {
        CheckAccessToForm::invoke($this->form);

        $renderedForm = DisplayForm::invoke($this->form);

        // Add form to the title
        add_filter(
            'document_title_parts',
            function ($title) {
                $title[0] = esc_html($this->form->title);

                return $title;
            }
        );
        // Add description meta tag
        add_action(
            'wp_head',
            function () {
                if ($this->form->isPreview()) {
                    show_admin_bar(false);
                }
                printf('<meta name="description" content="%s">'.PHP_EOL, esc_html($this->form->description));
            },
            1
        );

        // Render view
        return $this->engine->render(
            'form',
            ['form' => $this->form, 'content' => $renderedForm]
        );
    }
}
