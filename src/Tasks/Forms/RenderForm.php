<?php

namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();



use Exception;
use TotalForm\Filters\Forms\FormPreRenderFilter;
use TotalForm\Models\Form;
use TotalForm\Plugin;
use TotalForm\Views\Template;
use TotalFormVendors\TotalSuite\Foundation\Contracts\Support\HTMLRenderable;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class RenderForm
 *
 * @package TotalForm\Tasks\Form
 * @method static array invoke(Form $form)
 * @method static array invokeWithFallback(array $fallback, Form $form)
 */
class RenderForm extends Task
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Template
     */
    protected $template;

    /**
     * RenderForm constructor.
     *
     * @param  Form  $form
     *
     * @throws \TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception
     */
    public function __construct(Form $form)
    {
        $this->form     = $form;
        $this->template = GetFormTemplate::invoke('default-template');
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
        try {
            $form     = FormPreRenderFilter::apply($this->form);
            $rendered = $this->template->render($form);
        } catch (Exception $exception) {
            if ($exception instanceof HTMLRenderable) {
                $rendered = $exception->toHTML();
            } else {
                $rendered = Html::create('p', [], $exception->getMessage());
            }
        }

        $wrapper = Html::create(
            'div',
            [
                'id'    => "totalform-{$this->form->uid}",
                'class' => 'totalform-wrapper',
            ],
            $rendered
        );

        if (Plugin::options('branding.showCredits', false)) {
            $credit = Html::create(
                'div',
                [
                    'class' => 'totalform-credits',
                    'style' => 'font-family: sans-serif; font-size: 9px; text-transform: uppercase;text-align: center; padding: 10px 0;',
                ],
                sprintf(
                    __('Powered by %s', 'totalform'),
                    Html::create('a', ['href' => Plugin::env('product.url')], 'totalform')
                )
            );
            $wrapper->addContent($credit);
        }

        $style = $this->template->render($form, 'style');
        return $style.$wrapper->render();
    }
}
