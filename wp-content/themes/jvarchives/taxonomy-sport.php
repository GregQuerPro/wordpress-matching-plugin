<?php get_header(); ?>

<h1 class="mb-4"><?= esc_html(get_queried_object()->name) ?></h1>
<p class="mb-4"><?= esc_html(get_queried_object()->description) ?></p>

<?php $sports = get_terms(['taxonomy' => 'sport']) ?>

<?php if (is_array($sports)) : ?>
    <?php
    set_query_var('sports', $sports);
    get_template_part('partials/filter', 'post')
    ?>
<?php endif ?>

<?php if (have_posts()) : ?>
    <section class="row">
        <?php while (have_posts()) : the_post(); ?>
            <div class="col-sm-4">
                <?php get_template_part('partials/card', 'post') ?>
            </div>
        <?php endwhile ?>

        <?php montheme_pagination() ?>

    </section>
<?php else : ?>
    <h1>Pas d'articles</h1>
<?php endif; ?>

<?php get_footer() ?>