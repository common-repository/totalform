<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Models\Form;
use TotalForm\Tasks\Entries\CheckRecaptcha;
use TotalForm\Tasks\Entries\CreateEntry;
use TotalForm\Tasks\Entries\ExtractEntryData;
use TotalForm\Tasks\Forms\ProcessFormNotifications;

class Submit extends \TotalFormVendors\TotalSuite\Foundation\Action
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
        $data  = $this->request->getParsedBody();

        $files = $this->request->getUploadedFiles();

        $form      = Form::byIdAndActive($data['form_uid']);
        $entryData = ExtractEntryData::invoke($form, $data, $files);


        CheckRecaptcha::invoke($form, $data);

        // Create entry
        $entry = CreateEntry::invoke($form, $entryData);

        // Send notifications
        ProcessFormNotifications::invoke($form, $entry);

        return [
            'success' => true,
            'data'    => [
                'entry'      => $entry->toPublic()->toArray(),
                'customView' => $form->getAfterEntryView(),
            ],
        ];
    }
}
