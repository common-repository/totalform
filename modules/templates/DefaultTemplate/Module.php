<?php

namespace TotalForm\Templates\DefaultTemplate;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalForm\Plugin;
use TotalForm\Views\Template;

class Module extends Template
{
    protected function registerScripts()
    {
        wp_enqueue_script(
            'totalform-default-template',
            Plugin::env()->isDebug() ? $this->getUrl('assets/js/app.js') : $this->getUrl('assets/js/app.js'),
            ['petite-vue'],
            Plugin::env('version'),
            true
        );
    }

    /**
     * @param  Form  $form
     *
     * @param  string  $template
     *
     * @return string
     */
    public function render(Form $form, $template = 'form'): string
    {
        $this->registerScripts();

        return parent::render($form, $template);
    }
}
