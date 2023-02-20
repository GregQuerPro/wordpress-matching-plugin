<?php get_header(); ?>

<h1 class="mb-4">Résultats pour votre recherche "<?= sprintf(apply_filters('montheme_search_title', "Résultat pour votre recherche \"%s"), get_search_query()) ?>"</h1>

<form class="form-inline">
    <input type="search" name="s" class="form-control mb-2 mr-sm-2" value="<?= get_search_query() ?>" placeholder="Votre recherche">
    <div class="form-check mb-2 mr-sm-2">
        <input type="checkbox" value="1" name="sponso" id="inlineFormCheck" class="form-check-input" <?= checked('1', get_query_var('sponso')) ? 'checked' : '' ?>>
        <label for="inlineFormCheck" class="form-check-label">Article sponsorisé seulement</label>
    </div>
    <button type="submit" class="btn btn-primary mb-2">Rechercher</button>
</form>

<ul class="nav nav-pills my-4">
    <?php foreach ($sports as $sport) : ?>
        <li class="nav-item">
            <a href="<?= get_term_link($sport) ?>" class="nav-link<?= is_tax('sport', $sport->term_id) ? ' active' : '' ?>"><?= $sport->name ?></a>
        </li>
    <?php endforeach ?>
</ul>

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