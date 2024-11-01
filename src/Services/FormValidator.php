<?php

namespace TotalForm\Services;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Filters\Validations\ValidationAliasesFilter;
use TotalForm\Filters\Validations\ValidationMessagesFilter;
use TotalForm\Filters\Validations\ValidationRulesFilter;
use TotalForm\Models\FormSection;
use TotalForm\Tasks\Utils\GetDefaultValidationMessages;
use TotalFormVendors\Rakit\Validation\Validation;
use TotalFormVendors\Rakit\Validation\Validator;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Concerns\ResolveFromContainer;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class BlockRegistry
 *
 * @package TotalForm
 */
class FormValidator
{
    use ResolveFromContainer;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var BlockRegistry
     */
    protected $blockRegistry;

    public function __construct(Validator $validator, BlockRegistry $blockRegistry)
    {
        $this->validator     = $validator;
        $this->blockRegistry = $blockRegistry;
    }

    /**
     * @param  FormSection  $section
     * @param  Collection  $entry
     */
    public function validateSection($section, $entry)
    {
        $validation = $this->buildValidationForSection($section);
        $validation->validate($entry->toArray());

        return $validation;
    }

    /**
     * @param  FormSection  $section
     *
     * @return Validation
     */
    public function buildValidationForSection(FormSection $section)
    {
        $rules    = [];
        $aliases  = [];
        $messages = GetDefaultValidationMessages::invokeWithFallback([]);

        foreach ($section->blocks as $block) {
            $blockType = $this->blockRegistry->getBlockType($block->type);
            if ($blockType::$category !== 'field') {
                continue;
            }

            $rules    = array_merge($rules, $blockType::getValidationRules($block));
            $messages = array_merge($messages, $blockType::getValidationMessages($block));
            $aliases  = array_merge($aliases, $blockType::getValidationAliases($block));
        }

        $rules    = ValidationRulesFilter::apply($rules, $section);
        $messages = ValidationMessagesFilter::apply($messages, $section);
        $aliases  = ValidationAliasesFilter::apply($aliases, $section);


        $validation = $this->validator->make([], array_filter($rules));
        $validation->setMessages(array_filter($messages));
        $validation->setAliases(array_filter($aliases));

        return $validation;
    }
}
