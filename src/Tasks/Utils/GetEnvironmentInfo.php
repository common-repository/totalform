<?php

namespace TotalForm\Tasks\Utils;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;

/**
 * Class StoreNPS
 *
 * @package TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Options
 * @method static Collection invoke(Options $options, array $data)
 * @method static Collection invokeWithFallback($fallback, Options $options, array $data)
 */
class GetEnvironmentInfo extends Task
{

    public function __construct()
    {
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
        // System information
        global $wpdb;

        $details = [
            'PHP'       => [
                'Version'              => PHP_VERSION,
                'OS'                   => PHP_OS,
                'Extensions'           => implode(', ', get_loaded_extensions()),
                'Memory Limit'         => size_format(wp_convert_hr_to_bytes(ini_get('memory_limit'))),
                'Post Max Size'        => size_format(wp_convert_hr_to_bytes(ini_get('post_max_size'))),
                'Upload max file size' => size_format(wp_convert_hr_to_bytes(ini_get('upload_max_filesize'))),
                'Time Limit'           => ini_get('max_execution_time'),
                'Max Input Vars'       => number_format(ini_get('max_input_vars')),
                'Display Errors'       => ini_get('display_errors') ? ini_get('display_errors') : 'OFF',
            ],
            'Database'  => [
                'Version' => $wpdb->db_version(),
                'Tables'  => $wpdb->tables(),
            ],
            'Server'    => [
                'Software' => sanitize_text_field($_SERVER['SERVER_SOFTWARE']),
                'Port'     => sanitize_text_field($_SERVER['SERVER_PORT']),
                'Protocol' => sanitize_text_field($_SERVER['SERVER_PROTOCOL']),
            ],
            'Sessions'  => [
                'Enabled'          => isset($_SESSION) ? 'ON' : 'OFF',
                'Name'             => ini_get('session.name'),
                'Path'             => ini_get('session.save_path'),
                'Use Cookies'      => ini_get('session.use_cookies') ? 'ON' : 'OFF',
                'Use Only Cookies' => ini_get('session.use_only_cookies') ? 'ON' : 'OFF',
                'Cookie Path'      => ini_get('session.cookie_path'),
            ],
            'Cookies'   => [
                'Domain' => (COOKIE_DOMAIN ? COOKIE_DOMAIN : 'N/A'),
                'Path'   => SITECOOKIEPATH,
            ],
            'WordPress' => [
                'Version'                 => $GLOBALS['wp_version'],
                'Locale'                  => get_locale(),
                'MU'                      => is_multisite() ? 'ON' : 'OFF',
                'Home'                    => get_option('home'),
                'Memory Limit'            => size_format(wp_convert_hr_to_bytes(WP_MEMORY_LIMIT)),
                'Max Memory Limit'        => size_format(wp_convert_hr_to_bytes(WP_MAX_MEMORY_LIMIT)),
                'Short Initialization'    => SHORTINIT ? 'ON' : 'OFF',
                'Debug Mode'              => WP_DEBUG ? 'ON' : 'OFF',
                'Debug Script'            => SCRIPT_DEBUG ? 'ON' : 'OFF',
                'Debug Log'               => WP_DEBUG_LOG ? 'ON' : 'OFF',
                'Cache'                   => WP_CACHE ? 'ON' : 'OFF',
                'Force SSL'               => FORCE_SSL_ADMIN ? 'ON' : 'OFF',
                'Cron'                    => !defined('DISABLE_WP_CRON') ? 'ON' : 'OFF',
                'Revisions'               => WP_POST_REVISIONS ? (WP_POST_REVISIONS ? 'ON' : 'OFF') : 'OFF',
                'Compress Stylesheets'    => defined('COMPRESS_CSS') && COMPRESS_CSS ? 'ON' : 'OFF',
                'Compress Scripts'        => defined('COMPRESS_SCRIPTS') && COMPRESS_SCRIPTS ? 'ON' : 'OFF',
                'Concatenate Scripts'     => defined('CONCATENATE_SCRIPTS ') && CONCATENATE_SCRIPTS ? 'ON' : 'OFF',
                'Enforce Gzip'            => defined('ENFORCE_GZIP ') && ENFORCE_GZIP ? 'ON' : 'OFF',
                'Directory Permissions'   => defined('FS_CHMOD_DIR') ? FS_CHMOD_DIR : 'OFF',
                'File Permissions'        => defined('FS_CHMOD_FILE') ? FS_CHMOD_DIR : 'OFF',
                'Filesystem Method'       => defined('FS_METHOD') ? FS_METHOD : get_filesystem_method(),
                'Proxy'                   => defined('WP_PROXY_HOST') ? 'ON' : 'OFF',
                'Block External Requests' => defined('WP_HTTP_BLOCK_EXTERNAL') && WP_HTTP_BLOCK_EXTERNAL ? 'ON' : 'OFF',
                'Save Queries'            => defined('SAVEQUERIES') ? (SAVEQUERIES ? 'ON' : 'OFF') : 'OFF',
            ],
            'Plugins'   => [],
        ];

        if (!function_exists('get_plugins')) {
            require_once ABSPATH.'wp-admin/includes/plugin.php';
        }
        // Plugins
        $plugins       = get_plugins();
        $activePlugins = get_option('active_plugins', []);
        foreach ($plugins as $path => $plugin):
            // If the plugin isn't active, don't show it.
            if (!in_array($path, $activePlugins)):
                continue;
            endif;

            $details['Plugins'][$plugin['Name']] = $plugin['Version'];
        endforeach;

        // Multi-site Plugins
        if (is_multisite()) :
            $networkPlugins = wp_get_active_network_plugins();
            foreach ($networkPlugins as $networkPluginFile) :
                $networkPlugin = get_plugin_data($networkPluginFile);

                $details['Network Active Plugins'][$networkPlugin['Name']] = $networkPlugin['Version'];
            endforeach;
        endif;

        return $details;
    }
}
