<?php


namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Entry;
use TotalForm\Models\Form;
use TotalForm\Plugin;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\ValidationException;
use TotalFormVendors\TotalSuite\Foundation\Helpers\ReCaptcha;
use TotalFormVendors\TotalSuite\Foundation\Http\ResponseFactory;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class CheckRecaptcha
 *
 * @package TotalForm\Tasks\Entries
 * @method static Entry invoke(Form $form, array $data)
 */
class CheckRecaptcha extends Task
{
    protected $data;
    protected $form;

    public function __construct(Form $form, array $data)
    {
        $this->form = $form;
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */

    public function getRecaptchaMessage($message)
    {
        if (!empty($this->form->settings->texts[$message])) {
            return __($this->form->settings->texts[$message], 'totalform');
        }

        return __($message, 'totalform');;
    }

    protected function validate()
    {
        if ($this->form->settings->behaviors['recaptcha.enabled'] && empty($this->data['recaptcha'])) {
            ValidationException::throw(
                $this->getRecaptchaMessage('reCaptcha test failed. Please try again.'),
                []
            );
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        if ($this->form->settings->behaviors['recaptcha.enabled']) {
            $secret = Plugin::options('recaptcha.siteSecret');
            $threshold = Plugin::options('recaptcha.threshold', '0.5');
            $token = Plugin::request('recaptcha');
            $ip = Plugin::request()->ip();

            Exception::throwIf(
                !ReCaptcha::check($token, $threshold, $secret, $ip),
                $this->getRecaptchaMessage('Invalid reCaptcha. Please try again.')
            );
        }

        return true;
    }
}
