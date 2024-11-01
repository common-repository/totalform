<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class Form
 *
 * @property string $uid
 * @property string $title
 * @property Collection<EntryBlock>|EntryBlock[] $blocks
 *
 * @package TotalForm\Models
 */
class EntrySection extends Model
{
    /**
     * @var array
     */
    protected $types = [
        'blocks' => 'blocks',
    ];

    /**
     * @var EntryData $entryData
     */
    protected $entryData;

    public function __construct(EntryData $entryData, $attributes = [])
    {
        parent::__construct($attributes);
        $this->entryData = $entryData;
    }

    /**
     * @param  array  $data
     *
     * @return Collection
     * @noinspection PhpUnused
     */
    public function castToBlocks($data): Collection
    {
        $layouts = [];
        foreach ($data as $index => $block) {
            $layouts[] = new EntryBlock($this, $block);
        }

        return Collection::create($layouts);
    }
}
