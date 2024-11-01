<?php

namespace TotalFormVendors\League\Event;
! defined( 'ABSPATH' ) && exit();


class Generator implements GeneratorInterface
{
    use GeneratorTrait {
        addEvent as public;
    }
}
