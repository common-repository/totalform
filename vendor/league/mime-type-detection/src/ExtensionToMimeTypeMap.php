<?php

declare(strict_types=1);

namespace TotalFormVendors\League\MimeTypeDetection;
! defined( 'ABSPATH' ) && exit();


interface ExtensionToMimeTypeMap
{
    public function lookupMimeType(string $extension): ?string;
}
