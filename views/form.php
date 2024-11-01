<?php
! defined( 'ABSPATH' ) && exit();

/**
 * @var \TotalForm\Models\Form $form
 * @var string $content
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php echo is_admin_bar_showing() ? 'with-admin-bar' : 'without-admin-bar'; ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>

    <?php if (is_embed()): ?>
        <style>
            html {
                overflow: auto;
            }

            body::before, body::after {
                display: none !important;
            }

            body {
                background: transparent !important;
            }

            .totalform-content {
                padding: 30px;
            }

        </style>
    <?php else: ?>
        <style>
            html {
                overflow: auto;
            }

            html[without-admin-bar] {
                margin: 0 !important;
            }

            body::before, body::after {
                display: none !important;
            }

            body {
                background: #eeeeee !important;
            }

            .totalform-content {
                margin: 30px auto;
                max-width: 620px;
                padding: 30px;
                background: #ffffff;
                box-shadow: 0 2px 16px rgba(0, 0, 0, 0.05);
                border-radius: 6px;
            }

            .totalform-warning {
                padding: 15px;
                margin: 0 !important;
                background: #EF6C00;
                color: #FFFFFF;
                text-align: center;
                box-shadow: 0 1px 8px rgba(0, 0, 0, 0.1);
            }

            @media screen and (max-width: 782px) {
                .totalform-content {
                    border-radius: 0;
                    margin: auto;
                }
            }
        </style>
    <?php endif; ?>
</head>

<body <?php body_class(); ?>>

<?php if (!$form->published_at && current_user_can('administrator')): ?>
    <p class="totalform-warning">
        <?php esc_html_e('This form is private. You need to publish it first to make it publicly accessible.'); ?>
    </p>
<?php endif; ?>

<?php if ($form->isPreview()): ?>
    <p class="totalform-warning">
        <?php esc_html_e('Changes are not saved while in preview.'); ?>
    </p>
<?php endif; ?>

<main class="totalform-content">
    <?php echo wp_kses($content, [
        'div'      => [
            'class'         => [],
            'height'        => [],
            'ref'           => [],
            'v-scope'       => [],
            '@vue:mounted'  => [],
            'v-if'          => [],
            'v-bind:hidden' => [],
            'v-for'         => [],
            'v-text'        => [],
        ],
        'form'     => [
            'action'              => [],
            'ref'                 => [],
            'method'              => [],
            'novalidate'          => [],
            'v-bind:class'        => [],
            'v-on:submit.prevent' => [],
        ],
        'style'    => [],
        'hr'       => ['class' => [], 'id' => []],
        'br'       => ['class' => [], 'id' => []],
        'h1'       => ['class' => [], 'id' => []],
        'h2'       => ['class' => [], 'id' => []],
        'h3'       => ['class' => [], 'id' => []],
        'h4'       => ['class' => [], 'id' => []],
        'h6'       => ['class' => [], 'id' => []],
        'p'        => ['class' => [], 'id' => []],
        'label'    => ['class' => [], 'name' => [], 'id' => [], 'ref' => [], 'for' => []],
        'span'     => ['class' => [], 'name' => [], 'id' => [], 'ref' => []],
        'table'    => ['class' => [], 'id' => [], 'ref' => []],
        'thead'    => ['class' => [], 'id' => [], 'ref' => []],
        'tbody'    => ['class' => [], 'id' => [], 'ref' => []],
        'tfoot'    => ['class' => [], 'id' => [], 'ref' => []],
        'th'       => ['class' => [], 'id' => [], 'ref' => []],
        'tr'       => ['class' => [], 'id' => [], 'ref' => []],
        'td'       => ['class' => [], 'id' => [], 'ref' => []],
        'iframe'   => [
            'class'           => [],
            'id'              => [],
            'src'             => [],
            'frameborder'     => [],
            'allowfullscreen' => [],
            'allow'           => [],
            'width'           => [],
            'height'          => [],
        ],
        'figure'   => [
            'class' => [],
            'id'    => [],
            'ref'   => [],
        ],
        'img'      => [
            'class'  => [],
            'id'     => [],
            'src'    => [],
            'srcset' => [],
            'alt'    => [],
            'width'  => [],
            'height' => [],
        ],
        'input'    => [
            'class'  => [],
            'name'   => [],
            'id'     => [],
            'ref'    => [],
            'value'  => [],
            'type'   => [],
            'accept' => [],
        ],
        'button'   => ['class' => [], 'name' => [], 'id' => [], 'ref' => [], 'value' => [], 'type' => []],
        'textarea' => ['class' => [], 'name' => [], 'id' => [], 'ref' => [], 'value' => [], 'rows' => []],
        'select'   => ['class' => [], 'name' => [], 'id' => [], 'ref' => [], 'value' => [], 'multiple' => []],
        'option'   => ['class' => [], 'name' => [], 'id' => [], 'ref' => [], 'value' => [], 'selected' => []],
        'template' => ['ref' => []],
    ]); ?>
</main>

<?php wp_footer(); ?>

</body>
</html>
