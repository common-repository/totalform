<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class GetForm
 *
 * @package TotalForm\Tasks\Forms
 * @method static string invoke(string $formUid, string $slug, int $iteration = 1)
 */
class GenerateUniqueSlug extends Task
{
    /**
     * @var $formUid
     * @var $slug
     */
    protected $formUid;
    protected $slug;
    /**
     * @var int
     */
    protected $iteration;

    /**
     * GetForm constructor.
     *
     * @param $formUid
     * @param $slug
     */
    public function __construct($formUid, $slug, $iteration = 1)
    {
        $this->formUid   = $formUid;
        $this->slug      = sanitize_title_with_dashes($slug);
        $this->iteration = $iteration;
    }

    protected function validate(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function execute()
    {
        $query = Form::query()
                     ->whereIn('slug', [$this->slug, "{$this->slug}-{$this->iteration}"])
                     ->where(
                         'uid',
                         '!=',
                         $this->formUid
                     );

        $found = $query->count();

        if ($found > 1) {
            return GenerateUniqueSlug::invoke($this->formUid, $this->slug, $this->iteration + 1);
        } elseif ($found === 1) {
            return "{$this->slug}-{$this->iteration}";
        }

        return $this->slug;
    }


}
