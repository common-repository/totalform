<?php

namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Exceptions\Limitations\FinishedException;
use TotalForm\Exceptions\Limitations\NotAcceptingEntries;
use TotalForm\Exceptions\Limitations\NotStartedException;
use TotalForm\Exceptions\Limitations\QuotaReached;
use TotalForm\Exceptions\Limitations\UnauthenticatedUser;
use TotalForm\Exceptions\Limitations\UnauthorizedUser;
use TotalForm\Models\Form;
use TotalForm\Models\FormLimitation;
use TotalForm\Plugin;
use TotalForm\Tasks\Entries\CountEntries;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Task;
use WP_User;

/**
 * Class CheckLimitations
 *
 * @package TotalForm\Tasks\Forms
 *
 * @method static void invoke(Form $form)
 * @method static void invokeWithFallback($fallback, Form $form)
 */
class CheckFormLimitations extends Task
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var WP_User
     */
    protected $user;

    /**
     * CheckLimitations constructor.
     *
     * @param  Form  $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
        $this->user = wp_get_current_user();
    }

    protected function validate()
    {
        return true;
    }

    public function getMessage($message)
    {
        if (!empty($this->form->settings->texts[$message])) {
            return __(
                $this->form->settings->texts[$message] ?: $message,
                'totalform'
            );
        }

        return __($message, 'totalform');
    }

    /**
     * @throws Exception
     */
    protected function execute()
    {
        $this->checkAcceptEntries();
        $this->checkSession($this->form->settings->limitations->get('session'));
        $this->checkQuota($this->form->settings->limitations->get('session'));
        $this->checkAuth($this->form->settings->limitations->get('auth'));
        $this->checkDate($this->form->settings->limitations->get('date'));
    }

    protected function checkAcceptEntries()
    {
        if (!$this->form->settings->isAcceptingNewEntries()) {
            NotAcceptingEntries::throw(
                $this->getMessage('This form is no longer accepting new entries.'),
                [],
                200
            );
        }
    }

    protected function checkQuota(FormLimitation $sessionRule)
    {
        if ($sessionRule->enabled) {
            $countEntries = CountEntries::invoke(['form_uid' => $this->form->uid]);
            $quota        = $sessionRule->arguments->get('quota', 1);
            if ($countEntries >= $quota) {
                QuotaReached::throw(
                    $this->getMessage(
                        'This form reached its quota. If you think this is a mistake, please consider contacting the webmaster.'
                    ),
                    [],
                    200
                );
            }
        }
    }

    protected function checkSession(FormLimitation $sessionRule)
    {
        if ($sessionRule->enabled) {
            $cookieValue = (int) Plugin::request()->getCookieParam("quota_{$this->form->uid}", 0);
            $quota       = $sessionRule->arguments->get('quota', 1);
            if ($cookieValue >= $quota) {
                QuotaReached::throw(
                    $this->getMessage(
                        'This form reached its quota. If you think this is a mistake, please consider contacting the webmaster.'
                    ),
                    [],
                    200
                );
            }
        }
    }

    protected function checkAuth(FormLimitation $authRule)
    {
        if ($authRule->enabled) {
            UnauthenticatedUser::throwIf(
                $this->user->ID === 0,
                $this->getMessage('This form is limited to authenticated users.'),
                [],
                501
            );

            if ($roles = $authRule->arguments->get('roles')) {
                $roles = array_filter($roles);
                $match = array_intersect((array) $this->user->roles, $roles);
                UnauthorizedUser::throwIf(
                    empty($match),
                    $this->getMessage(
                        'This form is limited to a specific group of users, please consider contacting your administrator if you think that is a mistake.'
                    )
                );
            }
        }
    }

    protected function checkDate(FormLimitation $dateRule)
    {
        if ($dateRule->enabled) {
            $now   = mysql2date('U', 'now');
            $start = $dateRule->arguments->get('start');
            $end   = $dateRule->arguments->get('end');

            NotStartedException::throwIf(
                $start && strtotime($start, $now) > $now,
                $this->getMessage(
                    'This form is not accepting entries yet.'
                )
            );

            FinishedException::throwIf(
                $end && strtotime($end, $now) <= $now,
                $this->getMessage('This form is no longer accepting new entries.')
            );
        }
    }
}
