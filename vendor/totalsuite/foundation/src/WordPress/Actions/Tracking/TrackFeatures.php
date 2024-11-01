<?php
namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Tracking;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Contracts\TrackingStorage;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Plugin;

class TrackFeatures extends Action
{
    /**
     * @var Options
     */
    protected $options;

    /**
     * TrackFeatures constructor.
     */
    public function __construct()
    {
        $this->options = Plugin::get(TrackingStorage::class);
    }


    public function execute()
    {
        $features = $this->options->get('features', []);

        $features[] = [
            'action' => $this->request->getParsedBodyParam('action'),
            'label'  => $this->request->getParsedBodyParam('label'),
            'date'   => date(DATE_ATOM)
        ];

        $this->options->set('features', $features)->save();

        wp_send_json_success();
    }

    public function authorize(): bool
    {
        return true;
    }
}