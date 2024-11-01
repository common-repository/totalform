<?php


namespace TotalForm\Actions\Forms;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Tasks\Forms\ShareFormViaEmail;

class Share extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }

    public function execute($formUid)
    {
        return ShareFormViaEmail::invoke($formUid, $this->request->getParsedBody());
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'formUid' => [
                'expression'        => '(?<formUid>([\w-]+))',
                'sanitize_callback' => static function ($formUid) {
                    return (string) $formUid;
                },
            ],
        ];
    }
}
