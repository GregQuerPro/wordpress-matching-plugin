<?php

namespace MonTheme;

use Walker_Comment;

/**
 * Comment API: Walker_Comment class
 *
 * @package WordPress
 * @subpackage Comments
 * @since 4.4.0
 */

/**
 * Core walker class used to create an HTML list of comments.
 *
 * @since 2.7.0
 *
 * @see Walker
 */
class CommentWalker extends Walker_Comment
{

    /**
     * Starts the list before the elements are added.
     *
     * @since 2.7.0
     *
     * @see Walker::start_lvl()
     * @global int $comment_depth
     *
     * @param string $output Used to append additional content (passed by reference).
     * @param int    $depth  Optional. Depth of the current comment. Default 0.
     * @param array  $args   Optional. Uses 'style' argument for type of HTML list. Default empty array.
     */
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $GLOBALS['comment_depth'] = $depth + 1;
        $output .= '<div class="m-4">';
    }

    /**
     * Ends the list of items after the elements are added.
     *
     * @since 2.7.0
     *
     * @see Walker::end_lvl()
     * @global int $comment_depth
     *
     * @param string $output Used to append additional content (passed by reference).
     * @param int    $depth  Optional. Depth of the current comment. Default 0.
     * @param array  $args   Optional. Will only append content if style argument value is 'ol' or 'ul'.
     *                       Default empty array.
     */
    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        $GLOBALS['comment_depth'] = $depth + 1;
        $output .= '</div>';
    }

    /**
     * Outputs a comment in the HTML5 format.
     *
     * @since 3.6.0
     *
     * @see wp_list_comments()
     *
     * @param WP_Comment $comment Comment to display.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function html5_comment($comment, $depth, $args)
    {
        $tag = ('div' === $args['style']) ? 'div' : 'li';

        $commenter          = wp_get_current_commenter();
        $show_pending_links = !empty($commenter['comment_author']);

        if ($commenter['comment_author_email']) {
            $moderation_note = __('Your comment is awaiting moderation.');
        } else {
            $moderation_note = __('Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.');
        }
?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class($this->has_children ? 'parent' : '', $comment); ?>>
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body row">
                <footer class="comment-meta col-md-2">
                    <div class="comment-author vcard">
                        <?php
                        if (0 != $args['avatar_size']) {
                            echo get_avatar($comment, $args['avatar_size']);
                        }
                        ?>
                    </div><!-- .comment-author -->

                    <?php if ('0' == $comment->comment_approved) : ?>
                        <em class="comment-awaiting-moderation"><?php echo $moderation_note; ?></em>
                    <?php endif; ?>
                </footer><!-- .comment-meta -->

                <div class="comment-content col-md-10">
                    <?php
                    $comment_author = get_comment_author_link($comment);

                    if ('0' == $comment->comment_approved && !$show_pending_links) {
                        $comment_author = get_comment_author($comment);
                    }

                    printf(
                        /* translators: %s: Comment author link. */
                        __('%s <span class="says">says:</span>'),
                        sprintf('<b class="fn">%s</b>', $comment_author)
                    );
                    ?>

                    <div class="comment-metadata">
                        <?php
                        printf(
                            '<a href="%s"><time datetime="%s">%s</time></a>',
                            esc_url(get_comment_link($comment, $args)),
                            get_comment_time('c'),
                            sprintf(
                                /* translators: 1: Comment date, 2: Comment time. */
                                __('%1$s at %2$s'),
                                get_comment_date('', $comment),
                                get_comment_time()
                            )
                        );

                        edit_comment_link(__('Edit'), ' <span class="edit-link">', '</span>');
                        ?>
                    </div><!-- .comment-metadata -->

                    <?php comment_text(); ?>
                </div><!-- .comment-content -->

                <?php
                if ('1' == $comment->comment_approved || $show_pending_links) {
                    comment_reply_link(
                        array_merge(
                            $args,
                            array(
                                'add_below' => 'div-comment',
                                'depth'     => $depth,
                                'max_depth' => $args['max_depth'],
                                'before'    => '<div class="reply col-md-12">',
                                'after'     => '</div>',
                            )
                        )
                    );
                }
                ?>
            </article><!-- .comment-body -->
    <?php
    }
}
