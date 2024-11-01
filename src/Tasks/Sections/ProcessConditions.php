<?php

namespace TotalForm\Tasks\Sections;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Models\Condition;
use TotalForm\Models\Form;
use TotalForm\Models\FormSection;
use TotalForm\Models\NextAction;
use TotalForm\Tasks\ProcessCondition;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class ProcessConditions
 *
 * @package TotalForm\Tasks\Sections
 * @method static NextAction invoke(Form $form, FormSection $section, array $entry)
 * @method static NextAction invokeWithFallback($fallback, Form $form, FormSection $section, array $entry)
 */
class ProcessConditions extends Task
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var FormSection
     */
    protected $section;

    /**
     * @var array
     */
    protected $entry;

    /**
     * ProcessSection constructor.
     *
     * @param  Form  $form
     * @param  FormSection  $section
     * @param  Collection  $entry
     */
    public function __construct(Form $form, FormSection $section, Collection $entry)
    {
        $this->form    = $form;
        $this->section = $section;
        $this->entry   = $entry;
    }

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
        switch ($this->section->action) {
            case Form::ACTION_CONDITIONS:
            {
                return $this->applyConditions();
            }
            case Form::ACTION_SUBMIT:
            {
                return NextAction::from(
                    [
                        'action'      => Form::ACTION_SUBMIT,
                        'section_uid' => null,
                    ]
                );
            }
            case Form::ACTION_SECTION:
            {
                return NextAction::from(
                    [
                        'action'      => Form::ACTION_SECTION,
                        'section_uid' => $this->section->next_section_uid,
                    ]
                );
            }
            case Form::ACTION_NEXT:
            default :
            {
                $next = $this->section->nextSection;

                if ($next instanceof FormSection) {
                    return NextAction::from(
                        [
                            'action'      => Form::ACTION_NEXT,
                            'section_uid' => $next->uid,
                        ]
                    );
                }

                return NextAction::from(
                    [
                        'action'      => Form::ACTION_SUBMIT,
                        'section_uid' => null,
                    ]
                );
            }
        }
    }

    /**
     * @return NextAction
     */
    protected function applyConditions(): NextAction
    {
        foreach ($this->section->conditions as $condition) {
            if ($this->applyOperator($condition) === true) {
                return NextAction::from(
                    [
                        'action'      => $condition->action,
                        'section_uid' => $condition->next_section_uid,
                    ]
                );
            }
        }

        return NextAction::from(
            [
                'action'      => Form::ACTION_NEXT,
                'section_uid' => $this->section->getNextSection()->uid,
            ]
        );
    }

    /**
     * @param  Condition  $condition
     *
     * @return bool
     */
    protected function applyOperator(Condition $condition): bool
    {
        $input = $this->entry->get($condition->field);

        if ($input === null) {
            return false;
        }

        return ProcessCondition::invokeWithFallback(false, $input, $condition->operator, $condition->value);
    }
}
