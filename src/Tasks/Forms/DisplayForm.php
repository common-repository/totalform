<?php

namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();



use Exception;
use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Contracts\Support\HTMLRenderable;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class DisplayForm
 *
 * @package TotalForm\Tasks\Forms
 * @method static string invoke(Form $form)
 * @method static string invokeWithFallback($fallback, Form $form)
 */
class DisplayForm extends Task
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * DisplayForm constructor.
     *
     * @param  Form  $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @return bool
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function execute()
    {
        try {
            CheckFormLimitations::invoke($this->form);

            return $this->form->render();
        } catch (Exception $exception) {
            if ($exception instanceof HTMLRenderable) {
                $html = $exception->toHTML();
            } else {
                $html = Html::create('p', [], $exception->getMessage());
            }

            return $html;
        }
    }
}
