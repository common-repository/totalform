<?php

namespace TotalForm\Tasks\Utils;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Capabilities\UserCanCreateEntries;
use TotalForm\Capabilities\UserCanCreateForm;
use TotalForm\Capabilities\UserCanDeleteEntries;
use TotalForm\Capabilities\UserCanDeleteForm;
use TotalForm\Capabilities\UserCanExportEntries;
use TotalForm\Capabilities\UserCanManageModules;
use TotalForm\Capabilities\UserCanManageOptions;
use TotalForm\Capabilities\UserCanUpdateEntries;
use TotalForm\Capabilities\UserCanUpdateForm;
use TotalForm\Capabilities\UserCanViewEntries;
use TotalForm\Capabilities\UserCanViewForms;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Roles;

/**
 * Class AttachRoles
 *
 * @package TotalForm\Tasks
 * @method static array invoke()
 * @method static array invokeWithFallback(array $fallback)
 */
class AttachDefaultCapabilitiesToDefaultRoles extends Task
{
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
        Roles::set(
            Roles::ADMINISTRATOR,
            [
                UserCanManageOptions::NAME,
                UserCanManageModules::NAME,
                UserCanCreateForm::NAME,
                UserCanUpdateForm::NAME,
                UserCanDeleteForm::NAME,
                UserCanViewForms::NAME,
                UserCanCreateEntries::NAME,
                UserCanUpdateEntries::NAME,
                UserCanDeleteEntries::NAME,
                UserCanExportEntries::NAME,
                UserCanViewEntries::NAME,
            ]
        );
    }
}
