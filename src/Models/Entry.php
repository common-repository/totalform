<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Exceptions\Entries\EntryNotFound;
use TotalForm\Models\Concerns\CastMetaFieldToCollection;
use TotalForm\Tasks\Entries\ConvertEntryToHTMLTable;
use TotalForm\Tasks\Entries\ConvertEntryToMap;
use TotalForm\Tasks\Entries\ConvertEntryToText;
use TotalFormVendors\TotalSuite\Foundation\Database\TableModel;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class Entry
 *
 * @property int $id
 * @property string $uid
 * @property string $form_uid
 * @property string $ip
 * @property string $user_agent
 * @property EntryData $data
 * @property Collection $meta
 * @property string $created_at
 * @property string $deleted_at
 * @property int $user_id
 * @property string $read_at
 * @property string $bookmarked_at
 *
 * @package TotalForm\Models
 */
class Entry extends TableModel
{
    use CastMetaFieldToCollection;

    /**
     * @var string
     */
    protected $table = 'totalform_entries';

    /**
     * @var array
     */
    protected $types = [
        'data' => 'data',
        'meta' => 'meta',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'uid',
        'form_uid',
        'ip',
        'user_agent',
        'data',
        'meta',
        'user_id',
        'created_at',
        'deleted_at',
        'read_at',
        'bookmarked_at',
    ];

    /**
     * @param  string  $data
     *
     * @return EntryData
     * @noinspection PhpUnused
     */
    public function castToData($data)
    {
        $entryData = [];

        if (!empty($data)) {
            $entryData = json_decode($data, true);
        }

        return new EntryData($this, $entryData);
    }

    /**
     * @param  string  $entryUid
     *
     * @return Entry
     * @throws Exception
     */
    public static function byUid($entryUid): Entry
    {
        $form = static::query()->where('uid', $entryUid)->first();
        EntryNotFound::throwUnless($form, __('Entry not found', 'totalform'));

        return $form;
    }

    /**
     * @param  int|string  $entryIdOrUid
     *
     * @return Entry
     * @throws Exception
     */
    public static function byIdOrUid($entryIdOrUid): Entry
    {
        $form = static::query()->where('id', $entryIdOrUid)
                      ->orWhere('uid', $entryIdOrUid)
                      ->first();
        EntryNotFound::throwUnless($form, __('Entry not found', 'totalform'));

        return $form;
    }

    public function toArray(): array
    {
        $attributes = parent::toArray();

        $attributes['created_at']   = !empty($attributes['created_at']) ? date_i18n(DATE_ATOM, strtotime($attributes['created_at'])) : null;
        $attributes['read_at'] = !empty($attributes['read_at']) ?  date_i18n(DATE_ATOM, strtotime($attributes['read_at'])) : null;
        $attributes['bookmarked_at'] = !empty($attributes['bookmarked_at']) ?  date_i18n(DATE_ATOM, strtotime($attributes['bookmarked_at'])) : null;
        $attributes['deleted_at']   = !empty($attributes['deleted_at']) ? date_i18n(DATE_ATOM, strtotime($attributes['deleted_at'])) : null;

        if (!empty($attributes['user_id'])) {
            $user               = get_userdata($attributes['user_id']);
            $attributes['user'] = [
                'id'     => $attributes['user_id'],
                'name'   => $user->display_name,
                'email'  => $user->user_email,
                'avatar' => get_avatar_url($user),
            ];
        } else {
            $attributes['user'] = [
                'id'     => 0,
                'name'   => __('Anonymous', 'totalform'),
                'email'  => '',
                'avatar' => '',
            ];
        }

        return $attributes;
    }

    public function getFieldsMap()
    {
        $fields   = [];
        foreach ($this->data->sections as $section) {
            foreach ($section->blocks as $block) {
                $fields[$block->uid] = $block->value;
            }
        }
        return $fields;
    }

    public function toPublic()
    {
        $self = clone $this;
        $self->setAttributes(
            [
                'uid' => $this->uid,
            ]
        );

        return $self;
    }

    public function toHtml()
    {
        return ConvertEntryToHTMLTable::invoke($this);
    }

    public function toText()
    {
        return ConvertEntryToText::invoke($this);
    }

    public function toMap()
    {
        return ConvertEntryToMap::invoke($this);
    }
}
