<?php get_header(); ?>

<h1 class="mb-5">Voir tous nos biens</h1>

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