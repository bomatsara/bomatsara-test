<?php

namespace BomatsaraTest\Post;

class Post
{
    public function create(array $post_data): int
    {
        return wp_insert_post($post_data);
    }

    public function is_exists(string $title): bool|int
    {
        global $wpdb;
        $args = [];

        $post_title = wp_unslash(sanitize_post_field('post_title', $title, 0, 'db'));
        $post_name = wp_unslash(sanitize_title($title));

        $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";

        if (!empty($title)) {
            $query .= ' AND post_title = %s';
            $args[] = $post_title;
        }

        if (!empty($post_name)) {
            $query .= ' AND post_name = %s';
            $args[] = $post_name;
        }

        if (!empty($args)) {
            return (int)$wpdb->get_var($wpdb->prepare($query, $args));
        }

        return false;
    }
}