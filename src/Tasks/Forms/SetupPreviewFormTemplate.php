<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalForm\Plugin;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\View\Engine;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Manager;

class SetupPreviewFormTemplate extends Task
{
    protected function validate()
    {
        return true;
    }

    protected function execute()
    {
        add_filter(
            'query_vars',
            static function ($queryVars) {
                $queryVars[] = 'form_preview';

                return $queryVars;
            }
        );

        add_action(
            'init',
            static function () {
                add_rewrite_rule("preview-form/([a-z0-9-]+)[/]?$", 'index.php?form_preview=$matches[1]', 'top');
            }
        );

        add_action('template_redirect', [$this, 'handleTemplateRedirect']);
    }

    public function handleTemplateRedirect($template)
    {
        global $wp_query;

        if ($formUid = get_query_var('form_preview')) {
            if (wp_verify_nonce(Plugin::request('preview_nonce'), 'preview_form') === false) {
                wp_die('Invalid action.');
            }

            $form = json_decode(wp_unslash((string) Plugin::request('form')), true);
            $form = Form::from($form);
            $form->setPreview();

            $wp_query->is_embed = Plugin::request('embed');
            $wp_query->is_home  = false;

            show_admin_bar(false);

            try {
                echo ViewForm::invoke(
                    Manager::instance(),
                    Engine::instance(),
                    $form
                );
            } catch (\Exception $exception) {
                wp_die($exception->getMessage(), get_bloginfo('name'));
            }

            exit;
        }

        return $template;
    }
}
