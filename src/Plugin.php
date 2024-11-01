<?php

namespace TotalForm;
! defined( 'ABSPATH' ) && exit();


use Exception;
use TotalForm\Actions\Entries\Bookmark as EntriesBookmarkAction;
use TotalForm\Actions\Entries\Create as EntriesCreateAction;
use TotalForm\Actions\Entries\Delete as EntriesDeleteAction;
use TotalForm\Actions\Entries\Export as EntriesExportAction;
use TotalForm\Actions\Entries\Get as EntriesGetAction;
use TotalForm\Actions\Entries\Index as EntriesIndexAction;
use TotalForm\Actions\Entries\Read as EntriesReadAction;
use TotalForm\Actions\Entries\RemoveBookmark as EntriesRemoveBookmarkAction;
use TotalForm\Actions\Entries\Restore as EntriesRestoreAction;
use TotalForm\Actions\Entries\Trash as EntriesTrashAction;
use TotalForm\Actions\Entries\Unread as EntriesUnreadAction;
use TotalForm\Actions\Entries\Update as EntriesUpdateAction;
use TotalForm\Actions\Forms\Create as FormsCreateAction;
use TotalForm\Actions\Forms\Preview as FormsPreviewAction;
use TotalForm\Actions\Forms\Delete as FormsDeleteAction;
use TotalForm\Actions\Forms\Get as FormsGetAction;
use TotalForm\Actions\Forms\Index as FormsIndexAction;
use TotalForm\Actions\Forms\Reset as FormsResetAction;
use TotalForm\Actions\Forms\Restore as FormsRestoreAction;
use TotalForm\Actions\Forms\Share as FormsShareAction;
use TotalForm\Actions\Forms\Trash as FormsTrashAction;
use TotalForm\Actions\Forms\Update as FormsUpdateAction;
use TotalForm\Actions\Utils\Feedback;
use TotalForm\Capabilities\UserCanCreateEntries;
use TotalForm\Capabilities\UserCanCreateForm;
use TotalForm\Capabilities\UserCanDeleteEntries;
use TotalForm\Capabilities\UserCanDeleteForm;
use TotalForm\Capabilities\UserCanExportEntries;
use TotalForm\Capabilities\UserCanManageOptions;
use TotalForm\Capabilities\UserCanUpdateEntries;
use TotalForm\Capabilities\UserCanUpdateForm;
use TotalForm\Capabilities\UserCanViewEntries;
use TotalForm\Capabilities\UserCanViewForms;
use TotalForm\Models\Entry;
use TotalForm\Models\Form;
use TotalForm\Pages\Dashboard;
use TotalForm\Services\PluginServiceProvider;
use TotalForm\Tasks\Blocks\RegisterDefaultBlockTypes;
use TotalForm\Tasks\Forms\SetupPreviewFormTemplate;
use TotalForm\Tasks\Forms\SetupViewFormTemplate;
use TotalForm\Tasks\Utils\AttachDefaultCapabilitiesToDefaultRoles;
use TotalForm\Tasks\Utils\DeleteUploadedFiles;
use TotalForm\Tasks\Utils\FlushRewriteRules;
use TotalForm\Tasks\Utils\RegisterDefaultAssets;
use TotalForm\Tasks\Utils\WipeFormsData;
use TotalFormVendors\League\Container\ServiceProvider\ServiceProviderInterface;
use TotalFormVendors\TotalSuite\Foundation\Migration\Migrator;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Options\Defaults as GetDefaults;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Options\Get as GetOptions;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Options\Reset as ResetOptions;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Options\Update as UpdateOptions;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Plugin as AbstractPlugin;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Rest\Router;
use TotalForm\Tasks\Utils\DetachDefaultCapabilitiesFromDefaultRoles;


/**
 * Class Plugin
 *
 * @package TotalForm
 */
class Plugin extends AbstractPlugin
{
    /**
     * Initiate the plugin
     *
     * @throws Exception
     */
    public function run()
    {
        // Backoffice page
        new Dashboard();

        // View form
        SetupViewFormTemplate::invoke();

        // Preview form
        SetupPreviewFormTemplate::invoke();

        // Default blocks
        RegisterDefaultBlockTypes::invoke();
    }

    /**
     * @inheritDoc
     */
    public function getServiceProvider(): ServiceProviderInterface
    {
        return new PluginServiceProvider();
    }

