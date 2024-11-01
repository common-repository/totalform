<?php

namespace TotalForm\Tasks\Utils;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Helpers\Misq;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Plugin;

/**
 * Class StoreNPS
 *
 * @package TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Options
 * @method static Collection invoke(Options $options, array $data)
 * @method static Collection invokeWithFallback($fallback, Options $options, array $data)
 */
class StoreFeedback extends Task
{
    /**
     * @var Options
     */
    protected $options;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Reset constructor.
     *
     * @param  Options  $options
     * @param  array  $data
     */
    public function __construct(Options $options, array $data)
    {
        $this->options = $options;
        $this->data    = array_map('esc_html', $data);
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
        $this->data['product'] = Plugin::env('product.id');
        if ($this->data['joinBetaTesters']) {
            $this->data['emailUser'] = wp_get_current_user()->user_email;
        }

        if ($this->data['includeEnvInfo']) {
            $this->data['envInfo'] = GetEnvironmentInfo::invoke();
        }

        Exception::throwUnless(
            $this->options->fill($this->data)->save(),
            'Could not save uninstall feedback'
        );

        wp_remote_post('https://collect.totalsuite.net/feedback', [
            'body'     => $this->data,
            'blocking' => false,
        ]);

        return $this->options->getBase();
    }
}
