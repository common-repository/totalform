<?php
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Template;

/**
 * @var string $apiBase
 * @var string $nonce
 * @var Template $template
 * @var Form $form
 * @var string $customCss
 * @var array $options
 */
$scope = [
    'config' => [
        'apiBase'   => esc_attr($apiBase),
        'nonce'     => $nonce,
        'recaptcha' => $options['recaptcha'],
    ],
    'form'   => $form->toPublic(),
];
?>
<div class="totalform"
     v-scope="TotalForm(<?php echo esc_attr(json_encode($scope)) ?>)"
     @vue:mounted="mounted">

    <form action="<?php echo esc_url($apiBase . '/entry'); ?>"
          ref="form"
          method="post"
          novalidate
          v-bind:class="{'is-processing': isProcessing}"
          v-on:submit.prevent="submit()">
        <input type="hidden" name="form_uid" value="<?php echo esc_attr($form->uid); ?>">
        <div class="global-error" v-if="errors.global.length" hidden v-bind:hidden="undefined">
            <div v-for="error in errors.global" v-text="error"></div>
        </div>
        <?php foreach ($form->sections as $section): ?>
            <section>
                <input type="hidden" name="section_uid" value="<?php echo esc_attr($section->uid); ?>">
                <?php
                foreach ($section->layouts as $layout): ?>
                    <div class="row">
                        <?php foreach ($layout->columns as $column): ?>
                            <div class="column column-width-<?php echo esc_attr($column->width['large']); ?>">
                                <?php foreach ($column->blocks as $block): ?>
                                    <?php if ($block->isContent()): ?>
                                        <?php
                                            echo $block->render()
                                                  ->setAttribute(
                                                      'class',
                                                      ["block block-type-{$block->type} {$block->argument('cssClass')}"],
                                                      true
                                                  )
                                                  ->setAttribute(
                                                      'ref',
                                                      $block->uid
                                                  )
                                        ?>
                                    <?php else: ?>
                                        <div class="block block-type-<?php echo esc_attr($block->type); ?> <?php echo esc_attr($block->argument('cssClass')); ?>"
                                             ref="<?php echo esc_attr($block->uid); ?>">
                                            <?php echo $block->render(); ?>
                                            <div class="block-error"
                                                 v-text="errors.blocks['<?php echo esc_attr($block->uid); ?>']"></div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endforeach; ?>
        <button type="submit" class="button"><?php esc_html_e('Submit', 'totalform'); ?></button>
    </form>


    <?php if ($form->isPreview()): ?>
        <template ref="thankyou">
            <?php echo esc_html($form->getAfterEntryView()); ?>
        </template>
    <?php endif; ?>
</div>
