<?php

namespace TotalForm\Tasks\Utils;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class GetDefaultValidationMessages
 *
 * @package TotalForm\Tasks
 * @method static array invoke()
 * @method static array invokeWithFallback(array $fallback)
 */
class GetDefaultValidationMessages extends Task
{
    /**
     * @inheritDoc
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        $messages = [];

        $messages['required']      = __('Required', 'totalform');
        $messages['email']         = __('Must be a valid email', 'totalform');
        $messages['min']           = str_replace(['{{min}}'], [':min'], __('Minimum is {{min}}', 'totalform'));
        $messages['max']           = str_replace(['{{max}}'], [':max'], __('Maximum is {{max}}', 'totalform'));
        $messages['minLength']     = str_replace(['{{minLength}}'],
                                                 [':minLength'],
                                                 __('Minimum length is {{minLength}}', 'totalform'));
        $messages['maxLength']     = str_replace(['{{maxLength}}'],
                                                 [':maxLength'],
                                                 __('Maximum length is {{maxLength}}', 'totalform'));
        $messages['maxDate']       = str_replace(
            ['{{maxDate}}'],
            [':time'],
            __('Must be a date before {{maxDate}}', 'totalform')
        );
        $messages['minDate']       = str_replace(
            ['{{minDate}}'],
            [':time'],
            __('Must be a date after {{minDate}}', 'totalform')
        );
        $messages['numeric']       = __('Must be numeric', 'totalform');
        $messages['date']          = __('Must be a valid date format', 'totalform');
        $messages['between']       = str_replace(
            ['{{min}}', '{{max}}'],
            [':min', ':max'],
            __('Must be between {{min}} and {{max}}', 'totalform')
        );
        $messages['uploaded_file'] = __('Must be a valid uploaded file', 'totalform');
        $messages['mimes']         = str_replace(
            ['{{allowedTypes}}'],
            [':allowed_types'],
            __('File type must be {{allowedTypes}}', 'totalform')
        );
        $messages['in']            = str_replace(
            ['{{allowedValues}}'],
            [':allowed_values'],
            __('Must be one of: {{allowedValues}}', 'totalform')
        );

        return $messages;
    }
}
