<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalForm\Views\Template;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Manager;

/**
 * Class GetFormTemplate
 *
 * @package TotalForm\Tasks\Forms
 * @method static Template invoke(string $templateName)
 */
class GetFormTemplate extends Task
{
    /**
     * @var $templateName
     */
    protected $templateName;

    /**
     * GetFormTemplate constructor.
     *
     * @param $templateName
     */
    public function __construct($templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        return Manager::instance()->loadTemplate($this->templateName, 'default-template');
    }
}
