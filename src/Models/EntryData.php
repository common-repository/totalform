<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class EntryData
 *
 * @property Collection<EntrySection>|EntrySection[] $sections
 *
 * @package TotalForm\Models
 */
class EntryData extends Model
{
    /**
     * @var Entry
     */
    protected $entry;

    /**
     * @var array
     */
    protected $types = [
        'sections' => 'sections',
    ];

    /**
     * EntryData constructor.
     *
     * @param  Entry  $entry
     * @param  array  $attributes
     */
    public function __construct(Entry $entry, $attributes = [])
    {
        parent::__construct($attributes);
        $this->entry = $entry;
    }

    public function castToSections($data)
    {
        $sections = [];
        foreach ($data as $index => $section) {
            $sections[] = new EntrySection($this, $section);
        }

        return Collection::create($sections);
    }
}
