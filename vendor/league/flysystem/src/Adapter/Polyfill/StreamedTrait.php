<?php

namespace TotalFormVendors\League\Flysystem\Adapter\Polyfill;
! defined( 'ABSPATH' ) && exit();


trait StreamedTrait
{
    use StreamedReadingTrait;
    use StreamedWritingTrait;
}
