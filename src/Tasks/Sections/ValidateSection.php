<?php

namespace TotalForm\Tasks\Sections;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Models\FormSection;
use TotalForm\Services\FormValidator;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\ValidationException;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;

class ValidateSection extends Task
{
    /**
     * @var FormSection
     */
    protected $section;

    /**
     * @var array
     */
    protected $entry;

    /**
     * ValidateSection constructor.
     *
     * @param  FormSection  $section
     * @param  Collection|array  $entry
     *
     */
    public function __construct(FormSection $section, $entry)
    {
        $this->section = $section;
        $this->entry   = $entry;
    }


    protected function validate()
    {
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function execute()
    {
        if (!$this->section->blocks->isEmpty()) {
            $validation = FormValidator::instance()->validateSection($this->section, $this->entry);

            if ($validation->fails()) {
                ValidationException::throw(
                    __('Some fields are invalid.', 'totalform'),
                    $this->getErrorsFromValidation($validation)
                );
            }
        }

        return true;
    }

    /**
     * @param  \TotalFormVendors\Rakit\Validation\Validation  $validation
     *
     * @return array
     */
    protected function getErrorsFromValidation(\TotalFormVendors\Rakit\Validation\Validation $validation)
    {
        $errors = [];
        foreach ($validation->errors->toArray() as $field => $error) {
            $errors[$field] = array_values((array) $error);
        }

        return $errors;
    }
}
