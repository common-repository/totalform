<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;

/**
 * Class NextAction
 *
 * @package TotalForm\Models
 *
 * @property string $action
 * @property string $section_uid
 */
class NextAction extends Model
{
    public function isNext()
    {
        return $this->action === 'next';
    }

    public function isSkip()
    {
        return $this->action === 'section';
    }

    public function isSubmit()
    {
        return $this->action === 'submit';
    }
}
