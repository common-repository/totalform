<?php


namespace TotalForm\Exceptions\Limitations;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\Contracts\Support\HTMLRenderable;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

/**
 * Class UnauthenticatedUser
 *
 * @package TotalForm\Exceptions\Limitations
 */
class UnauthenticatedUser extends Exception implements HTMLRenderable
{
    public function toHTML()
    {
        $html = Html::create('p', [], $this->getMessage());
        $html->addContent('<p><br>&mdash;<br><br></p>');
        $html->addContent(Html::create('a', ['href' => esc_attr(wp_login_url(add_query_arg([])))], __('Login')));

        return $html;
    }
}
