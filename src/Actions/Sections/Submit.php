<?php


namespace TotalForm\Actions\Sections;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Tasks\Entries\CreateEntry;
use TotalForm\Tasks\Sections\ExtractSectionData;
use TotalForm\Tasks\Sections\ProcessSection;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;

class Submit extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * @throws \TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception
     */
    public function execute()
    {
        $data  = $this->request->getParsedBody();
        $files = $this->request->getUploadedFiles();

        $formUid     = Arrays::pull($data, 'form_uid', '');
        $sectionUid  = Arrays::pull($data, 'section_uid', '');
        $sectionData = ExtractSectionData::invoke($sectionUid, $data, $files);

        return ProcessSection::invoke($formUid, $sectionUid, $sectionData)
                             ->toJsonResponse();
    }
}
