<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class Form
 *
 * @property Collection<FormLimitation>|FormLimitation[] $limitations
 * @property Collection<FormNotification>|FormNotification[] $notifications
 * @property Collection<FormBehavior>|FormBehavior[] $behaviors
 * @property FormDesign $design
 * @property Collection|string[] $texts
 *
 * @package TotalForm\Models
 */
class FormSettings extends Model
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var array
     */
    protected $types = [
        'limitations'   => 'limitations',
        'behaviors'     => 'behaviors',
        'design'        => 'design',
        'texts'         => 'texts',
        'notifications' => 'notifications',
    ];

    public function __construct(Form $form, $attributes = [])
    {
        $attributes['limitations']   = $attributes['limitations'] ?? [];
        $attributes['behaviors']     = $attributes['behaviors'] ?? [];
        $attributes['design']        = $attributes['design'] ?? [];
        $attributes['texts']         = $attributes['texts'] ?? [];
        $attributes['notifications'] = $attributes['notifications'] ?? [];
        $this->form                  = $form;
        parent::__construct($attributes);
    }


    public function getDesignSettings($key, $default = null)
    {
        return $this->getAttribute("design.{$key}", $default);
    }

    /**
     * @return string
     */

    public function getCustomCss()
    {
        return $this->getDesignSettings('customCss', '');
    }

    public function castToLimitations($data)
    {
        $limitations = [];
        foreach ($data as $index => $limitationRule) {
            $limitations[$index] = new FormLimitation($limitationRule);
        }

        return Collection::create($limitations);
    }

    public function castToBehaviors($data)
    {
        $behaviors = [];
        foreach ($data as $index => $behavior) {
            $behaviors[$index] = new FormBehavior($behavior);
        }

        return Collection::create($behaviors);
    }

    public function castToDesign($data)
    {
        return new FormDesign($data);
    }

    public function castToTexts($data)
    {
        $data = Arrays::merge(
            [
                'buttons'     => [
                    'submit' => '',
                    'reset'  => '',
                ],
                'messages'    => [
                    'submitting' => '',
                    'error'      => '',
                ],
                'validations' => [
                    'required'  => '',
                    'email'     => '',
                    'minLength' => '',
                    'maxLength' => '',
                ],
            ],
            $data
        );

        return Collection::create($data);
    }

    public function castToNotifications($data)
    {
        if (empty($data[0])) {
            $data[0] = [
                'enabled'   => false,
                'to'        => wp_get_current_user()->user_email,
                'replyTo'   => '',
                'subject'   => 'New entry',
                'body'      => "Hello,\n\nYou have received a new entry: \n\n{{entry}}",
                'arguments' => [],
            ];
        }

        if (empty($data[1])) {
            $data[1] = [
                'enabled'   => false,
                'to'        => '',
                'replyTo'   => wp_get_current_user()->user_email,
                'subject'   => 'Your entry has been received',
                'body'      => 'Hello, this email is to confirm that your entry was received successfully.',
                'arguments' => [],
            ];
        }

        foreach ($data as $index => $notification) {
            $data[$index] = new FormNotification($notification);
        }

        return Collection::create($data);
    }

    public function isAcceptingNewEntries()
    {
        return (boolean) $this->getAttribute('acceptNewEntries');
    }

    public function jsonSerialize(): array
    {
        $json                = parent::jsonSerialize();
        $json['limitations'] = (object) $json['limitations']->toArray();
        $json['behaviors']   = (object) $json['behaviors']->toArray();

        return $json;
    }
}
