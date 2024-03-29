<?php get_header() ?>

<?php if (get_post_meta(get_the_ID(), SponsoMetaBox::META_KEY, true) === "1") : ?>
    <div class="alert alert-info">Cet article est sponsorisé</div>
<?php endif ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <h1 class="mb-4"><?php the_title() ?></h1>
        <div class="mb-5">
            <img class="card-img-top" src="<?php the_post_thumbnail_url() ?>">
        </div>
        <div>
            <?php the_content() ?>
        </div>

        <?php
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>

        <h2>Nos derniers articles</h2>

        <div class="row">
            <?php
            $sports = array_map(function ($term) {
                return $term->term_id;
            }, get_the_terms(get_post(), 'sport'));
            $query = new WP_Query([
                'post__not_in' => [get_the_ID()],
                'post_type' => 'post',
                'post_per_page' => 3,
                'tax_query' => [
                    [
                        'taxonomy' => 'sport',
                        'terms' => $sports
                    ]
                ],
                'meta_query' => [
                    [
                        'key' => SponsoMetaBox::META_KEY,
                        'compare' => 'EXISTS'
                    ]
                ]
            ]);
            while ($query->have_posts()) : $query->the_post();
            ?>
                <div class="col-sm-4">
                    <?php get_template_part('partials/card', 'post') ?>
                </div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>

    <?php endwhile ?> <?php endif; ?>

<?php get_footer() ?>