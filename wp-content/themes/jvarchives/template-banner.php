<?php

/*
 * Template Name: Page avec bannière
 * Template Post Type: page, post
 */

?>

<?php get_header() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <p>Ici la bannière</p>
        <h1 class="mb-4"><?php the_title() ?></h1>
        <div class="mb-5">
            <img class="card-img-top" src="<?php the_post_thumbnail_url() ?>">
        </div>
        <div>
            <?php the_content() ?>
        </div>
    <?php endwhile ?> <?php endif; ?>

<?php get_footer() ?>