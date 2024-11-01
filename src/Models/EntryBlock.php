<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Concerns\CastMetaFieldToCollection;
use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class EntryBlock
 *
 * @property string $uid
 * @property string $title
 * @property string $label
 * @property string $type
 * @property string $text
 * @property mixed $value
 * @property Collection $meta
 *
 * @package TotalForm\Models
 */
class EntryBlock extends Model
{
    use CastMetaFieldToCollection;

    /**
     * @var EntrySection
     */
    protected $entrySection;

    /**
     * @var array
     */
    protected $types = [
        'meta' => 'meta',
    ];

    /**
     * EntryBlock constructor.
     *
     * @param  EntrySection  $entrySection
     * @param  array  $attributes
     */
    public function __construct(EntrySection $entrySection, $attributes = [])
    {
        parent::__construct($attributes);
        $this->entrySection = $entrySection;
    }

    public function getSlug()
    {
        $slug = sanitize_title_with_dashes(remove_accents($this->title ?: $this->label));

        return str_replace('-', '_', $slug);
    }
}
