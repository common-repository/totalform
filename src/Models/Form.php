<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Exceptions\Forms\FormNotFound;
use TotalForm\Filters\Forms\FormAfterEntryViewFilter;
use TotalForm\Filters\Forms\FormLinkFilter;
use TotalForm\Models\Concerns\CastMetaFieldToCollection;
use TotalForm\Plugin;
use TotalForm\Tasks\Entries\CountEntries;
use TotalForm\Tasks\Forms\LoadFormTextDomain;
use TotalForm\Tasks\Forms\RenderForm;
use TotalFormVendors\TotalSuite\Foundation\Database\TableModel;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class Form
 *
 * @property int $id
 * @property string $uid
 * @property string $title
 * @property string $slug
 * @property Collection<FormSection>|FormSection[] $sections
 * @property FormSettings $settings
 * @property Collection $meta
 * @property string $published_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property int $user_id
 *
 * @package TotalForm\Models
 */
class Form extends TableModel
{
    const ACTION_NEXT       = 'next';
    const ACTION_SECTION    = 'section';
    const ACTION_CONDITIONS = 'conditions';
    const ACTION_SUBMIT     = 'submit';

    use CastMetaFieldToCollection;

    /**
     * @var string
     */
    protected $table = 'totalform_forms';

    /**
     * @var array
     */
    protected $types = [
        'id'       => 'integer',
        'sections' => 'sections',
        'settings' => 'settings',
        'meta'     => 'meta',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'uid',
        'title',
        'slug',
        'sections',
        'settings',
        'user_id',
        'published_at',
        'updated_at',
        'deleted_at',
    ];

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        LoadFormTextDomain::invoke($this);
    }

    /**
     * @param $id
     *
     * @return self
     * @throws Exception
     */
    public static function byIdAndActive($id)
    {
        $form = static::query();
        if (is_numeric($id)) {
            $form->where('id', $id);
        } elseif (strlen($id) === 36) {
            $form->where('uid', $id);
        } else {
            $form->where('slug', $id);
        }
        $form = $form->first();
        FormNotFound::throwUnless($form, __('Form not found', 'totalform'));

        return $form;
    }

    /**
     * @param  string  $data
     *
     * @return Collection
     * @noinspection PhpUnused
     */
    public function castToSections($data): Collection
    {
        $sections = [];
        $previous = null;

        if (!empty($data)) {
            $data = is_string($data) ? json_decode($data, true) : $data;

            foreach ($data as $index => $section) {
                $current = new FormSection($this, $section);
                $current->setIndex($index);

                if ($previous instanceof FormSection) {
                    $current->setPreviousSection($previous);
                    $previous->setNextSection($current);
                }

                $sections[] = $previous = $current;
            }
        }

        return Collection::create($sections);
    }

    public function castToSettings($data)
    {
        $settings = [];

        if (!empty($data)) {
            $settings = is_string($data) ? json_decode($data, true) : $data;
        }

        return new FormSettings($this, $settings);
    }

    /**
     * @param  string  $formUid
     *
     * @return Form
     * @throws Exception
     */
    public static function byUid($formUid): Form
    {
        $form = static::query()->where('uid', $formUid)->orWhere('slug', $formUid)->first();
        FormNotFound::throwUnless($form, __('Form not found', 'totalform'));

        return $form;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $form                 = parent::toArray();
        $form['url']          = $this->getUrl();
        $form['preview_url']  = $this->getPreviewUrl();
        $form['updated_at']   = $form['updated_at'] ? date_i18n(DATE_ATOM, strtotime($form['updated_at'])) : $form['updated_at'];
        $form['published_at'] = $form['published_at'] ? date_i18n(DATE_ATOM, strtotime($form['published_at'])) : $form['published_at'];
        $form['deleted_at']   = $form['deleted_at'] ? date_i18n(DATE_ATOM, strtotime($form['deleted_at'])) : $form['deleted_at'];

        return $form;
    }

    public function toPublic()
    {
        return [
            'uid'      => $this->uid,
            'url'      => $this->getUrl(),
            'preview'  => $this->isPreview(),
            'settings' => [
                'behaviors' => [
                    'recaptcha' => $this->settings['behaviors.recaptcha']
                ],
            ],
        ];
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function withEntriesCount()
    {
        $this['entries_count'] = CountEntries::invoke(['form_uid' => $this->uid]);

        return $this;
    }


    /**
     * @return $this
     * @throws Exception
     */
    public function withEntriesUnreadCount()
    {
        $this['unread_entries_count'] = CountEntries::invoke(['form_uid' => $this->uid, 'status' => 'unread']);

        return $this;
    }


    /**
     * @param  array  $arguments
     *
     * @return string
     */
    public function getUrl($arguments = [])
    {
        $baseUrl               = site_url();
        $arguments['form_uid'] = $this->uid;

        if (Plugin::env()->isPrettyPermalinks()) {
            $base = FormLinkFilter::apply('form');

            $baseUrl = site_url("{$base}/".$this->slug ?: $this->uid."/")."/";
            unset($arguments['form_uid']);
        }

        return add_query_arg($arguments, $baseUrl);
    }

    /**
     * @param  array  $arguments
     *
     * @return string
     */
    public function getPreviewUrl($arguments = [])
    {
        $baseUrl                   = site_url();
        $arguments['form_preview'] = $this->uid;

        if (Plugin::env()->isPrettyPermalinks()) {
            $baseUrl = site_url("preview-form/".$this->uid)."/";
            unset($arguments['form_preview']);
        }

        return add_query_arg($arguments, $baseUrl);
    }

    /**
     * @throws Exception
     * @throws \TotalFormVendors\TotalSuite\Foundation\Exceptions\ModuleException
     */
    public function render()
    {
        return RenderForm::invoke($this);
    }


    public function setPreview($preview = true)
    {
        $this->setAttribute('preview', $preview);
    }

    public function isPreview()
    {
        return $this->getAttribute('preview', false);
    }

    public function isLimitationEnabled(string $string)
    {
        return false;
    }

    public function getDesignSettings(string $string)
    {
        return '';
    }

    public function getWelcomeBlocks()
    {
        return [];
    }

    public function getThankYouBlocks()
    {
        return [];
    }

    public function getThankYouMessage()
    {
        if (!empty($this->settings->behaviors['customThankYouMessage']->enabled)) {
            return $this->settings->behaviors['customThankYouMessage.arguments.content'] ?? '';
        }

        return __('Your entry has been received.', 'totalform');
    }

    public function getRedirectionView()
    {
        if (!empty($this->settings->behaviors['redirectAfterSubmission']->enabled)) {
            $url = $this->settings->behaviors['redirectAfterSubmission.arguments.url'] ?? '';

            return sprintf("<script type=\"text/javascript\">top.location = '%s';</script>", esc_js($url));
        }

        return null;
    }

    public function getAfterEntryView()
    {
        return FormAfterEntryViewFilter::apply($this->getRedirectionView() ?: $this->getThankYouMessage());
    }
}
