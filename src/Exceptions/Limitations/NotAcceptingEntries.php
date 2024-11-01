<?php


namespace TotalForm\Exceptions\Limitations;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\Contracts\Support\HTMLRenderable;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

/**
 * Class NotAcceptingEntries
 *
 * @package TotalForm\Exceptions\Limitations
 */
class NotAcceptingEntries extends Exception implements HTMLRenderable
{
    public function toHTML()
    {
        return Html::create('p', [], $this->getMessage());
    }
}
