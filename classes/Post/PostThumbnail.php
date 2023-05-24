<?php

namespace BomatsaraTest\Post;

class PostThumbnail
{
    public function __construct()
    {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
    }

    public function set_post_thumbnail(int $post_id, string $image_url): void
    {
        $media = media_sideload_image($image_url, $post_id);

        if (!is_wp_error($media)) {
            $args = [
                'post_type' => 'attachment',
                'posts_per_page' => 1,
                'post_status' => 'any',
                'post_parent' => $post_id,
            ];

            $attachments = get_posts($args);

            if ($attachments) {
                set_post_thumbnail($post_id, $attachments[0]->ID);
            }
        }
    }
}