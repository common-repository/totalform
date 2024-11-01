<?php
! defined( 'ABSPATH' ) && exit();


return [
    'version'    => '1.2.0',
    'loader'     => (require 'vendor/autoload.php'),
    'textdomain' => 'totalform',
    'product'    => [
        'id'     => 'totalform',
        'name'   => 'TotalForm',
        'source' => 'totalsuite.net',
        'url'    => 'https://totalsuite.net/totalform',
    ],
    'namespaces' => [
        'rest'      => 'totalform',
        'extension' => 'TotalForm\\Extensions',
        'template'  => 'TotalForm\\Templates',
    ],
    'path'       => [
        'base'        => wp_normalize_path(plugin_dir_path(__FILE__)),
        'languages'   => wp_normalize_path(plugin_dir_path(__FILE__)).'languages',
        'uploads'     => wp_normalize_path(wp_get_upload_dir()['basedir']),
        'userUploads' => wp_normalize_path(wp_get_upload_dir()['basedir'].'/totalform/uploads'),
        'modules'     => wp_normalize_path(plugin_dir_path(__FILE__).'modules'),
        'userModules' => wp_normalize_path(wp_get_upload_dir()['basedir'].'/totalform/modules'),
        'migrations'  => plugin_dir_path(__FILE__).'migrations',
    ],
    'url'        => [
        'base'        => plugins_url('', __FILE__),
        'apiBase'     => '/totalform',
        'modules'     => [
            'base'  => plugins_url('modules', __FILE__),
            'store' => 'https://totalsuite.net/api/v3/modules?for=totalform',
        ],
        'userModules' => [
            'base' => wp_get_upload_dir()['baseurl'].'/totalform/modules',
        ],
        'userUploads' => [
            'base' => wp_get_upload_dir()['baseurl'].'/totalform/uploads',
        ],
        'blogFeed'    => 'https://totalsuite.net/wp-json/wp/v2/blog_article',
        'tracking'    => [
            'nps'         => 'https://collect.totalsuite.net/nps',
            'feedback'    => 'https://collect.totalsuite.net/feedback',
            'uninstall'   => 'https://collect.totalsuite.net/uninstall',
            'environment' => 'https://collect.totalsuite.net/env',
            'events'      => 'https://collect.totalsuite.net/event',
            'log'         => 'https://collect.totalsuite.net/log',
            'onboarding'  => 'https://collect.totalsuite.net/onboarding',
        ],
        'activation'  => [
            'activate' => 'https://totalsuite.net/api/v3/activate',
            'license'  => 'https://totalsuite.net/api/v3/license',
        ],
    ],
    'db'         => [
        'prefix' => $GLOBALS['wpdb']->prefix,
    ],
    'stores'     => [
        'optionsKey'  => 'totalform_options',
        'modulesKey'  => 'totalform_modules',
        'versionKey'  => 'totalform_version',
        'trackingKey' => 'totalform_tracking',
    ],
    'defaults'   => [
        'options' => [
            'general'   => [
                'wipeData'   => false,
                'shareUsage' => false,
            ],
            'customer' => [
                'tracking' => false,
            ],
            'branding'  => [
                'showCredits' => false,
            ],
            'privacy'   => [],
            'advanced'  => [
                'disableDefaultStyle' => false,
            ],
            'recaptcha' => [
                'siteKey'    => '',
                'siteSecret' => '',
            ],
        ],
    ],
];
