<?php

namespace TotalForm\Pages;
! defined( 'ABSPATH' ) && exit();


use Exception;
use TotalForm\Plugin;
use TotalForm\Tasks\Forms\GetDefaultPresets;
use TotalFormVendors\TotalSuite\Foundation\License;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Admin\Page;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Promotion\GetModules;

/**
 * Class Dashboard
 *
 * @package TotalForm\Pages
 */
class Dashboard extends Page
{
    public function register()
    {
        parent::register();

        $slug = $this->slug();

        $submenu = [
            "{$slug}#/forms"   => __('Forms', 'totalform'),
            "{$slug}#/options" => __('Options', 'totalform'),
            "{$slug}#/help"    => __('Help', 'totalform'),
        ];

        foreach ($submenu as $slug => $label) {
            add_submenu_page(
                $this->slug(),
                $label,
                $label,
                $this->capability(),
                $slug,
                [$this, 'render']
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function assets()
    {
        // Disable emoji
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');

        $baseUrl = Plugin::env('url.base', '');

        wp_enqueue_media();
        wp_enqueue_style('material-font', 'https://fonts.googleapis.com/icon?family=Material+Icons');
        wp_enqueue_script('runtime', $baseUrl.'/assets/admin/runtime.js', [], Plugin::env('version'));
        wp_enqueue_script('polyfills', $baseUrl.'/assets/admin/polyfills.js', [], Plugin::env('version'));
        wp_enqueue_script('vendor', $baseUrl.'/assets/admin/vendor.js', [], Plugin::env('version'));
        wp_enqueue_style('styles', $baseUrl.'/assets/admin/styles.css', [], Plugin::env('version'));
        wp_enqueue_script('main', $baseUrl.'/assets/admin/main.js', [], Plugin::env('version'), true);
    }

    /**
     * @inheritDoc
     */
    public function icon(): string
    {
        return 'dashicons-feedback';
    }

    /**
     * @inheritDoc
     */
    public function capability(): string
    {
        return 'manage_options';
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return __('TotalForm', 'totalform');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function data(): array
    {
        return [
            'baseUrl'  => Plugin::env('url.base'),
            'config'   => $this->getConfig(),
            'basePath' => Plugin::env('path.base'),
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getConfig(): array
    {
        $baseUrl      = Plugin::env('url.base').'/';
        $apiBaseUrl   = rest_url(Plugin::env('url.apiBase'));
        $wpBaseUrl    = rest_url();
        $wpNonce      = wp_create_nonce('wp_rest');
        $previewNonce = wp_create_nonce('preview_form');

        return [
            'baseUrl'      => $baseUrl,
            'previewNonce' => $previewNonce,
            'api'          => [
                'wp'    => $wpBaseUrl,
                'base'  => $apiBaseUrl,
                'nonce' => $wpNonce,
            ],
            'modules'      => GetModules::invoke(),
            'currentUser'  => [
                'name'  => wp_get_current_user()->display_name,
                'email' => wp_get_current_user()->user_email,
            ],
            'support'      => [
                'url'           => 'https://totalsuite.net/support/?utm_source=support-panel&utm_medium=in-app&utm_campaign=totalform',
                'documentation' => 'https://totalsuite.net/product/totalform/documentation/?utm_source=support-panel&utm_medium=in-app&utm_campaign=totalform',
                'search'        => 'https://totalsuite.net/search/?utm_source=support-panel&utm_medium=in-app&utm_campaign=totalform',
                'sections'      => [
                    [
                        'title' => 'Get started',
                        'items' => [
                            [
                                'title' => 'How to install TotalForm',
                                'url'   => 'https://totalsuite.net/documentation/totalform/basics-totalform/how-to-install-totalform/?utm_source=support-panel&utm_medium=in-app&utm_campaign=totalform',
                            ],
                            [
                                'title' => 'How to create a form',
                                'url'   => 'https://totalsuite.net/documentation/totalform/basics-totalform/how-to-create-a-form/?utm_source=support-panel&utm_medium=in-app&utm_campaign=totalform',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Take a step further',
                        'items' => [
                            [
                                'title' => 'How to integrate the form',
                                'url'   => 'https://totalsuite.net/documentation/totalform/basics-totalform/how-to-integrate-the-form/?utm_source=support-panel&utm_medium=in-app&utm_campaign=totalform',
                            ],
                            [
                                'title' => 'How to customize the appearance of the form',
                                'url'   => 'https://totalsuite.net/documentation/totalform/basics-totalform/how-to-customize-the-appearance-of-the-form/?utm_source=support-panel&utm_medium=in-app&utm_campaign=totalform',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Entries management',
                        'items' => [
                            [
                                'title' => 'How to browse entries',
                                'url'   => 'https://totalsuite.net/documentation/totalform/basics-totalform/how-to-browse-entries/?utm_source=support-panel&utm_medium=in-app&utm_campaign=totalform',
                            ],
                            [
                                'title' => 'How to delete or reset entries',
                                'url'   => 'https://totalsuite.net/documentation/totalform/basics-totalform/how-to-delete-or-reset-entries/?utm_source=support-panel&utm_medium=in-app&utm_campaign=totalform',
                            ],
                        ],
                    ],
                ],
                'codex'         => 'https://totalsuite.net/documentation/totalform/codex/?utm_source=support-panel&utm_medium=in-app&utm_campaign=totalform',
            ],
            'templates'    => GetDefaultPresets::invoke(),
            'roles'        => wp_list_pluck(get_editable_roles(), 'name'),
            'product'      => Plugin::env('product'),
            'customer'     => Plugin::options(
                'customer',
                [
                    'status'     => 'init',
                    'email'      => '',
                    'signup'     => false,
                    'audience'   => 'other',
                    'usage'      => 'other',
                    'tracking'   => false,
                    'newsletter' => false,
                ]
            ),
            'license'      => License::instance()->toArray(),
            'onboarding'   => [
                'url'   => [
                    'documentation' => 'https://totalsuite.net/product/totalform/documentation/',
                    'store'         => 'https://totalsuite.net/product/totalform/add-ons/',
                ],
                'steps' => [
                    'welcome'      => [
                        'title' => __('Hey mate!', 'totalform'),
                        'text'  => __(
                            'We are delighted to see you started using TotalForm, <br> TotalForm will impress you, we promise!',
                            'totalform'
                        ),
                        'tabs'  => [
                            [
                                'icon'  => 'touch_app',
                                'title' => __('User Friendly', 'totalform'),
                                'text'  => __(
                                    "Crafting forms isn't easy but with TotalForm, it's a joyful experience.",
                                    'totalform'
                                ),
                            ],
                            [
                                'icon'  => 'style',
                                'title' => __('Elegant Design', 'totalform'),
                                'text'  => __(
                                    "A good-looking form could help you achieve a better response rate.",
                                    'totalform'
                                ),
                            ],
                            [
                                'icon'  => 'power',
                                'title' => __('Flexibility & Extensibility', 'totalform'),
                                'text'  => __(
                                    "Build highly flexible forms to achieve unprecedented results.",
                                    'totalform'
                                ),
                            ],
                        ],
                    ],
                    'introduction' => [
                        'title' => __('Get started', 'totalform'),
                        'text'  => __(
                            "We've prepared some materials for you to ease your learning curve.",
                            'totalform'
                        ),
                        'posts' => [
                            [
                                'title' => __("How to create a form", "totalform"),
                                'text'  => __(
                                    "Learn how to create a form in no time using TotalForm.",
                                    "totalform"
                                ),
                                'image' => "assets/images/onboarding/create.svg",
                                'url'   => "https://totalsuite.net/documentation/totalform/basics-totalform/how-to-create-a-form/",
                            ],
                            [
                                'title' => __("How to integrate a form", "totalform"),
                                'text'  => __(
                                    "Once your form is ready, we'll show you how to integrate it into your site.",
                                    "totalform"
                                ),
                                'image' => "assets/images/onboarding/integrate.svg",
                                'url'   => "https://totalsuite.net/documentation/totalform/basics-totalform/how-to-integrate-the-form/",
                            ],
                            [
                                'title' => __("How to customize appearance", "totalform"),
                                'text'  => __(
                                    "Learn how to customize the appearance of the form to match your brand.",
                                    "totalform"
                                ),
                                'image' => "assets/images/onboarding/integrate.svg",
                                'url'   => "https://totalsuite.net/documentation/totalform/basics-totalform/how-to-customize-the-appearance-of-the-form/",
                            ],
                        ],
                    ],
                    'connect'      => [
                        'title'       => __('Get started', 'totalform'),
                        'text'        => __(
                            "We've prepared some materials for you to ease your learning curve.",
                            'totalform'
                        ),
                        'information' => [
                            __('Let you know about the upcoming features.', 'totalform'),
                            __('Inform you about important updates.', 'totalform'),
                            __('Adjust recommendations.', 'totalform'),
                            __('Adapt product settings.', 'totalform'),
                            __('Send you exclusive offers.', 'totalform'),
                        ],
                    ],
                    'finish'       => [
                        'title'       => __('Bravo! You did it!', 'totalform'),
                        'text'        => __(
                            "You are all set to start making informed decisions! One last thing, we'd like to collect some anonymous usage information that will help us shape up TotalForm.",
                            'totalform'
                        ),
                        'information' => [
                            __('Make TotalForm stable and bug-free.', 'totalform'),
                            __('Get an overview of environments.', 'totalform'),
                            __('Optimize performance.', 'totalform'),
                            __('Adjust default parameters.', 'totalform'),
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function template(): string
    {
        return 'dashboard';
    }
}
