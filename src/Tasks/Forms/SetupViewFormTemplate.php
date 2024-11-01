<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Filters\Forms\FormLinkFilter;
use TotalForm\Models\Form;
use TotalForm\Plugin;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\View\Engine;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Manager;

class SetupViewFormTemplate extends Task
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
                $queryVars[] = 'form_slug';

                return $queryVars;
            }
        );

        add_action(
            'init',
            static function () {
                $base = FormLinkFilter::apply('form');
                add_rewrite_rule("{$base}/([a-z0-9-]+)[/]?$", 'index.php?form_slug=$matches[1]', 'top');
            }
        );

        add_action('template_redirect', [$this, 'handleTemplateRedirect']);
    }

    public function handleTemplateRedirect($template)
    {
        global $wp_query;

        if ($formUid = get_query_var('form_slug')) {
            $wp_query->is_embed = Plugin::request('embed');
            $wp_query->is_home  = false;

            try {
                $form = Form::byUid($formUid);
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