    /**
     * @inheritDoc
     * @TODO: Add capabilities to the routes
     */
    public function registerRoutes(Router $router)
    {
        // Listing of forms
        $router->get('/admin/form', FormsIndexAction::class)->capability(UserCanViewForms::class);
        // Get a form
        $router->get('/admin/form', FormsGetAction::class)->capability(UserCanViewForms::class);;
        // Create a form
        $router->post('/admin/form', FormsCreateAction::class)->capability(UserCanCreateForm::class);


        // Update a form
        $router->put('/admin/form', FormsUpdateAction::class)->capability(UserCanUpdateForm::class);
        // Trash a form
        $router->patch('/admin/form/trash', FormsTrashAction::class)->capability(UserCanDeleteForm::class);
        // Delete a form
        $router->delete('/admin/form', FormsDeleteAction::class)->capability(UserCanDeleteForm::class);
        // Restore a form
        $router->patch('/admin/form/restore', FormsRestoreAction::class)->capability(UserCanDeleteForm::class);
        // Reset a form
        $router->post('/admin/form/reset', FormsResetAction::class)->capability(UserCanDeleteEntries::class);
        // Create a form
        // @TODO review the capability
        $router->post('/admin/form/share', FormsShareAction::class);

        // Listing of entries
        $router->get('/admin/entry', EntriesIndexAction::class)->capability(UserCanViewEntries::class);
        // Get an entry
        $router->get('/admin/entry', EntriesGetAction::class)->capability(UserCanViewEntries::class);
        // Create an entry
        // @TODO review the action EntriesCreateAction
        $router->post('/admin/entry', EntriesCreateAction::class)->capability(UserCanCreateEntries::class);
        // Export entries
        $router->post('/admin/entry/export', EntriesExportAction::class)->capability(UserCanExportEntries::class);
        // Update an entry
        $router->put('/admin/entry', EntriesUpdateAction::class);
        // Trash an entry
        $router->post('/admin/entry/trash', EntriesTrashAction::class)->capability(UserCanDeleteEntries::class);
        // Restore an entry
        $router->post('/admin/entry/restore', EntriesRestoreAction::class)->capability(UserCanDeleteEntries::class);
        // Delete an entry
        $router->post('/admin/entry/delete', EntriesDeleteAction::class)->capability(UserCanDeleteEntries::class);
        // Read an entry

        $router->post('/admin/entry/read', EntriesReadAction::class)->capability(UserCanUpdateEntries::class);
        // Unread an entry
        $router->post('/admin/entry/unread', EntriesUnreadAction::class)->capability(UserCanUpdateEntries::class);
        // Bookmark an entry
        $router->post('/admin/entry/bookmark', EntriesBookmarkAction::class)->capability(UserCanUpdateEntries::class);
        // Remove bookmark an entry
        $router->post('/admin/entry/remove-bookmark', EntriesRemoveBookmarkAction::class)->capability(
            UserCanUpdateEntries::class
        );

        $router->post('/section', Actions\Sections\Submit::class);
        $router->post('/entry', Actions\Entries\Submit::class);

        // Options

        $router->get('/admin/options', GetOptions::class)->capability(UserCanManageOptions::class);
        // Update options
        $router->post('/admin/options', UpdateOptions::class)->capability(UserCanManageOptions::class);
        // Reset options
        $router->delete('/admin/options', ResetOptions::class)->capability(UserCanManageOptions::class);
        // Default options
        $router->get('/admin/options/defaults', GetDefaults::class)->capability(UserCanManageOptions::class);

        // Feedback
        $router->post('/admin/feedback', Feedback::class)->capability(UserCanManageOptions::class);
    }

    /**
     * @inheritDoc
     */
    public function registerShortCodes()
    {
        Shortcodes\Form::register();
    }

    /**
     * @inheritDoc
     */
    public function registerAssets()
    {
        RegisterDefaultAssets::invoke();
    }

    /**
     * @inheritDoc
     */
    public function registerWidgets()
    {
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function onActivation()
    {
        Migrator::instance()->execute();

        /**
         * Capabilities
         */

        AttachDefaultCapabilitiesToDefaultRoles::invoke();

        /**
         * Rewrite rules
         */
        FlushRewriteRules::invoke();
    }

    /**
     * @inheritDoc
     */
    public function onDeactivation()
    {
        /**
         * Capabilities
         */
        DetachDefaultCapabilitiesFromDefaultRoles::invoke();

        /**
         * Rewrite rules
         */
        FlushRewriteRules::invoke();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public static function onUninstall()
    {
        define('TOTALFORM_UNINSTALLING', true);

        /*
         * Data
         */
        $wipeData = (bool) static::options('general.wipeData', false);

        if ($wipeData) {
            DeleteUploadedFiles::invoke();
            WipeFormsData::invoke();
        }
        /**
         * Capabilities
         */
        DetachDefaultCapabilitiesFromDefaultRoles::invoke();

        /**
         * Rewrite rules
         */
        FlushRewriteRules::invoke();
    }

    /**
     * @return array
     */
    public function objectsCount()
    {
        return [
            'forms' => Form::count(),
            'entries' => Entry::count(),
        ];
    }
}
