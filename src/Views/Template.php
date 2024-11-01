<?php

namespace TotalForm\Views;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Filters\Forms\FormAfterContentFilter;
use TotalForm\Filters\Forms\FormAssetsFilter;
use TotalForm\Filters\Forms\FormBeforeContentFilter;
use TotalForm\Filters\Forms\FormCustomCssFilter;
use TotalForm\Models\Form;
use TotalForm\Plugin;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Template as BaseTemplate;

/**
 * Class Template
 *
 * @package TotalForm
 */
abstract class Template extends BaseTemplate
{
    /**
     * @param  Form  $form
     *
     * @param  string  $template
     *
     * @return string
     */
    public function render(Form $form, $template = 'form'): string
    {
        // reCaptcha integration
        if ($form->settings->behaviors['recaptcha.enabled']) {
            wp_enqueue_script(
                'recaptcha-v3',
                sprintf(
                    "https://www.google.com/recaptcha/api.js?render=%s",
                    Plugin::options('recaptcha.siteKey')
                ),
                [],
                null
            );
        }

        if (!Plugin::options('advanced.disableDefaultStyle', false)) {
            wp_enqueue_style('totalform', $this->getUrl('assets/css/style.css'), [], Plugin::env('version'));
        }

        // Generate nonce
        $nonce = is_user_logged_in() ? wp_create_nonce('wp_rest') : null;

        // Share data
        $this->engine->addData(
            [
                'form'      => $form,
                'options'   => [
                    'recaptcha' => [
                        'enabled' => $form->settings->behaviors['recaptcha.enabled'],
                        'siteKey' => Plugin::options('recaptcha.siteKey'),
                    ],
                ],
                'nonce'     => $nonce,
                'apiBase'   => rest_url(Plugin::env('url.apiBase')),
                'customCss' => FormCustomCssFilter::apply($form->settings->getCustomCss()),
                'before'    => FormBeforeContentFilter::apply(''),
                'after'     => FormAfterContentFilter::apply(''),
                'assets'    => FormAssetsFilter::apply(
                    [
                        'css' => [],
                        'js'  => [],
                    ]
                ),
            ]
        );

        return $this->view($template);
    }

    public function getEngine()
    {
        return $this->engine;
    }
}
