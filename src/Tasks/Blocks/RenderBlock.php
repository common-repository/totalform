<?php

namespace TotalForm\Tasks\Blocks;
! defined( 'ABSPATH' ) && exit();



use Exception;
use TotalForm\Models\FormBlock;
use TotalForm\Services\BlockRegistry;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class RenderBlock
 *
 * @package TotalForm\Tasks\Form
 * @method static array invoke(FormBlock $block)
 * @method static array invokeWithFallback(array $fallback, FormBlock $block)
 */
class RenderBlock extends Task
{
    /**
     * @var FormBlock
     */
    protected $block;

    /**
     * RenderBlock constructor.
     *
     * @param  FormBlock  $block
     */
    public function __construct(FormBlock $block)
    {
        $this->block = $block;
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
        return BlockRegistry::render($this->block);
    }
}
