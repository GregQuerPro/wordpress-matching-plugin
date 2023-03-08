<?php global $post; ?>
<article class="bloc-highlighted">
    <h2><?= get_field('titre') ?></h2>
    <div class="row">
        <?php if ($posts = get_field('articles_a_remonter')) : ?>
            <?php foreach ($posts as $post) : setup_postdata($post) ?>
                <div class="col-md-4">
                    <?= get_template_part('partials/card', 'post') ?>
                </div>
                <?php wp_reset_postdata() ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</article>