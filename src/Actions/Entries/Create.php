<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Models\Form;
use TotalForm\Tasks\Entries\CheckRecaptcha;
use TotalForm\Tasks\Entries\CreateEntry;
use TotalForm\Tasks\Entries\ExtractEntryData;

class Create extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }


    public function execute()
    {

        $data      = $this->request->getParsedBody();
        $files     = $this->request->getUploadedFiles();
        $form      = Form::byIdAndActive($data['form_uid']);
        $entryData = ExtractEntryData::invoke($form, $data, $files);


        return CreateEntry::invoke($form, $entryData)->toJsonResponse();
    }
}
