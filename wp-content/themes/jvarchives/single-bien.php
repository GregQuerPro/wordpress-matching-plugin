<?php get_header() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <h1 class="mb-4"><?php the_title() ?></h1>
        <div class="mb-5">
            <img class="card-img-top" src="<?php the_post_thumbnail_url() ?>">
        </div>
        <div>
            <?php the_content() ?>
        </div>
        <div>Surface: <?= get_field('surface') ?> m²</div>
        <?php if (get_field('jardin') && !empty(get_field('surface_jardin'))) : ?>
            <div class="mb-4">Surface Jardin: <?= get_field('surface_jardin') ?> m²</div>
        <?php endif ?>

        <h3>Options</h3>
        <?php if (have_rows('options')) : ?>
            <div class="mb-4">
                <?php while (have_rows('options')) : the_row() ?>
                    <div>
                        <?= get_sub_field('nom') ?> - <?= get_sub_field('url') ?><br>
                    </div>
                <?php endwhile ?>
            </div>
        <?php endif ?>

        <h3>Gallerie</h3>
        <div style="display: flex" class="mb-4">
            <?php foreach (get_field('photos') as $photo) : ?>
                <div style="margin-right:10px">
                    <img src="<?= $photo['sizes']['thumbnail']  ?>" alt="">
                </div>
            <?php endforeach ?>
        </div>
    <?php endwhile ?> <?php endif; ?>

<?php get_footer() ?>