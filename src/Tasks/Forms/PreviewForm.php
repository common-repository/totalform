<?php

namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Contracts\Support\HTMLRenderable;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\View\Engine;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Manager;

/**
 * Class PreviewForm
 *
 * @package TotalForm\Tasks\Form
 */
class PreviewForm extends Task
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
        try {
            $renderedForm = $this->form->render();

        } catch (Exception $exception) {
            if ($exception instanceof HTMLRenderable) {
                $renderedForm = $exception->toHTML();
            } else {
                $renderedForm = Html::create('p', [], $exception->getMessage());
            }
        }

        // Add form to the title
        add_filter(
            'document_title_parts',
            function ($title) {
                $title[0] = $this->form->title;

                return $title;
            }
        );

        // Render view
        return $this->engine->render(
            'form',
            ['form' => $this->form, 'content' => $renderedForm]
        );
    }
}
