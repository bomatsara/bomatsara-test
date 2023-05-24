<?php
namespace BomatsaraTest\Shortcode;

use BomatsaraTest\Shortcode\ShortcodeInterface;
use WP_Query;

class ShortcodePost implements ShortcodeInterface
{
    public function __construct()
    {
        $this->register_shortcode();
    }

    public function register_shortcode(): void
    {
        add_shortcode('bt_shortcode_post', [$this, 'shortcode_callback']);
    }

    public function shortcode_callback($atts)
    {
        $args = shortcode_atts([
            'title' => 'Articles',
            'count' => 5,
            'sort'  => 'date',
            'ids'   => '',
        ], $atts);

        $this->enqueue_styles();

        $query_args = [
            'post_type'      => 'post',
            'posts_per_page' => $args['count'],
            'orderby'        => $args['sort'],
            'order'          => 'DESC',
        ];

        if (!empty($args['ids'])) {
            $query_args['post__in'] = explode(',', $args['ids']);
        }

        if ($args['sort'] === 'rating') {
            $query_args['orderby'] = 'meta_value_num';
            $query_args['meta_key'] = 'rating';
            $query_args['meta_query'] = [
                'relation' => 'OR',
                [
                    'key'     => 'rating',
                    'compare' => 'EXISTS',
                ],
                [
                    'key'     => 'rating',
                    'compare' => 'NOT EXISTS',
                ],
            ];
        }

        $query = new WP_Query($query_args);

        ob_start();

        $template_path = plugin_dir_path( dirname( __FILE__ ) ) . '../templates/shortcode-post.php';
        include($template_path);

        return ob_get_clean();
    }


    public function enqueue_styles(): void
    {
        wp_enqueue_style('bt-shortcode-post-style', plugin_dir_url(__DIR__) . '../assets/dist/shortcode_post.min.css');
    }
}