<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Exceptions\Files\FileUploadException;
use TotalForm\Models\Entry;
use TotalForm\Models\EntrySection;
use TotalForm\Models\FormBlock;
use TotalForm\Models\FormValidationRule;
use TotalForm\Plugin;
use TotalForm\Validations\DefaultValidationRules;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class File extends BlockType
{
    public static $category = 'field';
    public static $id       = 'file-field';
    public static $icon     = 'crop_7_5';

    use InputHelpers;

    public static function input(FormBlock $block)
    {
        $types = [];
        if ($block->argument('validations.docFile.enabled')) {
            $types[] = 'doc/*';
        }
        if ($block->argument('validations.imageFile.enabled')) {
            $types[] = 'image/*';
        }
        if ($block->argument('validations.videoFile.enabled')) {
            $types[] = 'video/*';
        }
        if ($block->argument('validations.archiveFile.enabled')) {
            $types[] = 'application/zip';
        }

        $input = Html::create(
            'input',
            [
                'type'        => 'file',
                'name'        => static::getInputName($block->section->uid, $block->uid),
                'id'          => static::getInputId($block->uid),
                'slug'        => $block->getSlug(),
                'placeholder' => $block->argument('placeholder'),
                'value'       => $block->argument('defaultValue'),
            ]
            + ($block->argument('validations.required.enabled') ? ['required'] : [])
            + (empty($types) ? [] : ['accept' => implode(',', $types)])
        );

        return $input;
    }

    public static function getValidationRules(FormBlock $block)
    {
        $rules = parent::getValidationRules($block);
        if ($block->isRequired()) {
            $rules[$block->uid][] = DefaultValidationRules::file(FormValidationRule::from());
        }

        $rules[$block->uid][] = DefaultValidationRules::fileType(
            FormValidationRule::from(
                [
                    'enabled'   => true,
                    'messages'  => [],
                    'arguments' => [
                        'archives'  => $block->argument('validations.archiveFile.enabled'),
                        'documents' => $block->argument('validations.docFile.enabled'),
                        'images'    => $block->argument('validations.imageFile.enabled'),
                        'videos'    => $block->argument('validations.videoFile.enabled'),
                    ],
                ]
            )
        );

        return $rules;
    }

    public static function getValidationMessages(FormBlock $formBlock)
    {
        return [
            "{$formBlock->uid}:max" => str_replace(
                ['{{max}}'],
                [':max'],
                __('Maximum allowed file size is {{max}}', 'totalform')
            ),
        ];
    }

    public static function getEntryBlockFromRawValue($file, FormBlock $block, EntrySection $entrySection, Entry $entry)
    {
        $entryBlock = parent::getEntryBlockFromRawValue($file, $block, $entrySection, $entry);

        if (empty($file)) {
            $entryBlock->value = '';
            $entryBlock->text  = '';

            return $entryBlock;
        }

        $filterDir = static function ($dirs) use ($entry) {
            $uploads = Plugin::env('path.userUploads');
            $url     = Plugin::env('url.userUploads.base');

            $dirs['subdir'] = sprintf("%s/%s", $entry->form_uid, $entry->uid);
            $dirs['path']   = sprintf("%s/%s", $uploads, $dirs['subdir']);
            $dirs['url']    = sprintf("%s/%s/%s", $url, $entry->form_uid, $entry->uid);

            return $dirs;
        };

        add_filter('upload_dir', $filterDir);
        $file = wp_handle_upload($file, ['test_form' => false]);
        remove_filter('upload_dir', $filterDir);

        if (!empty($file['error'])) {
            FileUploadException::throw(__('File could not be stored', 'totalform'), [$file['error']]);
        }

        $entryBlock->value            = $entryBlock->text = $file['url'];
        $entryBlock->meta['filename'] = basename($file['url']);

        return $entryBlock;
    }

    public static function getMetadataFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        return [
            'size' => $value['size'] ?? 0,
            'type' => $value['type'] ?? 'application/unknown',
        ];
    }
}
