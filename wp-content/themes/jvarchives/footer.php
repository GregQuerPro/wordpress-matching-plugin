</div>
<footer>
    <?php wp_nav_menu([
        'theme_location' => 'footer',
        'container' => false,
        'menu_class' => 'navbar-nav me-auto'
    ]);
    the_widget(YoutubeWidget::class, ['title' => '', 'youtube' => 'EBQGQQWbiqQ'], ['before_title' => '', 'after_title' => '']);
    ?>
    <div>
        <?= get_option('agence_horaire') ?>
    </div>
</footer>
<?php wp_footer(); ?>

</body>

</html>