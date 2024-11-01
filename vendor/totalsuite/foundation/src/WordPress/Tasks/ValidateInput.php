<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\Rakit\Validation\Validator;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\ValidationException;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Plugin;

/**
 * Class Validate
 *
 * @method static bool invoke(array $rules, array $data, array $messages = [])
 * @method static bool invokeWithFallback($fallback, array $rules, array $data, array $messages = [])
 */
class ValidateInput extends Task
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var array
     */
    private $rules;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $messages;

    /**
     * Validation constructor.
     *
     * @param array $rules
     * @param array $data
     * @param array $messages
     */
    public function __construct(array $rules, array $data, array $messages = [])
    {
        $this->validator = Plugin::get(Validator::class);
        $this->rules = $rules;
        $this->data = $data;
        $this->messages = $messages;
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @return bool
     * @throws ValidationException
     */
    protected function execute()
    {
        $validation = $this->validator->make($this->data, $this->rules, $this->messages);
        $validation->validate();

        if ($validation->fails()) {
            throw new ValidationException('Validation failed', $validation->errors->toArray());
        }

        return true;
    }
}
