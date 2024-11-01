<?php

namespace TotalForm\Services;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\BlockType;
use TotalForm\Blocks\DefaultBlockType;
use TotalForm\Exceptions\Blocks\BlockException;
use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Concerns\ResolveFromContainer;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class BlockRegistry
 *
 * @package TotalForm
 */
class BlockRegistry
{
    use ResolveFromContainer;

    /**
     * @var Collection<BlockType>|BlockType[]
     */
    protected $blockTypes;

    public function __construct()
    {
        $this->blockTypes = Collection::create();
    }

    /**
     * @param  string|BlockType  $class
     *
     * @throws Exception
     */
    public function registerType($class)
    {
        if (!class_exists($class)) {
            BlockException::throw(sprintf('Class not found (%s)', $class));
        }

        if (!is_a($class, BlockType::class, true)) {
            BlockException::throw(sprintf('Field must extends %s class', BlockType::class));
        }

        if ($this->blockTypes->has($class::$id)) {
            BlockException::throw(sprintf('Field type %s is already registered', $class::$id));
        }

        $this->blockTypes[$class::$id] = $class;

        return true;
    }

    /**
     * @param  string  $type
     *
     * @param  string  $fallback
     *
     * @return BlockType
     */
    public function getBlockType($type, $fallback = DefaultBlockType::class)
    {
        return $this->blockTypes->get($type, $fallback);
    }

    /**
     * @param $class
     *
     * @return bool
     * @throws Exception
     */
    public static function register($class)
    {
        return static::instance()->registerType($class);
    }

    /**
     * @param $type
     *
     * @param  string  $fallback
     *
     * @return BlockType
     */
    public static function getByType($type, $fallback = DefaultBlockType::class)
    {
        return static::instance()->getBlockType($type, $fallback);
    }

    /**
     * @param  FormBlock  $block
     *
     * @return BlockType
     */
    public static function render(FormBlock $block)
    {
        return static::instance()->getBlockType($block->type)::render($block);
    }
}
