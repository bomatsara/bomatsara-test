<?php
/**
 * Template for displaying posts in the Shortcode Post
 *
 *
 * @package BomatsaraTest
 * @version 1.0.0
 *
 * @var WP_Query $query Instance of WP_Query being used for post loop.
 * @var array $args Shortcode attributes used for query and display options.
 */

if (!defined('ABSPATH')) {
    exit;
}

if ($query->have_posts()) : ?>

    <div class="bt-shortcode-post">
        <div class="bt-shortcode-post__title"><?= ucfirst(esc_html($args['title'])); ?></div>

        <?php while ($query->have_posts()) : $query->the_post(); ?>

            <?php
                $categories = get_the_category();
                $category_link = isset($categories[0]) ? get_category_link($categories[0]->term_id) : '#';
                $rating = get_post_meta(get_the_ID(), 'rating', true);
                $site_link = get_post_meta(get_the_ID(), 'site_link', true);
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <?php if (has_post_thumbnail()): ?>
                    <div class="post__image">
                        <?php the_post_thumbnail(); ?>
                    </div>
                <?php endif; ?>

                <div class="post__content">
                    <div class="post__category"><a href="<?= esc_url($category_link); ?>"><?= esc_html($categories[0]->name); ?></a></div>

                    <div class="post__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>

                    <div class="post__meta">
                        <a class="post__read-more" href="<?php the_permalink(); ?>">Read more</a>

                        <?php if($rating): ?>
                            <div class="post__rating">⭐ <?= esc_html($rating); ?></div>
                        <?php endif; ?>

                        <?php if($site_link): ?>
                            <div class="post__button">
                                <a href="<?= esc_url($site_link); ?>" class="bt-btn">Go to site</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>

        <?php endwhile; wp_reset_postdata(); ?>
    </div>
<?php endif; ?>
