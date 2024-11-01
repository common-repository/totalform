<?php


namespace TotalForm\Models\Concerns;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

trait CastMetaFieldToCollection
{
    public function castToMeta($data)
    {
        $meta = [];

        if (!empty($data)) {
            $meta = is_string($data) ? json_decode($data, true) : $data;
        }

        return Collection::create($meta);
    }
}
