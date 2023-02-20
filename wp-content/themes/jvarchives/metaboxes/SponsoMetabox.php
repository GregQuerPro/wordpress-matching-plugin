<?php

class SponsoMetaBox
{

    const META_KEY = 'montheme_sponso';
    const NONCE = '_montheme_sponso_nonce';

    public static function register()
    {
        add_action('add_meta_boxes', [self::class, 'add'], 10, 2);
        add_action('save_post', [self::class, 'save']);
    }

    public static function add($postType, WP_POST $post)
    {
        if ($postType === 'post' && current_user_can('publish_posts', $post)) {
            add_meta_box(self::META_KEY, 'Sponsoring', [self::class, 'render'], 'post', 'side');
        }
    }

    public static function render(WP_POST $post)
    {
        $value = get_post_meta($post->ID, self::META_KEY, true);
        wp_nonce_field(self::NONCE, self::NONCE);
?>
        <input type="hidden" value="0" name="<?php echo self::META_KEY ?>">
        <input type="checkbox" value="1" name="<?php echo self::META_KEY ?>" <?= checked($value, '1') ?>>
        <label for="montheme_sponso">Cet article est-il sponsorisé ?</label>
<?php
    }

    public static function save(int $post)
    {
        if (array_key_exists(self::META_KEY, $_POST) && current_user_can('publish_posts', $post)) {
            if ($_POST[self::META_KEY] === '0') {
                delete_post_meta($post, self::META_KEY);
            } else {
                update_post_meta($post, self::META_KEY, 1);
            }
        }
    }
}
