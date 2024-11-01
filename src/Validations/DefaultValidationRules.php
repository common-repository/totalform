<?php

namespace TotalForm\Validations;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Models\FormValidationRule;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;

class DefaultValidationRules
{
    protected static $fileTypes = [
        "documents" => ['docx', 'pdf', 'txt', 'pptx'],
        "images"    => ['png', 'jpg', 'jpeg'],
        "videos"    => ['avi', 'mp4', 'mov'],
        "archives"  => ['zip', 'rar', 'tar', 'gzip'],
    ];

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function required(FormValidationRule $validation)
    {
        return 'required';
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function email(FormValidationRule $validation)
    {
        return 'email';
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function min(FormValidationRule $validation)
    {
        return sprintf('min:%d', (int) $validation->arguments->get('value', 0));
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function max(FormValidationRule $validation)
    {
        return sprintf('max:%d', (int) $validation->arguments->get('value', 0));
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function maxLength(FormValidationRule $validation)
    {
        return sprintf('maxLength:%s', (int) $validation->arguments->get('value', 0));
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return callable
     */
    public static function minLength(FormValidationRule $validation)
    {
        return sprintf('minLength:%s', (int) $validation->arguments->get('value', 0));
    }


    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function minSelection(FormValidationRule $validation)
    {
        return static::min($validation);
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function maxSelection(FormValidationRule $validation)
    {
        return static::max($validation);
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */

    public static function dateFormat()
    {
        return 'regex:/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/';
    }

    public static function maxDate(FormValidationRule $validation)
    {
        $date = date('Y-m-d', strtotime($validation->arguments->get('value')));

        return sprintf('maxDate:%s', $date);
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function minDate(FormValidationRule $validation)
    {
        $date = date('Y-m-d', strtotime($validation->arguments->get('value')));

        return sprintf('minDate:%s', $date);
    }

    /**
     * @return string
     */
    public static function number()
    {
        return 'numeric';
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function date(FormValidationRule $validation)
    {
        return sprintf('date:%s', $validation->arguments->get('format'));
    }

    // Intrinsic validations

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function inArray(FormValidationRule $validation)
    {
        return sprintf('in_array:%s', implode(',', (array) $validation->arguments->get('values')));
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function between(FormValidationRule $validation)
    {
        return sprintf('between:%d,%d', $validation->arguments->get('min', 0), $validation->arguments->get('max', 0));
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function file(FormValidationRule $validation)
    {
        return 'uploaded_file';
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function fileType(FormValidationRule $validation)
    {
        $types   = $validation->arguments->filter()->keys()->toArray();
        $allowed = Arrays::flatten(Arrays::only(static::$fileTypes, $types));

        return sprintf('mimes:%s', implode(',', $allowed));
    }

    /**
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function fileSize(FormValidationRule $validation)
    {
        return sprintf('max:%s', "{$validation->arguments->get('value', 0)}KB");
    }

    public static function maxSize(FormValidationRule $validation)
    {
        return sprintf('max:%s', "{$validation->arguments->get('value', 0)}KB");
    }

    public static function minSize(FormValidationRule $validation)
    {
        return sprintf('min:%s', "{$validation->arguments->get('value', 0)}KB");
    }


    public static function noop(FormValidationRule $validation)
    {
        return;
    }

    /**
     * @param  string  $name
     * @param  FormValidationRule  $validation
     *
     * @return string
     */
    public static function from($name, FormValidationRule $validation)
    {
        return is_callable([static::class, $name]) ? static::$name($validation) : null;
    }
}
