<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;

/**
 * Class FormDesign
 * @property string $typography{
 * @type string $fontFamily
 * }
 * @property string $space
 * @property string $size
 * @property string $radius
 * @property array $colors {
 * @type string $primary
 * @type string $secondary
 * @type string $dark
 * @type string $background
 * @type string $error
 * @type string $success
 * }
 * @property string $customCss
 *
 * @package TotalForm\Models
 */
class FormDesign extends Model
{

}
