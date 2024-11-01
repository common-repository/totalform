<?php
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalForm\Plugin;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Colors;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Template;

/**
 * @var Template $template
 * @var Form $form
 * @var array $assets
 * @var string $customCss
 */

?>
<style type="text/css">
    .totalform-wrapper {
        --color-primary: <?php echo esc_html($form->settings->design->getAttribute('colors.primary')); ?>;
        --color-primary-dark: <?php echo esc_html(Colors::darken($form->settings->design->getAttribute('colors.primary'), 20)); ?>;
        --color-primary-light: <?php echo esc_html(Colors::lignten($form->settings->design->getAttribute('colors.primary'), 50)); ?>;
        --color-primary-alpha: <?php echo esc_html(Colors::opacity($form->settings->design->getAttribute('colors.primary'), 0.15)); ?>;
        --color-primary-contrast: <?php echo esc_html($form->settings->design->getAttribute('colors.primary.contrast', '#FFFFFF')); ?>;

        --color-secondary: <?php echo esc_html($form->settings->design->getAttribute('colors.secondary')); ?>;
        --color-secondary-dark: <?php echo esc_html(Colors::darken($form->settings->design->getAttribute('colors.secondary'), 20)); ?>;
        --color-secondary-contrast: <?php echo esc_html($form->settings->design->getAttribute('colors.secondary.contrast')); ?>;
        --color-secondary-alpha: <?php echo esc_html(Colors::opacity($form->settings->design->getAttribute('colors.secondary'), 0.15)); ?>;
        --color-secondary-light: <?php echo esc_html(Colors::lignten($form->settings->design->getAttribute('colors.secondary'), 50)); ?>;

        --color-success: <?php echo esc_html($form->settings->design->getAttribute('colors.success')); ?>;
        --color-success-dark: <?php echo esc_html(Colors::darken($form->settings->design->getAttribute('colors.success'), 20)); ?>;
        --color-success-light: <?php echo esc_html(Colors::lignten($form->settings->design->getAttribute('colors.success'), 50)); ?>;

        --color-error: #ff5722;
        --color-error-dark: <?php echo esc_html(Colors::darken('#ff5722', 20)); ?>;
        --color-error-alpha: <?php echo esc_html(Colors::opacity('#ff5722', 0.15)); ?>;
        --color-error-light: <?php echo esc_html(Colors::lignten('#ff5722', 70)); ?>;

        --color-background: #888888;
        --color-background-dark: #555555;
        --color-background-contrast: #ffffff;
        --color-background-light: #fafafa;

        --color-dark: <?php echo esc_html($form->settings->design->getAttribute('colors.dark')); ?>;
        --color-dark-contrast: <?php echo esc_html($form->settings->design->getAttribute('colors.dark.contrast')); ?>;
        --color-dark-alpha: <?php echo esc_html(Colors::opacity($form->settings->design->getAttribute('colors.dark'), 0.05)); ?>;

        --font-family: <?php echo esc_html(sprintf('%s', $form->settings->design->getAttribute('typography.fontFamily'))); ?>;
        --size: var(<?php echo esc_html(sprintf('--size-%s', $form->settings->design->getAttribute('size'))); ?>);
        --space: var(<?php echo esc_html(sprintf('--space-%s', $form->settings->design->getAttribute('space'))); ?>);
        --radius: var(<?php echo esc_html(sprintf('--radius-%s', $form->settings->design->getAttribute('radius'))); ?>);
        --width: var(<?php echo esc_html(sprintf('--width-%s', $form->settings->design->getAttribute('width'))); ?>);
    }


    <?php if(!Plugin::options('advanced.disableDefaultStyle', false)) {echo esc_html($customCss);} ?>
</style>
