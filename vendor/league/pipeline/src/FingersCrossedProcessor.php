<?php
declare(strict_types=1);

namespace TotalFormVendors\League\Pipeline;
! defined( 'ABSPATH' ) && exit();


class FingersCrossedProcessor implements ProcessorInterface
{
    public function process($payload, callable ...$stages)
    {
        foreach ($stages as $stage) {
            $payload = $stage($payload);
        }

        return $payload;
    }
}
